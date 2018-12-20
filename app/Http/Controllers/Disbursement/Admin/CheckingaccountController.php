<?php

namespace App\Http\Controllers\Admin;

use App\BankAccount;
use App\BankNo;
use App\Businessunit;
use App\Checkingaccounts;
use App\Functions\Checking;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CheckingaccountController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function bank_r() {
        $checks = Checkingaccounts::select('businessunit.unitid', 'businessunit.bname')
            ->join('users', 'checking_account.uploaded_by', '=', 'users.user_id')
            ->join('businessunit', 'checking_account.bu', '=', 'businessunit.unitid')
            ->groupBy('businessunit.unitid')
            ->get();

        $users_no = [];

        foreach ($checks as $check) {
            $users = Checkingaccounts::select(DB::raw('COUNT(DISTINCT(uploaded_by)) as count'))
                ->where('bu', $check->unitid)
                ->first();
            $users_no[] = $users->count;
        }

        $mode = ' : Business units';

        $pagetitle = "Checking Accounts";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "Business units contained checking accounts";

        return view('admin.checkingaccounts.index', compact('pagetitle', 'checks', 'users_no', 'title', 'mode', 'doctitle', 'mode'));
//        $checks = Checkingaccounts::select('bankaccount_id')
//            ->groupBy('bankaccount_id')
//            //->having('id', '>', 0)
//            ->get();
//        $account_number = [];
//
//        foreach ($checks as $key => $check) {
//
//            if (!empty($check)) {
//                $account_number[] = $check->bankaccount_id;
//            } else {
//
//            }
//
//        }
//
//        if (!empty($account_number)) {
//            $accounts = BankAccount::whereIn('id', $account_number)
//                ->get();
//        } else {
//
//        }
//
//        $template = '';
//
//        $title = "Bank Reconciliation System - Checking Accounts";
//        $pagetitle = "Checking Accounts";
//        $doctitle = "Bank accounts use by checking accounts";
//
//        return view('admin.checkingaccounts.index', compact('pagetitle', 'accounts', 'title', 'template', 'doctitle'));
    }

    public function listUsers($id) {

        $users = User::select('users.firstname', 'users.lastname', 'user_type.user_type_name', 'users.user_id')
            ->join('checking_account', 'users.user_id', '=', 'checking_account.uploaded_by')
            ->join('user_type', 'users.privilege', '=', 'user_type.user_type_id')
            ->where('checking_account.bu', $id)
            ->groupBy('users.firstname')
            ->get();

        $bu = Businessunit::findOrFail($id);

        $mode = ' : Users uploaded';

        $pagetitle = "Checking Accounts";
        $crumbtitle = "Users uploaded";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "users who uploaded a bank statements";

        return view('admin.checkingaccounts.userlist', compact('pagetitle', 'users', 'id', 'title', 'mode', 'doctitle', 'crumbtitle', 'bu'));

    }

    public function listAccounts($id, $userid) {

        $checks = Checkingaccounts::select('nav_setup_no')
            ->where('uploaded_by', $userid)
            ->where('bu', $id)
            ->groupBy('nav_setup_no')
            ->get();

        $final_banks = [];

        foreach ($checks as $check) {

            $bankcode = BankNo::select('id')
                ->where('bankno', $check->nav_setup_no)
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

        $pagetitle = "Checking Accounts";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "bank accounts with checking accounts";

        return view('admin.checkingaccounts.accountlist', compact('pagetitle', 'final_banks', 'id', 'userid', 'title', 'bu', 'user','doctitle', 'checks', 'crumbtitle', 'mode'));

    }

    public function monthlistChecks($id, $userid, $account, $code) {

        $pagetitle = "Checking accounts month list";

        $title = "Bank Reconciliation System - ".$pagetitle;

        $doctitle = "checking accounts months";

        $months = Checkingaccounts::select('date_posted', 'uploaded_by', 'date_uploaded')
            ->where('nav_setup_no', $code)
            ->where('uploaded_by', $userid)
            ->where('bu', $id)
            ->groupBy(DB::raw('DATE_FORMAT(date_posted, "%M %Y")'))
            ->get();

        $bu = Businessunit::findOrFail($id);
        $user = User::findOrFail($userid);
        $accountname = BankAccount::findOrFail($account);

        return view('admin.checkingaccounts.month', compact('pagetitle', 'months', 'title', 'doctitle', 'account', 'id', 'company', 'bu', 'user', 'code', 'userid', 'accountname'));

    }

    public function listChecks($id, $userid, $account, $code, $year, $month) {

        $views = Checkingaccounts::select('id', 'date_posted', 'transaction_desc', 'check_no', 'trans_amount', 'balance', 'date_modified', 'modified_by')
            ->where('nav_setup_no', $code)
            ->where('uploaded_by', $userid)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->where(DB::raw('YEAR(date_posted)'), $year)
            ->where(DB::raw('MONTH(date_posted)'), $month)
            ->paginate(10);

        $uploader = User::findOrFail($userid);

        $accountdata = BankAccount::findOrFail($account);

        $newdate = date('F Y', strtotime($year.'-'.$month.'-01'));

        $countall = Checkingaccounts::select('*')
            ->where('nav_setup_no', $code)
            ->where('uploaded_by', $userid)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->where(DB::raw('YEAR(date_posted)'), $year)
            ->where(DB::raw('MONTH(date_posted)'), $month)
            ->limit(8)
            ->count();

        $counttrash = Checkingaccounts::select('*')
            ->where('nav_setup_no', $code)
            ->where('uploaded_by', $userid)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->where(DB::raw('YEAR(date_posted)'), $year)
            ->where(DB::raw('MONTH(date_posted)'), $month)
            ->onlyTrashed()
            ->count();

        $bu = Businessunit::findOrFail($id);

        $title = "Bank Reconciliation System - Lists of checking accounts in a month";
        $pagetitle = "Checking Accounts lists";
        $doctitle = "Checking Accounts in the month of";
        $template = "all";

        return view('admin.checkingaccounts.viewmonth', compact('pagetitle', 'title', 'months', 'views', 'id', 'doctitle', 'newdate', 'company', 'bu', 'userid', 'code', 'year', 'month', 'account', 'uploader', 'template', 'countall', 'counttrash', 'accountdata'));

    }

    public function listChecksTrash($id, $userid, $account, $code, $year, $month) {

        $views = Checkingaccounts::onlyTrashed()
            ->where('nav_setup_no', $code)
            ->where('uploaded_by', $userid)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->where(DB::raw('YEAR(date_posted)'), $year)
            ->where(DB::raw('MONTH(date_posted)'), $month)
            ->paginate(10);

        $uploader = User::findOrFail($userid);

        $accountdata = BankAccount::findOrFail($account);

        $newdate = date('F Y', strtotime($year.'-'.$month.'-01'));

        $countall = Checkingaccounts::select('*')
            ->where('nav_setup_no', $code)
            ->where('uploaded_by', $userid)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->where(DB::raw('YEAR(date_posted)'), $year)
            ->where(DB::raw('MONTH(date_posted)'), $month)
            ->limit(8)
            ->count();

        $counttrash = Checkingaccounts::select('*')
            ->where('nav_setup_no', $code)
            ->where('uploaded_by', $userid)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->where(DB::raw('YEAR(date_posted)'), $year)
            ->where(DB::raw('MONTH(date_posted)'), $month)
            ->onlyTrashed()
            ->count();

        $bu = Businessunit::findOrFail($id);

        $title = "Bank Reconciliation System - Lists of checking accounts in a month";
        $pagetitle = "Checking Accounts lists";
        $doctitle = "Checking Accounts in the month of";
        $template = "trash";

        return view('admin.checkingaccounts.viewmonth', compact('pagetitle', 'title', 'months', 'views', 'id', 'doctitle', 'newdate', 'company', 'bu', 'userid', 'code', 'year', 'month', 'account', 'uploader', 'template', 'countall', 'counttrash', 'accountdata'));

    }

    public function getChecks( $id ) {

        $data = json_decode($id);

        if ($data[1] == 'restore') {

            $bs = Checkingaccounts::withTrashed()
                ->where('id', $data[0])
                ->restore();

            return response($bs);
        }

        $check = Checkingaccounts::find($data[0]);

        $date = $check->date_posted->format('m/d/Y');

        $response = [
            'id'       => $check->id,
            'date_posted'     => $date,
            'transaction_desc'   => $check->transaction_desc,
            'check_no' => $check->check_no,
            'trans_amount'   => $check->trans_amount,
            'balance'  => $check->balance,
        ];

        return response($response);

    }

    public function updateChecks(Request $request, $id) {



        $date = explode('/', $request->bankdate);
//
        list($month, $day, $year) = $date;
//
        $newdate = $year . '-' . $month.'-'.$day;

//        return $newdate;

//
        try {
            $check = Checkingaccounts::find($id);
            $check->date_posted = $newdate;
            $check->transaction_desc = $request->desc;
            $check->check_no = $request->checkno;
            $check->trans_amount = $request->bankamount;
            $check->balance = $request->balance;
            $check->save();
//
            $stringdate = $check->date_posted->format('F d, Y');
//
            $response = [
                'date'      => $stringdate,
                'desc'          => $check->transaction_desc,
                'checkno'       => $check->check_no,
                'amount'    => $check->trans_amount,
                'balance'       => $check->balance,
            ];
//
            return response($response);
//
        }catch (Exception $e) {
            dd($e);
        }

    }

    public function trashChecks($id) {

        try {
            $data = json_decode($id);

            if ($data[1] == 'delete') {
//                $code = User::withTrashed()
//                    ->where('user_id', $data[0])
//                    ->forceDelete();
////
//                return $code;
            }
//
            $check = Checkingaccounts::findOrFail($data[0]);
            $check->delete();

            return response($data);

        }catch (Exception $e) {
            dd($e);
        }

    }

//    public function month_r($id) {
//
//        //dd($id);
//
//        $months = Checkingaccounts::select('date_posted', 'uploaded_by', 'date_uploaded')
//            ->where('bankaccount_id', $id)
//            ->groupBy(DB::raw('DATE_FORMAT(date_posted, "%M %Y")'))
//            ->get();
//
//        //dd($months);
//
//        $title = "Bank Reconciliation System - Checking accounts list months";
//        $pagetitle = "Months";
//        $doctitle = "Months checking accounts";
//
//        return view('admin.checkingaccounts.month', compact('pagetitle', 'title', 'months', 'id', 'doctitle'));
//
//
////        foreach ($months as $month) {
////            echo date('F Y', strtotime($month->effective_date));
////        }
//    }

    public function in_month_r($id, $year, $month) {

        $views = Checkingaccounts::select('id', 'date_posted', 'check_no', 'trans_amount', 'balance', 'date_uploaded', 'uploaded_by')
            ->where('bankaccount_id', $id)
            ->where(DB::raw('YEAR(date_posted)'), $year)
            ->where(DB::raw('MONTH(date_posted)'), $month)
//            ->limit(8)
            ->paginate(10);

        $newdate = date('F Y', strtotime($year.'-'.$month.'-01'));

        $title = "Bank Reconciliation System - Lists of checking accounts in a month";
        $pagetitle = "Checking accounts lists";
        $doctitle = "Checking accounts in the month of";

        return view('admin.checkingaccounts.viewmonth', compact('pagetitle', 'title', 'months', 'views', 'id', 'doctitle', 'newdate'));

    }
}
