<?php

namespace App\Http\Controllers\Admin;

use App\Bank;
use App\BranchCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BranchcodeController extends Controller
{
    //
    public function index() {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;

        $pagetitle = 'Branch Codes';
        $doctitle = $pagetitle;
        $title = 'Bank Reconciliation System - Admin - Branch Codes';

        $banks = Bank::all();

        $branchcodes = BranchCode::where('bank_name', 1)
            ->get();

        return view('admin.branchcode.index', compact('pagetitle', 'title', 'doctitle', 'login_user_firstname', 'banks', 'branchcodes'));

    }

    public function postAjax(Request $request) {


        $login_user = Auth::user();

        if ($request->p == 'view') {

            $branchcodes = BranchCode::where('bank_name', $request->bank)
                ->get();

            return view('admin.branchcode.tableajax', compact('branchcodes'));

        } else {

            $file = $request->file('bcodefile');

            $path = $file->getPathName();

            $alltext = '';

            if ($file = fopen($path, "r")) {
                while (!feof($file)) {
                    $line = fgets($file);
                    $text = utf8_encode($line);
                    $expl = explode(" ", $text);
                    $alltext .= $expl[0];
                    $code = $expl[0];
                    $branch = trim(str_replace($expl[0], "", $text));

                    $branchcode = new BranchCode();
                    $branchcode->bank_code = $code;
                    $branchcode->branch_name = $branch;
                    $branchcode->bank_name = $request->bank;
                    $branchcode->created_by = $login_user->user_id;
                    $branchcode->updated_by = $login_user->user_id;
                    $branchcode->save();

                    //BranchCode::updateOrCreate(['bank_code'=>'test code','branch_name'=>$branch,'bank_name'=>'BPI']);
                }
                fclose($file);
            }

        }

    }
}
