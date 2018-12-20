<?php

namespace App\Http\Controllers\Admin;

use App\BankAccount;
use App\BankNo;
use App\Businessunit;
use App\PdcLine;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DisbursementController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function listBU() {
        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;
        $bus = PdcLine::select('businessunit.unitid', 'businessunit.bname')
            ->join('users', 'pdc_line.uploaded_by', '=', 'users.user_id')
            ->join('businessunit', 'pdc_line.bu_unit', '=', 'businessunit.unitid')
            ->groupBy('businessunit.unitid')
            ->get();
        $mode = ' : Business units';
        $pagetitle = "Disbursements";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "Business units containing disbursements data";
        return view('admin.disbursement.index', compact('pagetitle', 'bus', 'title', 'mode', 'doctitle', 'mode', 'login_user_firstname'));
    }

    public function listUsers($id) {
        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;
        $users = User::select('users.firstname', 'users.lastname', 'user_type.user_type_name', 'users.user_id', 'users.deleted_at')
            ->join('pdc_line', 'users.user_id', '=', 'pdc_line.uploaded_by')
            ->join('user_type', 'users.privilege', '=', 'user_type.user_type_id')
            ->where('pdc_line.bu_unit', $id)
            ->groupBy('users.firstname')
            ->withTrashed()
            ->get();
        $bu = Businessunit::where('unitid',$id)->first(['bname']);

        $mode = ' : Users uploaded';
        $pagetitle = "Disbursements";
        $crumbtitle = "Users uploaded";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "users uploaded a disbursements data";
        return view('admin.disbursement.userlist', compact('pagetitle', 'users', 'id', 'title', 'mode', 'doctitle', 'crumbtitle', 'bu', 'login_user_firstname'));
    }

    public function listAccounts($id, $userid) {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;
        $accounts = PdcLine::select('pdc_line.baccount_no', 'b.id', 'b.bank', 'b.accountno', 'b.accountname')
            ->join('bankaccount as b', 'pdc_line.baccount_no',DB::raw('(SELECT bankno FROM bankno WHERE id=b.bankno AND b.buid='.$id.')'))
            ->where('uploaded_by', $userid)
            ->where('bu_unit', $id)
            ->groupBy('baccount_no')
            ->withTrashed()
            ->get();
        $bu = Businessunit::select('bname')->findOrFail($id);
        $user = User::select('firstname', 'lastname')->findOrFail($userid);
        session(['bu' => $bu, 'user' => $user]);
        $mode = ' : Bank Accounts';
        $crumbtitle = "Bank Accounts";
        $pagetitle = "Disbursements";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "bank accounts with disbursements data";
        return view('admin.disbursement.accountlist', compact('pagetitle', 'final_banks', 'id', 'userid', 'title', 'bu', 'user','doctitle', 'accounts', 'crumbtitle', 'mode', 'login_user_firstname'));

    }

    public function monthlistDis($id, $userid, $account, $code) {
        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;
        $pagetitle = "Disbursement month list";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "disbursement months";
        $months = PdcLine::select('cv_date', 'uploaded_by', 'date_upload')
            ->where('baccount_no', $code)
            ->where('uploaded_by', $userid)
            ->where('bu_unit', $id)
            ->groupBy(DB::raw('DATE_FORMAT(cv_date, "%M %Y")'))
            ->withTrashed()
            ->get();
        $bu = session('bu');
        $user = session('user');
        $accountname = BankAccount::select('bank', 'accountno', 'accountname')->findOrFail($account);
        session(['accountname' => $accountname]);
        return view('admin.disbursement.month', compact('pagetitle', 'months', 'title', 'doctitle', 'account', 'id', 'bu', 'user', 'code', 'userid', 'accountname', 'login_user_firstname'));
    }

    public function listDis(Request $request, $id, $userid, $account, $code, $year, $month) {
        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;
        $newdate = date('F Y', strtotime($year.'-'.$month.'-01'));
        $where = [['baccount_no', $code], ['uploaded_by', $userid], ['bu_unit', $id]];
        $countall = PdcLine::where($where)
            ->where(DB::raw('YEAR(cv_date)'), $year)
            ->where(DB::raw('MONTH(cv_date)'), $month)
            ->count('id');
        $counttrash = PdcLine::where($where)
            ->where(DB::raw('YEAR(cv_date)'), $year)
            ->where(DB::raw('MONTH(cv_date)'), $month)
            ->onlyTrashed()
            ->count('id');

        $bu = session('bu');
        $uploader = session('user');
        $accountdata = session('accountname');

        $title = "Bank Reconciliation System - Lists of disbursements in a month";
        $pagetitle = "Disbursements lists";
        $doctitle = "Disbursements in the month of";
        if ($request->p == 'trash') {
            $template = "trash";
        } else {
            $template = "all";
        }
        return view('admin.disbursement.viewmonth', compact('pagetitle', 'title', 'months', 'id', 'doctitle', 'newdate', 'company', 'bu', 'userid', 'code', 'year', 'month', 'account', 'uploader', 'template', 'countall', 'counttrash', 'accountdata', 'login_user_firstname'));

    }

    public function deletemonth(Request $request) {
        $where = [
            ['bu_unit', $request->bu],
            ['baccount_no', $request->code],
            ['uploaded_by', $request->userid],
        ];

        if ($request->action == 'restore') {
            $disburse = PdcLine::withTrashed()
                ->where($where)
                ->whereMonth('cv_date', $request->month)
                ->whereYear('cv_date', $request->year)
                ->restore();
            return response()->json(['a'=>'month','b'=>'restore']);
        }

        $disb = PdcLine::where($where)
            ->whereMonth('cv_date', $request->month)
            ->whereYear('cv_date', $request->year);
        $disb->delete();
        return response()->json(['a'=>'month','b'=>'trash']);

    }

}
