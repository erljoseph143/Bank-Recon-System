<?php

namespace App\Http\Controllers\ColAcct;

use App\BankAccount;
use App\Businessunit;
use App\Checkingaccounts;
use App\Usertype;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckingaccountController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function listAcc() {

        $login_user = Auth::user();

        $login_user_firstname = $login_user->firstname;
        $login_user_lastname = $login_user->lastname;
        $userid = $login_user->user_id;
        $created = $login_user->created_at;

        $login_user_type = Usertype::select('user_type_name')
            ->where('user_type_id', $login_user->privilege)
            ->first();

        $title = "Bank Reconciliation System - Checking Accounts Lists";

        $codes = Checkingaccounts::select('nav_setup_no')
            ->groupBy('nav_setup_no')
            ->get();

        $bu = Businessunit::select('bname')
            ->where('unitid', $login_user->bunitid)
            ->first();

        $newaccounts = [];

        foreach ($codes as $key => $code) {

            $accounts = BankAccount::select('bankaccount.id', 'bankaccount.bank', 'bankaccount.accountno', 'bankaccount.accountname')
                ->join('bankno as a', 'bankaccount.bankno', '=', 'a.id')
                ->where('a.bankno', $code->nav_setup_no)
                ->where('bankaccount.buid', 40)
                ->first();

            if ($accounts != null) {
                $newaccounts[$key][0] = $accounts->bank;
                $newaccounts[$key][1] = $accounts->accountno;
                $newaccounts[$key][2] = $accounts->accountname;
                $newaccounts[$key][3] = $code->nav_setup_no;
            }

        }

        return view('colacct.checks.list1', compact('title', 'login_user', 'login_user_lastname', 'userid', 'login_user_type', 'created', 'newaccounts', 'bu'));

    }

    public function categories($code) {

        $login_user = Auth::user();

        $login_user_type = Usertype::select('user_type_name')
            ->where('user_type_id', $login_user->privilege)
            ->first();

        $bu = Businessunit::select('bname')
            ->where('unitid', $login_user->bunitid)
            ->first();

        $title = "Bank Reconciliation System - Checking Accounts Lists";

        $check_dates = Checkingaccounts::join('users as u', 'u.user_id', 'checking_account.created_by')
            ->select( DB::raw('DATE_FORMAT(checking_account.date_posted, "%M %Y") as datein'), 'checking_account.nav_setup_no', 'checking_account.created_at', DB::raw('CONCAT(u.firstname, " ", u.lastname) as name') )
            ->groupBy(DB::raw('DATE_FORMAT(date_posted, "%M %Y")'), 'checking_account.nav_setup_no')
            ->where('bu', $login_user->bunitid)
            ->where('nav_setup_no', $code)
            ->get();

        return view('colacct.checks.date', compact('check_dates', 'title', 'login_user', 'login_user_type', 'bu'));

    }

    public function listsChecks( $code, $date ) {

        $login_user = Auth::user();

        $login_user_type = Usertype::select('user_type_name')
            ->where('user_type_id', $login_user->privilege)
            ->first();

        $newdate = new DateTime($date);

        $year = $newdate->format('Y');
        $month = $newdate->format('m');

        $title = 'Accounting Colonnade | View Checking Accounts';

        $bu = Businessunit::select('bname')
            ->where('unitid', $login_user->bunitid)
            ->first();

        $data = [
            'month' => $month,
            'year'  => $year,
            'code'  => $code,
        ];

        $checks = Checkingaccounts::select('checking_account.date_posted', 'checking_account.check_no', 'checking_account.trans_amount', 'checking_account.balance', 'checking_account.trans_type', 'checking_account.match_type', 'checking_account.created_at', 'users.firstname', 'users.lastname')
            ->join('users', 'checking_account.created_by', 'users.user_id')
            ->where(DB::raw('YEAR(checking_account.date_posted)'), $year)
            ->where(DB::raw('MONTH(checking_account.date_posted)'), $month)
            ->where('checking_account.nav_setup_no', $code)
            ->where('checking_account.company', $login_user->company_id)
            ->where('checking_account.bu', $login_user->bunitid)
            ->orderBy('checking_account.check_no', 'DESC')
            ->get();

        return view('colacct.checks.checklist', compact('title', 'bu', 'code', 'date', 'checks', 'login_user', 'login_user_type', 'data'));

    }

    public function monthChecks($code, $date) {

        $login_user = Auth::user();

        $newdate = new DateTime($date);

        $year = $newdate->format('Y');
        $month = $newdate->format('m');

        $match_checks = Checkingaccounts::select()
            ->where(DB::raw('MONTH(date_posted)'), $month)
            ->where(DB::raw('YEAR(date_posted)'), $year)
            ->where('company', $login_user->company_id)
            ->where('bu', $login_user->bunitid)
            ->where('nav_setup_no', $code)
            ->get();

        return view('colacct.checks.match', compact('match_checks'));

    }

    public function matchChecks($code, $date) {

        $login_user = Auth::user();

        $newdate = new DateTime($date);

        $year = $newdate->format('Y');
        $month = $newdate->format('m');

        $match_checks = Checkingaccounts::select()
            ->where(DB::raw('MONTH(date_posted)'), $month)
            ->where(DB::raw('YEAR(date_posted)'), $year)
            ->where('company', $login_user->company_id)
            ->where('bu', $login_user->bunitid)
            ->where('match_type', 'match check')
            ->where('nav_setup_no', $code)
            ->get();

        return view('colacct.checks.match', compact('match_checks'));

    }

}
