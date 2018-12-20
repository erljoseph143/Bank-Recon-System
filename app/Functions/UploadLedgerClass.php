<?php
/**
 * Created by PhpStorm.
 * User: AGC-PC
 * Date: 6/9/2018
 * Time: 8:34 AM
 */

namespace App\Functions;


use App\DxLedger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UploadLedgerClass
{
    public function upload($content, $filename, $types)
    {
        $login_user = Auth::user();
        if (FALSE === $indices = $this->indices($content)) {
            DB::rollBack();
            return response()->json(["Oh No!", "Cannot find the file headers in '" . $filename . "'!", "error"]);
        }
        $content = preg_replace("/.*\r\n\s*subsidiary ledger codes masterlist.*\r\n.*/i", "", $content);
        $content = preg_replace("/\r\n\s*code\s+s\/l name.*/i", "", $content);
        $content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content);
        $pattern = '/\n\s{' . ($indices['code']['beg']) . '}\S.*(\n\s{' . ($indices['code']['beg']) . '}\s.*){0,3}/i';
        if (preg_match_all($pattern, $content, $matches) < 1) {
            DB::rollBack();
            return array("Oh No!", "Cannot find sl codes in '" . $filename . "'!", "error");
        }
        $percents = UtilityClass::percents(count($matches[0]) - 1);
        $pattern = "/\w+\n\s{" . ($indices['code']['beg']) . "}\s/";
        foreach ($matches[0] as $key => $m) {
            $row = (preg_match_all($pattern, $m, $matches) > 0) ? preg_replace($pattern, "", $m) : preg_replace('/\w+$/', "", $m);
            $row = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $row);
            $code = trim(substr($row, $indices['code']['beg'], $indices['code']['len']));
            $code = iconv("UTF-8", "UTF-8//IGNORE", $code);
            $name = trim(substr($row, $indices['name']['beg'], 750));
            $name = preg_replace('/\s{2,}/', " ", $name);
            $row = ['ledger_code' => utf8_encode($code), 'ledger_name' => utf8_encode($name), 'created_by' => $login_user->user_id, 'updated_by' => $login_user->user_id];

            if (DxLedger::where('ledger_code', $code)->count() == 0) {
                DxLedger::create($row);
            }
            else {
                DxLedger::where('ledger_code', $code)->update($row);
            }
            if (array_key_exists($key, $percents)) {
                UtilityClass::notify_progress($percents[$key], $filename);
            }
            if (connection_aborted() == 1) {
                DB::rollBack();
                die();
            }
        }
        return TRUE;
    }

    private function indices($content) {
        $pattern = "/\s+code\s+s\/l name.*\r\n/i";
        if (preg_match_all($pattern, $content, $matches) < 1) {
            return FALSE;
        }
        $header = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $matches[0][0]);
        $fields = array("code" => "code", "name" => "s\/l name",
            "category" => "\w+$");
        $offsets = UtilityClass::offsets($header, $fields);
        $idx['name']['beg'] = $offsets['name'][1];
        $idx['name']['len'] = $offsets['category'][1] - $idx['name']['beg'];
        $idx['code']['beg'] = $offsets['code'][1];
        $idx['code']['len'] = $idx['name']['beg'] - $idx['code']['beg'];
        return $idx;
    }

}