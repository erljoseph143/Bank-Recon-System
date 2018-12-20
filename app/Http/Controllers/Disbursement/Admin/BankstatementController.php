<?php

namespace App\Http\Controllers\Admin;

use App\BankAccount;
use App\BankNo;
use App\BankStatement;
use App\Businessunit;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BankstatementController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //
//    public function allBankStatement() {
//
//        $bankstatements = DB::table('bank_statement')->select('bank_id')->get();
//
//        $bankaccounts = DB::table('bankaccount')->select('id','bank','accountno','accountname')->get();
//        dd($bankaccounts);
//
//        return view('admin.bankstatement.index');
//
//    }

    public function bank_r() {
//        $statements = BankStatement::select('bankno.id', 'bankno.bankno', 'company', 'bu_unit', 'uploaded_by')
//            ->join('bankno', 'bank_statement.bank_account_no', '=', 'bankno.bankno')
//            ->groupBy('bank_account_no')
//            ->get();
        $bus = BankStatement::select('businessunit.unitid', 'businessunit.bname')
            ->join('users', 'bank_statement.uploaded_by', '=', 'users.user_id')
            ->join('businessunit', 'bank_statement.bu_unit', '=', 'businessunit.unitid')
            ->groupBy('businessunit.unitid')
            ->get();

        $users_no = [];

        foreach ($bus as $bu) {
            $users = BankStatement::select(DB::raw('COUNT(DISTINCT(uploaded_by)) as count'))
                ->where('bu_unit', $bu->unitid)
                ->first();
            $users_no[] = $users->count;
        }



//        foreach ($statements as $key => $statement) {
//
//            $account = BankAccount::select('bankaccount.id', 'bankaccount.bank', 'bankaccount.accountno', 'bankaccount.accountname', 'bankaccount.company_code', 'bankaccount.buid', 'bankno.bankno')
//                ->join('bankno', 'bankaccount.bankno', '=', 'bankno.id')
//                ->where('bankaccount.bankno', $statement->id)
//                ->where('company_code', $statement->company)
//                ->where('buid', $statement->bu_unit)
//                ->first();
//
//            $accounts[$key][] = $account;
//            $accounts[$key]['user'] = $statement->uploaded_by;
//
//        }
        $mode = ' : Business units';

        $pagetitle = "Bank Statements";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "Business units contained bank statements";

        return view('admin.bankstatement.index', compact('pagetitle', 'bus', 'users_no', 'title', 'mode', 'doctitle', 'mode'));
    }

    public function listUsers($id) {

        $users = User::select('users.firstname', 'users.lastname', 'user_type.user_type_name', 'users.user_id')
            ->join('bank_statement', 'users.user_id', '=', 'bank_statement.uploaded_by')
            ->join('user_type', 'users.privilege', '=', 'user_type.user_type_id')
            ->where('bank_statement.bu_unit', $id)
            ->groupBy('users.firstname')
            ->get();

        $bu = Businessunit::findOrFail($id);

        $mode = ' : Users uploaded';

        $pagetitle = "Bank Statements";
        $crumbtitle = "Users uploaded";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "users who uploaded a bank statements";

        return view('admin.bankstatement.userlist', compact('pagetitle', 'users', 'id', 'title', 'mode', 'doctitle', 'crumbtitle', 'bu'));

    }

    public function listAccounts($id, $userid) {

        $accounts = BankStatement::select('bank_account_no')
            ->where('uploaded_by', $userid)
            ->where('bu_unit', $id)
            ->groupBy('bank_account_no')
            ->get();

        $final_banks = [];

        foreach ($accounts as $account) {

            $bankcode = BankNo::select('id')
            ->where('bankno', $account->bank_account_no)
            ->first();

            $banks = BankAccount::select('id', 'bank', 'accountno', 'accountname')
                ->where('bankno', $bankcode->id)
                ->where('buid', $id)
                ->first();

            $final_banks[] = $banks;

        }

        $bu = Businessunit::findOrFail($id);
        $user = User::findOrFail($userid);

        $mode = ' : Bank Accounts';
        $crumbtitle = "Bank Accounts";

        $pagetitle = "Bank Statements";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "bank accounts with bank statements";

        return view('admin.bankstatement.accountlist', compact('pagetitle', 'final_banks', 'id', 'userid', 'title', 'bu', 'user','doctitle', 'accounts', 'crumbtitle', 'mode'));

    }

    public function monthlistBankStatements($id, $userid, $account, $code) {

        $pagetitle = "Bank Statement month list";

        $title = "Bank Reconciliation System - ".$pagetitle;

        $doctitle = "bank statements months";

        $months = BankStatement::select('bank_date', 'uploaded_by', 'date_added')
            ->where('bank_account_no', $code)
            ->where('uploaded_by', $userid)
            ->where('bu_unit', $id)
            ->groupBy(DB::raw('DATE_FORMAT(bank_date, "%M %Y")'))
            ->get();

        $bu = Businessunit::findOrFail($id);
        $user = User::findOrFail($userid);
        $accountname = BankAccount::findOrFail($account);

        return view('admin.bankstatement.month', compact('pagetitle', 'months', 'title', 'doctitle', 'account', 'id', 'company', 'bu', 'user', 'code', 'userid', 'accountname'));

    }

    public function listBankStatements($id, $userid, $account, $code, $year, $month) {

        $views = BankStatement::select('bank_id', 'bank_date', 'description', 'bank_check_no', 'bank_amount', 'bank_balance', 'date_modified', 'modified_by')
            ->where('bank_account_no', $code)
            ->where('uploaded_by', $userid)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->where(DB::raw('YEAR(bank_date)'), $year)
            ->where(DB::raw('MONTH(bank_date)'), $month)
            ->paginate(10);

        $uploader = User::findOrFail($userid);

        $accountdata = BankAccount::findOrFail($account);

        $newdate = date('F Y', strtotime($year.'-'.$month.'-01'));

        $countall = BankStatement::select('bank_id', 'bank_date', 'description', 'bank_check_no', 'bank_amount', 'bank_balance', 'date_modified', 'modified_by')
            ->where('bank_account_no', $code)
            ->where('uploaded_by', $userid)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->where(DB::raw('YEAR(bank_date)'), $year)
            ->where(DB::raw('MONTH(bank_date)'), $month)
            ->limit(8)
            ->count();

        $counttrash = BankStatement::select('bank_id', 'bank_date', 'description', 'bank_check_no', 'bank_amount', 'bank_balance', 'date_modified', 'modified_by')
            ->where('bank_account_no', $code)
            ->where('uploaded_by', $userid)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->where(DB::raw('YEAR(bank_date)'), $year)
            ->where(DB::raw('MONTH(bank_date)'), $month)
            ->onlyTrashed()
            ->count();

        $bu = Businessunit::findOrFail($id);

        $title = "Bank Reconciliation System - Lists of bank statements in a month";
        $pagetitle = "Bank Statements lists";
        $doctitle = "Bank Statements in the month of";
        $template = "all";

        return view('admin.bankstatement.viewmonth', compact('pagetitle', 'title', 'months', 'views', 'id', 'doctitle', 'newdate', 'company', 'bu', 'userid', 'code', 'year', 'month', 'account', 'uploader', 'template', 'countall', 'counttrash', 'accountdata'));

    }

    public function listBankStatementsTrash($id, $userid, $account, $code, $year, $month) {

        $views = BankStatement::onlyTrashed()
            ->where('bank_account_no', $code)
            ->where('uploaded_by', $userid)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->where(DB::raw('YEAR(bank_date)'), $year)
            ->where(DB::raw('MONTH(bank_date)'), $month)
            ->paginate(10);

        $uploader = User::findOrFail($userid);

        $accountdata = BankAccount::findOrFail($account);

        $newdate = date('F Y', strtotime($year.'-'.$month.'-01'));

        $countall = BankStatement::select('bank_id', 'bank_date', 'description', 'bank_check_no', 'bank_amount', 'bank_balance', 'date_modified', 'modified_by')
            ->where('bank_account_no', $code)
            ->where('uploaded_by', $userid)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->where(DB::raw('YEAR(bank_date)'), $year)
            ->where(DB::raw('MONTH(bank_date)'), $month)
            ->limit(8)
            ->count();

        $counttrash = BankStatement::select('bank_id', 'bank_date', 'description', 'bank_check_no', 'bank_amount', 'bank_balance', 'date_modified', 'modified_by')
            ->where('bank_account_no', $code)
            ->where('uploaded_by', $userid)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->where(DB::raw('YEAR(bank_date)'), $year)
            ->where(DB::raw('MONTH(bank_date)'), $month)
            ->onlyTrashed()
            ->count();

        $bu = Businessunit::findOrFail($id);

        $title = "Bank Reconciliation System - Lists of bank statements in a month";
        $pagetitle = "Bank Statements lists";
        $doctitle = "Bank Statements in the month of";
        $template = "trash";

        return view('admin.bankstatement.viewmonth', compact('pagetitle', 'title', 'months', 'views', 'id', 'doctitle', 'newdate', 'company', 'bu', 'userid', 'code', 'year', 'month', 'account', 'uploader', 'template', 'countall', 'counttrash', 'accountdata'));

    }

    public function getBStatement($id) {

        $data = json_decode($id);

        if ($data[1] == 'restore') {

            $bs = BankStatement::withTrashed()
                ->where('bank_id', $data[0])
                ->restore();

            return response($bs);
        }

        $bankstatement = BankStatement::find($data[0]);

        $date = $bankstatement->bank_date->format('m/d/Y');

        $response = [
            'bank_id'       => $bankstatement->bank_id,
            'bank_date'     => $date,
            'description'   => $bankstatement->description,
            'bank_check_no' => $bankstatement->bank_check_no,
            'bank_amount'   => $bankstatement->bank_amount,
            'bank_balance'  => $bankstatement->bank_balance,
        ];

        return response($response);

    }

    public function updateBS(Request $request, $id) {

        $date = explode('/', $request->bankdate);

        list($month, $day, $year) = $date;

        $newdate = $year . '-' . $month.'-'.$day;

        try {
            $bs = BankStatement::find($id);
            $bs->bank_date = $newdate;
            $bs->description = $request->desc;
            $bs->bank_check_no = $request->checkno;
            $bs->bank_amount = $request->bankamount;
            $bs->bank_balance = $request->balance;
            $bs->save();

            $stringdate = $bs->bank_date->format('F d, Y');

            $response = [
                'bankdate'      => $stringdate,
                'desc'          => $bs->description,
                'checkno'       => $bs->bank_check_no,
                'bankamount'    => $bs->bank_amount,
                'balance'       => $bs->bank_balance,
            ];

            return response($response);

        }catch (Exception $e) {
            dd($e);
        }
    }

    public function trashBS($id) {

        try {
            $data = json_decode($id);

            if ($data[1] == 'delete') {
                $code = User::withTrashed()
                    ->where('user_id', $data[0])
                    ->forceDelete();
//
                return $code;
            }
//
            $bs = BankStatement::findOrFail($data[0]);
            $bs->delete();

            return response($data);

        }catch (Exception $e) {
            dd($e);
        }

    }

}
