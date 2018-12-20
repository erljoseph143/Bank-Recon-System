<?php
/**
 * Created by PhpStorm.
 * User: AGC-PC
 * Date: 3/24/2018
 * Time: 9:15 AM
 */

namespace App\Functions;

use Illuminate\Support\Facades\File;

class FileClass
{
    public static function create_backup($request, $bank, $bu, $json_data) {

        $filename = $request->month.'-'.$request->year.'-'.$request->code.'-'.$bank->bank.'-'.$bank->accountno.'-'.$bank->accountname;
        $tablefile = 'back-up/'.$request->table;
        $path0 = $tablefile.'/'.$bu->company->company;
        $path1 = $path0.'/'.$bu->bname;
        $bankpath = $bank->bank.'-'.$bank->accountno.'-'.$bank->accountname;
        $path2 = $path1.'/'.$bankpath;

        if (!file_exists($tablefile)) {
            File::makeDirectory($tablefile);
        }

        if (!file_exists($path0)) {
            File::makeDirectory($path0);
        }

        if (!file_exists($path1)) {
            File::makeDirectory($path1);
        }

        if (!file_exists($path2)) {
            File::makeDirectory($path2);
        }

        File::put($path2.'/'.$filename.'.json',json_encode($json_data));

    }

    public static function create_backup2($request, $json_data) {

        $filename = $request->table;
        $tablefile = 'back-up/'.$request->table;

        if (!file_exists($tablefile)) {
            File::makeDirectory($tablefile);
        }

        File::put($tablefile.'/'.$filename.'.json',json_encode($json_data));

    }
}