<?php
/**
 * Created by PhpStorm.
 * User: AGC-PC
 * Date: 5/29/2018
 * Time: 4:17 PM
 */

namespace App\Functions;


use App\DxTransaction;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;
use DateTime;

class UploadProofListClass
{

    public function upload($content, $filename, $types) {
        $login_user = Auth::user();
        if (FALSE === $type = $this->document_type($types, $content)) {
            DB::rollBack();
            return json_encode(['a' => 'Cannot determine what type of proof list is \'' . $filename . '\'! You might need to upload transaction types!', 'b' => 'error']);
        }
        if (FALSE === $indices = $this->indices($content)) {
            DB::rollBack();
            return json_encode(['a' => 'Cannot find the file headers in \'' . $filename . '\'!', 'b' => 'error']);
        }
        $content = preg_replace('/\n\S/i', "\n", $content);
        $content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content);
        $content = preg_replace("/\n\s{" . $indices['docdate']['beg'] . ',' . ($indices['docdate']['beg'] + 1) . "}[a-z]+.*/i", "", $content);
        $content = rtrim($content);
        $pattern = '/\n\s{' . $indices['docdate']['beg'] . '}[^\s]{1,10}.*/';
        if (preg_match_all($pattern, $content, $headers) < 1) {
            DB::rollBack();
            return json_encode(['a' => 'Can\'t find any transaction from \'' . $filename . '\'!', 'b' => 'error']);
        }
        $details = preg_split($pattern, $content);
        $percents = UtilityClass::percents(count($headers[0]) - 1);

        $bankfile = Storage::get("set.json");
        $banks = json_decode($bankfile, false);

        $str_banks = '';
        $max = count($banks->validbanks);
        foreach ($banks->validbanks as $key => $person_a) {
            $str_banks .= $person_a;
            if ($key+1!=$max) {
                $str_banks .='|';
            }
        }

        try {
            foreach ($headers[0] as $header_key => $value) {
                $detail = $this->transaction_details($value, $details[$header_key + 1], $indices, $str_banks);
                if ($detail == 0) {
                    return json_encode(["a" => session('errorinfo') . " at file ".$filename, "b" => "error"]);
                }
                $detail['doc_type'] = $type;
                $detail['status'] = 'Posted';
                $detail['hash'] = $type . substr($detail['doc_date'], 2, 2) . $detail['doc_no'];
                $detail['created_by'] = $login_user->user_id;
                $detail['updated_by'] = $login_user->user_id;
                $detail['buid'] = auth()->user()->bunitid;
                echo auth()->user()->bunitid;
//                return $details;
//                exit();
                if (DxTransaction::where('hash', $detail['hash'])->count('id') > 0) {
                    DxTransaction::where('hash', $detail['hash'])->update($detail);
                } else {
                    DxTransaction::create($detail);
                }
                if (array_key_exists($header_key, $percents)) {
                    UtilityClass::notify_progress($percents[$header_key], $filename);
                }
                if (connection_aborted() == 1) {
                    DB::rollBack();
                    return ["a" => "Operation aborted from '" . $filename . "'!", "b" => "error"];
                }
            }
        } catch (QueryException $exception) {
            return json_encode(['a' => $exception->errorInfo[2], 'b' => 'error']);
        }
        return TRUE;
    }

    private function transaction_details($header, $details, $indices, $validbanks) {
        $header = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $header);
        $date_substr = preg_split("/\//", trim(substr($header, $indices['docdate']['beg'], $indices['docdate']['len'])));
        $detail['doc_date'] = $date_substr[2] . '-' . $date_substr[0] . '-' .$date_substr[1];
        $detail['doc_no'] = str_pad(trim(substr($header, $indices['docno']['beg'], $indices['docno']['len'])), 8, "0", STR_PAD_LEFT);
        $detail['amount'] = UtilityClass::parse_amount(trim(substr($header, $indices['credit']['beg'], $indices['credit']['len'])));
        $particulars = substr($header, $indices['acctno']['beg'], $indices['credit']['beg'] - $indices['acctno']['beg']);
        $detail['payee'] = utf8_encode(trim(preg_replace('/check:.+\-\s+\d{3,20}\s+\-\s+\d{2}\/\d{2}\/\d{4}/i', "", $particulars)));
        $desc = preg_replace('/\n\s{' . $indices['acctno']['beg'] . '}\d{2}\.\d{2}.*/', "", $details);
        $desc = preg_replace('/\n\s{' . $indices['particulars']['beg'] . '}.*/', "", $desc);
        $desc = preg_replace('/\n\s*/', " ", $desc);
        $detail['description'] = utf8_encode(trim($desc));
        if ($detail['doc_no'] == '1001099') {
            echo json_encode(["progress" => $particulars, "filename" => "err"]);
        }
        //old regex
        //check:.+\-\s+\d{3,20}\s+\-\s+\d{2}\/\d{2}\/\d{4}
        //backup regex0
        //check:\s\w*\s-\s(\d{1,20}\s|\s)-\s\d{2}\/\d{2}\/\d{4}
        if (preg_match_all('/check:.\w*\s-\s[\d\s].+\s\d{2}\/\d{2}\/\d{4}/i', $particulars, $matches) > 0) {
            $check = preg_replace('/check:/i', "", $matches[0][0]);
            if (count($cds = preg_split('/\s\-\s/', $check)) == 3) {
                $cut_zero_checkno= preg_replace('/^0/i', "", trim($cds[1]));
                $detail['check_bank'] = trim($cds[0]);
//                $detail['check_no'] = trim($cds[1]);
                $detail['check_no'] = $cut_zero_checkno;
                $date_substr = preg_split("/\//", trim($cds[2]));
                $detail['check_date'] = $date_substr[2] . '-' . $date_substr[0] . '-' .$date_substr[1];
                if (!preg_match("/(".$validbanks.")$/i", trim($cds[0]), $matches)) {
                    DB::rollBack();
                    session(['errorinfo' => 'Invalid bank '.$cds[0]]);
                    return 0;
                }

                $date_now = date('m/d/Y');
                $sliced_cur_date = explode('/', $date_now);
                $sliced_date = explode('-', $detail['check_date']);
                $yearnow = $sliced_cur_date[2];
                $year = $sliced_date[0];

                if ($year < 2005 OR $year > $yearnow) {
                    DB::rollBack();
                    session(['errorinfo' => 'Invalid check date '.$cds[2]]);
                    return 0;
                }
            }
        }
        return $detail;
    }

    private function indices($content) {
        $pattern = "/\s+doc date\s+doc no\.\s+account no\.\s+pcc\s+p a r t i c u l a r s\s+d e b i t\s+c r e d i t\r\n/i";
        if (preg_match_all($pattern, $content, $matches) < 1) {
            return FALSE;
        }
        $header = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $matches[0][0]);
        $fields = ["docdate" => "doc date", "docno" => "doc no\.",
            "acctno" => "account no\.", "pcc" => "pcc",
            "particulars" => "p a r t i c u l a r s",
            "debit" => "d e b i t", "credit" => "c r e d i t"];
        $offsets = UtilityClass::offsets($header, $fields);
        $idx['credit']['beg'] = $offsets['debit'][1] + strlen($offsets['debit'][0]) + 1;
        $idx['credit']['len'] = $offsets['credit'][1] + strlen($offsets['credit'][0]) + 1 - $idx['credit']['beg'];
        $idx['debit']['beg'] = $idx['credit']['beg'] - $idx['credit']['len'];
        $idx['debit']['len'] = $idx['credit']['len'];
        $idx['particulars']['beg'] = $offsets['particulars'][1];
        $idx['particulars']['len'] = $idx['debit']['beg'] - $idx['particulars']['beg'];
        $idx['pcc']['beg'] = $offsets['pcc'][1];
        $idx['pcc']['len'] = $idx['particulars']['beg'] - $idx['pcc']['beg'];
        $idx['acctno']['beg'] = $offsets['acctno'][1];
        $idx['acctno']['len'] = $idx['pcc']['beg'] - $idx['acctno']['beg'];
        $idx['docno']['beg'] = $offsets['docno'][1] - 1;
        $idx['docno']['len'] = $idx['acctno']['beg'] - $idx['docno']['beg'];
        $idx['docdate']['beg'] = $offsets['docdate'][1];
        $idx['docdate']['len'] = $idx['docno']['beg'] - $idx['docdate']['beg'];
        return $idx;
    }

    private function document_type($types, $content) {
        foreach ($types as $t) {
            if (preg_match_all('/consolidated ' . $t->name . '/i', $content, $matches) > 0) {
                return $t->code;
            }
        }
        return FALSE;
    }

}