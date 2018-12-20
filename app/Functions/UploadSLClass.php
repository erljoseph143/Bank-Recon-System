<?php
/**
 * Created by PhpStorm.
 * User: AGC-PC
 * Date: 6/9/2018
 * Time: 7:48 AM
 */

namespace App\Functions;


use App\Account;
use App\DxAccount;
use App\DxLedger;
use App\DxSubsidiaryLedger;
use Illuminate\Support\Facades\DB;

class UploadSLClass
{
    public function upload($content, $filename) {
        if (FALSE === $indices = $this->indices($content)) {
            DB::rollBack();
            return json_encode(['a' => 'Cannot find the file headers in \'' . $filename . '\'!', 'error']);
        }
        if (preg_match_all('/\n\s{' . $indices['docdate']['beg'] . '}account\:\s\d{2}\.\d{2}.*/i', $content, $accounts) < 1) {
            DB::rollBack();
            return json_encode(['a' => "Can't find the account code in '" . $filename . "'!", "error"]);
        }
        $result = DxAccount::get(['account_code', 'account_name']);
        $coa = array();
        foreach ($result as $r) {
            $coa[$r['account_code']] = $r['account_name'];
        }
        $result = DxLedger::all();
        $ldg = array();
        foreach ($result as $r) {
            $ldg[$r['ledger_code']] = $r['ledger_name'];
        }
        $account_code = preg_replace('/\n\s{' . $indices['docdate']['beg'] . '}account\:\s([^\s]+)\s.*/i', "$1", $accounts[0][0]);
        if (! array_key_exists($account_code, $coa)) {
            DB::rollBack();
            return response()->json(["a"=>"Account code '" . $account_code . "' in file '" . $filename . "' does not exist! Please upload the chart of accounts!", "b"=>"error"]);
        }
        $content = preg_replace('/(\n\s{' . $indices['docdate']['beg'] . '})(\d)/i', "$1 $2", $content);
        $content = preg_replace('/(\n\s{' . $indices['docdate']['beg'] . '})(\*)/i', "$1 $2", $content);
        $content = preg_replace('/\n\s{' . $indices['docdate']['beg'] . '}\S/i', "\n", $content);
        $content = preg_replace('/(\n\s{' . $indices['docdate']['beg'] . '})\s(\d)/i', "$1$2", $content);
        $content = preg_replace('/(\n\s{' . $indices['docdate']['beg'] . '})\s(\*)/i', "$1$2", $content);
        $content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content);
        $content = preg_replace("/\n\s{" . $indices['docdate']['beg'] . ',' . ($indices['docno']['beg']) . "}[a-z]+.*/i", "", $content);
        $content = preg_replace("/\n\s{" . $indices['docdate']['beg'] . "}\-+.*/i", "", $content);
        $content = rtrim($content);
        $pattern = '/\n\s{' . $indices['docdate']['beg'] . '}\*.*/i';
        if (preg_match_all($pattern, $content, $codes) < 1) {
            DB::rollBack();
            return response()->json(["Oooops!", "Can't find any subsidiary ledger from '" . $filename . "'!", "error"]);
        }
        $ledgers = preg_split($pattern, $content);
        $pattern = '/\n\s{' . $indices['docdate']['beg'] . '}\d{2}\/\d{2}\/\d{4}.*/';
        $start = 1;
        $count = 0;
        $starts = array();
        foreach ($codes[0] as $code_key => $code) {
            $starts[$code_key] = $start;
            $row_count = preg_match_all($pattern, $ledgers[$code_key + 1], $rows);
            $start += $row_count;
            $count += $row_count;
        }
        $percents = UtilityClass::percents($count);
        foreach ($codes[0] as $code_key => $code) {
            $ledger_code = preg_replace('/\n\s{' . $indices['docdate']['beg'] . '}\* \* \* s\/l code\: ([^\s]+)\s.*/i', "$1", $code);
            if (! array_key_exists($ledger_code, $ldg)) {
                DB::rollBack();
                return response()->json(["a"=>"Ledger code '" . $ledger_code . "' in file '" . $filename . "' does not exist! Please upload the sl code list!", "b"=>"error"]);
            }
            if (preg_match_all($pattern, $ledgers[$code_key + 1], $rows) > 0) {
                foreach ($rows[0] as $row_key => $row) {
                    $detail = $this->transaction_details($row, $indices);
                    $detail['account_code'] = $account_code;
                    $detail['ledger_code'] = $ledger_code;
                    $detail['hash'] = $detail['account_code'] . $detail['ledger_code'] . $detail['doc_type'] . substr($detail['doc_date'], 2, 2) . $detail['doc_no']
                        . "d" . $detail['debit'] . "c" . $detail['credit'];
                    $detail['buid'] = auth()->user()->bunitid;
                    $detail['created_by'] = auth()->user()->user_id;
                    $detail['updated_by'] = auth()->user()->user_id;
                    if (DxSubsidiaryLedger::where('hash', $detail['hash'])->count() > 0) {
                        DxSubsidiaryLedger::where('hash', $detail['hash'])->update($detail);
                    } else {
                        DxSubsidiaryLedger::create($detail);
                    }
                    if (array_key_exists($starts[$code_key] + $row_key, $percents)) {
                        UtilityClass::notify_progress($percents[$starts[$code_key] + $row_key], $filename);
                    }
                    if (connection_aborted() == 1) {
                        DB::rollBack();
                        die();
                    }
                }
            }
        }
        return TRUE;
    }

    private function transaction_details($row, $indices) {
        $row = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $row);
        $date_substr = explode("/", trim(substr($row, $indices['docdate']['beg'], $indices['docdate']['len'])));
        $detail['doc_date'] = $date_substr[2] . '-' . $date_substr[0] . '-' .$date_substr[1];
        $doc = trim(substr($row, $indices['docno']['beg'], $indices['docno']['len']));
        $detail['doc_type'] = trim(preg_replace('/(\S+)\s.*/i', '$1', $doc));
        $detail['doc_no'] = str_pad(trim(preg_replace('/\S+\s+(\S+)/i', '$1', $doc)), 8, "0", STR_PAD_LEFT);
        $detail['debit'] = UtilityClass::parse_amount(trim(substr($row, $indices['debit']['beg'], $indices['debit']['len'])));
        $detail['credit'] = UtilityClass::parse_amount(trim(substr($row, $indices['credit']['beg'], $indices['credit']['len'])));
        $detail['balance'] = UtilityClass::parse_amount(trim(substr($row, $indices['balance']['beg'], $indices['balance']['len'])));
        return $detail;
    }

    private function indices($content) {
        //Date       Type   Number P a r t i c u l a r s                                       D e b i t        C r e d i t      B a l a n c e
        $pattern = "/\s+date\s+type\s+number\s+p a r t i c u l a r s\s+d e b i t\s+c r e d i t\s+b a l a n c e\r\n/i";
        if (preg_match_all($pattern, $content, $matches) < 1) {
            return FALSE;
        }
        $header = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $matches[0][0]);
        $fields = array("docdate" => "date", "docno" => "type\s+number",
            "particulars" => "p a r t i c u l a r s",
            "debit" => "d e b i t", "credit" => "c r e d i t",
            "balance" => "b a l a n c e");
        $offsets = UtilityClass::offsets($header, $fields);
        $idx['balance']['beg'] = $offsets['credit'][1] + strlen($offsets['credit'][0]) + 1;
        $idx['balance']['len'] = $offsets['balance'][1] + strlen($offsets['balance'][0]) + 1 - $idx['balance']['beg'];
        $idx['credit']['beg'] = $offsets['debit'][1] + strlen($offsets['debit'][0]) + 1;
        $idx['credit']['len'] = $offsets['credit'][1] + strlen($offsets['credit'][0]) + 1 - $idx['credit']['beg'];
        $idx['debit']['beg'] = $idx['credit']['beg'] - $idx['credit']['len'];
        $idx['debit']['len'] = $idx['credit']['len'];
        $idx['particulars']['beg'] = $offsets['particulars'][1];
        $idx['particulars']['len'] = $idx['debit']['beg'] - $idx['particulars']['beg'];
        $idx['docno']['beg'] = $offsets['docno'][1];
        $idx['docno']['len'] = $idx['particulars']['beg'] - $idx['docno']['beg'];
        $idx['docdate']['beg'] = $offsets['docdate'][1];;
        $idx['docdate']['len'] = $idx['docno']['beg'] - $idx['docdate']['beg'];
        return $idx;
    }

}