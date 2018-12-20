<?php

namespace App\Http\Controllers\Admin;

use App\BankAccount;
use App\BankNo;
use App\BankStatement;
use App\Businessunit;
use App\Checkingaccounts;
use App\Deposit;
use App\Dtrbankstatement;
use App\Functions\FileClass;
use App\LogbookCheck;
use App\PdcLine;
use App\User;
use function Couchbase\defaultDecoder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Filesystem;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    //
    public function downloaddb() {

        $login_user = Auth::user();

        $login_user_firstname = $login_user->firstname;
        $pagetitle = "Back Up";
        $doctitle = "tables";

        $title = "Bank Reconciliation System - Admin - Backup";

        $tables2 = DB::select('SHOW TABLES');

        $newtables = [];

        foreach ($tables2 as $table) {

            $newtables[] = $table->Tables_in_brs;

        }

        $bskey = array_search('bank_statement', $newtables);
        unset($newtables[$bskey]);

        $chkey = array_search('checking_account', $newtables);
        unset($newtables[$chkey]);

        $dpkey = array_search('deposit', $newtables);
        unset($newtables[$dpkey]);

        $pdkey = array_search('pdc_line', $newtables);
        unset($newtables[$pdkey]);

        $dtrkey = array_search('dtrbankstatement', $newtables);
        unset($newtables[$dtrkey]);

        $logkey = array_search('logbook_checks', $newtables);
        unset($newtables[$logkey]);



        $tables = [
            'bank_statement'    => 'BankStatement',
            'checking_account'  => 'Checkingaccounts',
            'deposit'           => 'Deposit',
//            'dtrbankstatement'  => 'Dtrbankstatement',
//            'logbook_checks'    => 'LogbookCheck',
            'pdc_line'          => 'PdcLine',
        ];

        $bus = Businessunit::select('unitid', 'bname')
            ->get();

        $accounts = BankAccount::select('id', 'bank', 'accountno', 'accountname', 'buid')
            ->get();

        return view('admin.backup.downloaddb', compact('title', 'login_user_firstname', 'pagetitle', 'doctitle', 'tables', 'bus', 'accounts', 'newtables'));

    }

    public function tableBU(Request $request) {

        $table = $request->table;
        return $table;
        //$querytable =


    }

    public function uploaddb() {

        return view('admin.backup.uploaddb');

    }

    public function backupBULists(Request $request) {

        $table = $request->data;

        //$months = ;
        switch ($table) {
            case 'bank_statement':
                $bu_ids = BankStatement::select('bu_unit')
                    ->groupBy('bu_unit')
                    ->get();

                $ids = [];

                foreach ($bu_ids as $id) {

                    $ids[] = $id->bu_unit;

                }

                $bus = Businessunit::select('unitid', 'bname')
                    ->whereIn('unitid', $ids)
                    ->get();

                $codes = BankStatement::select('bank_account_no')
                    ->groupBy('bank_account_no')
                    ->get()
                    ->toArray();

                $bulist = view('admin.backup.bulist', compact('bus'))->render();

                $codelist = "";

                foreach ($codes as $code) {

                    $codelist .= '<option value="'.$code['bank_account_no'].'">'.$code['bank_account_no'].'</option>';

                }

                return response()->json(['bulist' => $bulist, 'codelist' => $codelist]);

                break;
            case 'checking_account':
                $bu_ids = Checkingaccounts::select('bu')
                    ->groupBy('bu')
                    ->get();

                $ids = [];

                foreach ($bu_ids as $id) {

                    $ids[] = $id->bu;

                }

                $bus = Businessunit::select('unitid', 'bname')
                    ->whereIn('unitid', $ids)
                    ->get();

                $codes = Checkingaccounts::select('nav_setup_no')
                    ->groupBy('nav_setup_no')
                    ->get()
                    ->toArray();

                $bulist = view('admin.backup.bulist', compact('bus'))->render();

                $codelist = "";

                foreach ($codes as $code) {

                    $codelist .= '<option value="'.$code['nav_setup_no'].'">'.$code['nav_setup_no'].'</option>';

                }

                return response()->json(['bulist' => $bulist, 'codelist' => $codelist]);
                break;
            case 'deposit':
                $bu_ids = Deposit::select('bu_unit')
                    ->groupBy('bu_unit')
                    ->get();

                $ids = [];

                foreach ($bu_ids as $id) {

                    $ids[] = $id->bu_unit;

                }

                $bus = Businessunit::select('unitid', 'bname')
                    ->whereIn('unitid', $ids)
                    ->get();

                $codes = Deposit::select('bank_account_no')
                    ->groupBy('bank_account_no')
                    ->get()
                    ->toArray();

                $bulist = view('admin.backup.bulist', compact('bus'))->render();

                $codelist = "";

                foreach ($codes as $code) {

                    $codelist .= '<option value="'.$code['bank_account_no'].'">'.$code['bank_account_no'].'</option>';

                }

                return response()->json(['bulist' => $bulist, 'codelist' => $codelist]);

                break;
            case 'dtrbankstatement':
                $bu_ids = Dtrbankstatement::select('bu')
                    ->groupBy('bu')
                    ->get();

                $ids = [];

                foreach ($bu_ids as $id) {

                    $ids[] = $id->bu;

                }

                $bus = Businessunit::select('unitid', 'bname')
                    ->whereIn('unitid', $ids)
                    ->get();

                $bulist = view('admin.backup.bulist', compact('bus'))->render();

                $codelist = "";

                return response()->json(['bulist' => $bulist, 'codelist' => $codelist]);
                break;
            case 'logbook_checks':
                $bu_ids = LogbookCheck::select('bu')
                    ->groupBy('bu')
                    ->get();

                $ids = [];

                foreach ($bu_ids as $id) {

                    $ids[] = $id->bu;

                }

                $bus = Businessunit::select('unitid', 'bname')
                    ->whereIn('unitid', $ids)
                    ->get();

                $bulist = view('admin.backup.bulist', compact('bus'))->render();

                $codelist = "";

                return response()->json(['bulist' => $bulist, 'codelist' => $codelist]);
                break;
            case 'pdc_line':
                $bu_ids = PdcLine::select('bu_unit')
                    ->groupBy('bu_unit')
                    ->get();

                $ids = [];

                foreach ($bu_ids as $id) {

                    $ids[] = $id->bu_unit;

                }

                $bus = Businessunit::select('unitid', 'bname')
                    ->whereIn('unitid', $ids)
                    ->get();

                $codes = PdcLine::select('baccount_no')
                    ->groupBy('baccount_no')
                    ->get()
                    ->toArray();

                $bulist = view('admin.backup.bulist', compact('bus'))->render();

                $codelist = "";

                foreach ($codes as $code) {

                    $codelist .= '<option value="'.$code['baccount_no'].'">'.$code['baccount_no'].'</option>';

                }

                return response()->json(['bulist' => $bulist, 'codelist' => $codelist]);

                break;
            default:
                return '<option value="-1">Select BU</option>';
                break;

        }

        return $table;

    }

    public function backupMonthLists(Request $request) {

        $table = $request->table;


        if ($table == 'BankStatement') {
            $months = BankStatement::select('bank_id', 'bank_date')
                ->where('bu_unit', $request->bu)
                ->where('bank_account_no', $request->acc)
                ->groupBy(DB::raw('DATE_FORMAT(bank_date, "%M %Y")'))
                ->get();


            $code = BankNo::select('id')
                ->where('bankno', $request->acc)
                ->first();
//
            $bank = BankAccount::select('id', 'bank', 'accountno', 'accountname')
                ->where('bankno', $code->id)
                ->where('buid', $request->bu)
                ->first();

            $table = view('admin.backup.monthintable', compact('months'))->render();

            return response()->json(['table' => $table, 'bank' => $bank]);

        } elseif ($table == 'Checkingaccounts') {

            $months = Checkingaccounts::select('id', 'date_posted')
                ->where('bu', $request->bu)
                ->where('nav_setup_no', $request->acc)
                ->groupBy(DB::raw('DATE_FORMAT(date_posted, "%M %Y")'))
                ->get();

            $code = BankNo::select('id')
                ->where('bankno', $request->acc)
                ->first();
//
            $bank = BankAccount::select('id', 'bank', 'accountno', 'accountname')
                ->where('bankno', $code->id)
                ->where('buid', $request->bu)
                ->first();

            $html = '';

            foreach ($months as $month) {
                $html .= '<tr><td>'.$month->date_posted->format('M Y').'</td>
                    <td>
                        <a data-url="'.url('admin/backup/extract-json').'" href="#download" class="on-default open-modal download-json" title="download" data-month="'.$month->date_posted->format('m').'" data-year="'.$month->date_posted->format('Y').'"><i class="fa fa-cloud-download"></i></a>
                        <label for="uploaddb" data-url="" href="#upload" class="on-default open-modal" title="upload" data-id=""><i class="fa fa-cloud-upload"></i></label>
                        <input id="uploaddb" type="file">
                    </td>
                </tr>';
            }

            return response()->json(['table' => $html, 'bank' => $bank]);

        } elseif ($table == 'Deposit') {

            $months = Deposit::select('id', 'posting_date')
                ->where('bu_unit', $request->bu)
                ->where('bank_account_no', $request->acc)
                ->groupBy(DB::raw('DATE_FORMAT(posting_date, "%M %Y")'))
                ->get();

            $code = BankNo::select('id')
                ->where('bankno', $request->acc)
                ->first();
//
            $bank = BankAccount::select('id', 'bank', 'accountno', 'accountname')
                ->where('bankno', $code->id)
                ->where('buid', $request->bu)
                ->first();

            $html = '';

            foreach ($months as $month) {
                $html .= '<tr><td>'.$month->posting_date->format('M Y').'</td>
                    <td>
                        <a data-url="'.url('admin/backup/extract-json').'" href="#download" class="on-default open-modal download-json" title="download" data-month="'.$month->posting_date->format('m').'" data-year="'.$month->posting_date->format('Y').'"><i class="fa fa-cloud-download"></i></a>
                        <label for="uploaddb" data-url="" href="#upload" class="on-default open-modal" title="upload" data-id=""><i class="fa fa-cloud-upload"></i></label>
                        <input id="uploaddb" type="file">
                    </td>
                </tr>';
            }

            return response()->json(['table' => $html, 'bank' => $bank]);

        } elseif ($table == 'PdcLine') {

            $months = PdcLine::select('id', 'cv_date')
                ->where('bu_unit', $request->bu)
                ->where('baccount_no', $request->acc)
                ->groupBy(DB::raw('DATE_FORMAT(cv_date, "%M %Y")'))
                ->get();

            $code = BankNo::select('id')
                ->where('bankno', $request->acc)
                ->first();
//
            $bank = BankAccount::select('id', 'bank', 'accountno', 'accountname')
                ->where('bankno', $code->id)
                ->where('buid', $request->bu)
                ->first();

            $html = '';

            foreach ($months as $month) {
                $html .= '<tr><td>'.$month->cv_date->format('M Y').'</td>
                    <td>
                        <a data-url="'.url('admin/backup/extract-json').'" href="#download" class="on-default open-modal download-json" title="download" data-month="'.$month->cv_date->format('m').'" data-year="'.$month->cv_date->format('Y').'"><i class="fa fa-cloud-download"></i></a>
                        <label for="uploaddb" data-url="" href="#upload" class="on-default open-modal" title="upload" data-id=""><i class="fa fa-cloud-upload"></i></label>
                        <input id="uploaddb" type="file">
                    </td>
                </tr>';
            }

            return response()->json(['table' => $html, 'bank' => $bank]);

        } else {
            return response()->json(['table' => '']);
        }

    }

    public function backupCodeLists(Request $request) {

        $table = $request->table;

        if ($table == 'BankStatement') {

            $months = BankStatement::select('bank_account_no')
                ->where('bu_unit', $request->bu)
                ->groupBy('bank_account_no')
                ->get();

            $codelists = "";

            foreach ($months as $month) {

                $codelists .= '<option value="'.$month->bank_account_no.'">'.$month->bank_account_no.'</option>';

            }

            return response()->json(['codes' => $codelists]);

        } elseif ($table == 'Checkingaccounts') {

            $months = Checkingaccounts::select('nav_setup_no')
                ->where('bu', $request->bu)
                ->groupBy('nav_setup_no')
                ->get();

            $codelists = "";

            foreach ($months as $month) {

                $codelists .= '<option value="'.$month->nav_setup_no.'">'.$month->nav_setup_no.'</option>';

            }

            return response()->json(['codes' => $codelists]);

        } elseif ($table == 'Deposit') {

            $months = Deposit::select('bank_account_no')
                ->where('bu_unit', $request->bu)
                ->groupBy('bank_account_no')
                ->get();

            $codelists = "";

            foreach ($months as $month) {

                $codelists .= '<option value="'.$month->bank_account_no.'">'.$month->bank_account_no.'</option>';

            }

            return response()->json(['codes' => $codelists]);

        } elseif ($table == 'PdcLine') {
            return response()->json(['table' => '']);
        } else {
            return response()->json(['table' => '']);
        }

        return response()->json(['html' => $request->table]);

    }

    public function extractJSON(Request $request) {

        $table = $request->table;

        if ($table == 'BankStatement') {

            $banks = BankStatement::where('bank_account_no', $request->code)
                ->where('bu_unit', $request->bu)
                ->where(DB::raw('MONTH(bank_date)'), $request->month)
                ->where(DB::raw('YEAR(bank_date)'), $request->year)
                ->get()
                ->toJson();

            $bu = Businessunit::where('unitid', $request->bu)->first();

            $code = BankNo::select('id')
                ->where('bankno', $request->code)
                ->first();

            $bank = BankAccount::select('id', 'bank', 'accountno', 'accountname')
                ->where('bankno', $code->id)
                ->where('buid', $request->bu)
                ->first();

            //dd($bank);

            $json_data = [
                'company'       => $bu->company->company,
                'bu'            => $bu->bname,
                'table'         => $table,
                'month'         => $request->month,
                'year'          => $request->year,
                'bankaccount'   => $bank->id,
                'items'         => $banks
            ];

            FileClass::create_backup($request, $bank, $bu, $json_data);

            return response()->json(['codes' => $banks]);

        } elseif ($table == 'Checkingaccounts') {

            $checks = Checkingaccounts::where('nav_setup_no', $request->code)
                ->where('bu', $request->bu)
                ->where(DB::raw('MONTH(date_posted)'), $request->month)
                ->where(DB::raw('YEAR(date_posted)'), $request->year)
                ->get()
                ->toJson();


            $bu = Businessunit::where('unitid', $request->bu)->first();

            $code = BankNo::select('id')
                ->where('bankno', $request->code)
                ->first();

            $bank = BankAccount::select('id', 'bank', 'accountno', 'accountname')
                ->where('bankno', $code->id)
                ->where('buid', $request->bu)
                ->first();

            $json_data = [
                'company'       => $bu->company->company,
                'bu'            => $bu->bname,
                'table'         => $table,
                'month'         => $request->month,
                'year'          => $request->year,
                'bankaccount'   => $bank->id,
                'items'         => $checks
            ];

            FileClass::create_backup($request, $bank, $bu, $json_data);

            return response()->json(['codes' => $checks]);

        } elseif ($table == 'Deposit') {

            $deposits = Deposit::where('bank_account_no', $request->code)
                ->where('bu_unit', $request->bu)
                ->where(DB::raw('MONTH(posting_date)'), $request->month)
                ->where(DB::raw('YEAR(posting_date)'), $request->year)
                ->get()
                ->toJson();

            $bu = Businessunit::where('unitid', $request->bu)->first();

            $code = BankNo::select('id')
                ->where('bankno', $request->code)
                ->first();

            $bank = BankAccount::select('id', 'bank', 'accountno', 'accountname')
                ->where('bankno', $code->id)
                ->where('buid', $request->bu)
                ->first();

            $json_data = [
                'company'       => $bu->company->company,
                'bu'            => $bu->bname,
                'table'         => $table,
                'month'         => $request->month,
                'year'          => $request->year,
                'bankaccount'   => $bank->id,
                'items'         => $deposits
            ];

            FileClass::create_backup($request, $bank, $bu, $json_data);

            return response()->json(['codes' => $deposits]);

        } elseif ($table == 'PdcLine') {

            $pdcline = PdcLine::where('baccount_no', $request->code)
                ->where('bu_unit', $request->bu)
                ->where(DB::raw('MONTH(cv_date)'), $request->month)
                ->where(DB::raw('YEAR(cv_date)'), $request->year)
                ->get()
                ->toJson();

            $bu = Businessunit::where('unitid', $request->bu)->first();

            $code = BankNo::select('id')
                ->where('bankno', $request->code)
                ->first();

            $bank = BankAccount::select('id', 'bank', 'accountno', 'accountname')
                ->where('bankno', $code->id)
                ->where('buid', $request->bu)
                ->first();

            $json_data = [
                'company'       => $bu->company->company,
                'bu'            => $bu->bname,
                'table'         => $table,
                'month'         => $request->month,
                'year'          => $request->year,
                'bankaccount'   => $bank->id,
                'items'         => $pdcline
            ];

            FileClass::create_backup($request, $bank, $bu, $json_data);

            return response()->json(['codes' => $pdcline]);
        } else {
            return response()->json(['codes' => '']);
        }

    }

    public function downloadJSONsTable(Request $request) {

//        if ( $request->table == 'bankaccount' ) {
//
//        } elseif ( $request->table == 'bankno' ) {
//
//        } elseif ( $request->table == 'businessunit' ) {
//
//        } elseif ( $request->table == 'company' ) {
//
//        } elseif ( $request->table == 'money' ) {
//
//        }

        $dbquery = DB::table($request->table)
            ->get();

        $json_data = [
            'table'         => $request->table,
            'items'         => $dbquery
        ];

        FileClass::create_backup2($request, $json_data);



        return response()->json(['data' => $dbquery]);

    }

}
