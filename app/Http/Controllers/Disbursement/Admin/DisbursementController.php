<?php

namespace App\Http\Controllers\Admin;

use App\BankAccount;
use App\BankNo;
use App\Businessunit;
use App\PdcLine;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DisbursementController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function listBU() {
        $bus = PdcLine::select('businessunit.unitid', 'businessunit.bname')
            ->join('users', 'pdc_line.uploaded_by', '=', 'users.user_id')
            ->join('businessunit', 'pdc_line.bu_unit', '=', 'businessunit.unitid')
            ->groupBy('businessunit.unitid')
            ->get();

        $users_no = [];

        foreach ($bus as $bu) {
            $users = PdcLine::select(DB::raw('COUNT(DISTINCT(uploaded_by)) as count'))
                ->where('bu_unit', $bu->unitid)
                ->first();
            $users_no[] = $users->count;
        }

        $mode = ' : Business units';

        $pagetitle = "Disbursements";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "Business units contained disbursements";

        return view('admin.disbursement.index', compact('pagetitle', 'bus', 'users_no', 'title', 'mode', 'doctitle', 'mode'));
    }

    public function listUsers($id) {

        $users = User::select('users.firstname', 'users.lastname', 'user_type.user_type_name', 'users.user_id')
            ->join('pdc_line', 'users.user_id', '=', 'pdc_line.uploaded_by')
            ->join('user_type', 'users.privilege', '=', 'user_type.user_type_id')
            ->where('pdc_line.bu_unit', $id)
            ->groupBy('users.firstname')
            ->get();

        $bu = Businessunit::findOrFail($id);

        $mode = ' : Users uploaded';

        $pagetitle = "Disbursements";
        $crumbtitle = "Users uploaded";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "users who uploaded a disbursements data";

        return view('admin.disbursement.userlist', compact('pagetitle', 'users', 'id', 'title', 'mode', 'doctitle', 'crumbtitle', 'bu'));

    }

    public function listAccounts($id, $userid) {

        $accounts = PdcLine::select('baccount_no')
            ->where('uploaded_by', $userid)
            ->where('bu_unit', $id)
            ->groupBy('baccount_no')
            ->get();

        $final_banks = [];

        foreach ($accounts as $account) {

            $bankcode = BankNo::select('id')
                ->where('bankno', $account->baccount_no)
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

        $pagetitle = "Disbursements";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "bank accounts with disbursements";

        return view('admin.disbursement.accountlist', compact('pagetitle', 'final_banks', 'id', 'userid', 'title', 'bu', 'user','doctitle', 'accounts', 'crumbtitle', 'mode'));

    }

    public function monthlistDis($id, $userid, $account, $code) {

        $pagetitle = "Disbursement month list";

        $title = "Bank Reconciliation System - ".$pagetitle;

        $doctitle = "disbursement months";

        $months = PdcLine::select('cv_date', 'uploaded_by', 'date_upload')
            ->where('baccount_no', $code)
            ->where('uploaded_by', $userid)
            ->where('bu_unit', $id)
            ->groupBy(DB::raw('DATE_FORMAT(cv_date, "%M %Y")'))
            ->get();

        $bu = Businessunit::findOrFail($id);
        $user = User::findOrFail($userid);
        $accountname = BankAccount::findOrFail($account);

        return view('admin.disbursement.month', compact('pagetitle', 'months', 'title', 'doctitle', 'account', 'id', 'company', 'bu', 'user', 'code', 'userid', 'accountname'));

    }

    public function listDis($id, $userid, $account, $code, $year, $month) {

        $views = PdcLine::select('id', 'cv_date', 'check_no', 'check_amount', 'label_match', 'date_modified', 'modified_by')
            ->where('baccount_no', $code)
            ->where('uploaded_by', $userid)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->where(DB::raw('YEAR(cv_date)'), $year)
            ->where(DB::raw('MONTH(cv_date)'), $month)
            ->paginate(10);

        $uploader = User::findOrFail($userid);

        $accountdata = BankAccount::findOrFail($account);

        $newdate = date('F Y', strtotime($year.'-'.$month.'-01'));

        $countall = PdcLine::select('*')
            ->where('baccount_no', $code)
            ->where('uploaded_by', $userid)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->where(DB::raw('YEAR(cv_date)'), $year)
            ->where(DB::raw('MONTH(cv_date)'), $month)
            ->limit(8)
            ->count();

        $counttrash = PdcLine::select('*')
            ->where('baccount_no', $code)
            ->where('uploaded_by', $userid)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->where(DB::raw('YEAR(cv_date)'), $year)
            ->where(DB::raw('MONTH(cv_date)'), $month)
            ->onlyTrashed()
            ->count();

        $bu = Businessunit::findOrFail($id);

        $title = "Bank Reconciliation System - Lists of disbursements in a month";
        $pagetitle = "Disbursements lists";
        $doctitle = "Disbursements in the month of";
        $template = "all";

        return view('admin.disbursement.viewmonth', compact('pagetitle', 'title', 'months', 'views', 'id', 'doctitle', 'newdate', 'company', 'bu', 'userid', 'code', 'year', 'month', 'account', 'uploader', 'template', 'countall', 'counttrash', 'accountdata'));

    }

    public function listDisTrash($id, $userid, $account, $code, $year, $month) {

        $views = PdcLine::onlyTrashed()
            ->where('baccount_no', $code)
            ->where('uploaded_by', $userid)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->where(DB::raw('YEAR(cv_date)'), $year)
            ->where(DB::raw('MONTH(cv_date)'), $month)
            ->paginate(10);

        $uploader = User::findOrFail($userid);

        $accountdata = BankAccount::findOrFail($account);

        $newdate = date('F Y', strtotime($year.'-'.$month.'-01'));

        $countall = PdcLine::select('*')
            ->where('baccount_no', $code)
            ->where('uploaded_by', $userid)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->where(DB::raw('YEAR(cv_date)'), $year)
            ->where(DB::raw('MONTH(cv_date)'), $month)
            ->limit(8)
            ->count();

        $counttrash = PdcLine::select('*')
            ->where('baccount_no', $code)
            ->where('uploaded_by', $userid)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->where(DB::raw('YEAR(cv_date)'), $year)
            ->where(DB::raw('MONTH(cv_date)'), $month)
            ->onlyTrashed()
            ->count();

        $bu = Businessunit::findOrFail($id);

        $title = "Bank Reconciliation System - Lists of disbursements in a month";
        $pagetitle = "Disbursements lists";
        $doctitle = "Disbursements in the month of";
        $template = "trash";

        return view('admin.disbursement.viewmonth', compact('pagetitle', 'title', 'months', 'views', 'id', 'doctitle', 'newdate', 'company', 'bu', 'userid', 'code', 'year', 'month', 'account', 'uploader', 'template', 'countall', 'counttrash', 'accountdata'));

    }

    public function getDisburse($id) {

        $data = json_decode($id);

        if ($data[1] == 'restore') {

            $disburse = PdcLine::withTrashed()
                ->where('id', $data[0])
                ->restore();

            return response($disburse);
        }

        $disburse = PdcLine::find($data[0]);

        $date = $disburse->cv_date->format('m/d/Y');

        $response = [
            'id'       => $disburse->id,
            'cvdate'     => $date,
            'checkno'   => $disburse->check_no,
            'amount' => $disburse->check_amount,
        ];

        return response($response);

    }

    public function updateDisburse(Request $request, $id) {

        $date = explode('/', $request->date);

        list($month, $day, $year) = $date;

        $newdate = $year . '-' . $month.'-'.$day;

        try {
            $disburse = PdcLine::find($id);
            $disburse->cv_date = $newdate;
            $disburse->check_no = $request->checkno;
            $disburse->check_amount = $request->amount;
            $disburse->save();

            $stringdate = $disburse->cv_date->format('F d, Y');

            $response = [
                'bankdate'      => $stringdate,
                'checkno'       => $disburse->check_no,
                'bankamount'    => $disburse->check_amount,
            ];

            return response($response);

        }catch (Exception $e) {
            dd($e);
        }

    }

    public function trashDisburse($id) {

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
            $disb = PdcLine::findOrFail($data[0]);
            $disb->delete();

            return response($data);

        }catch (Exception $e) {
            dd($e);
        }

    }

    public function disbursements_r() {
        $disbursements = PdcLine::select('bankno.id', 'bankno.bankno', 'company_code', 'bu_unit', 'uploaded_by')
            ->join('bankno', 'pdc_line.baccount_no', '=', 'bankno.bankno')
            ->groupBy('baccount_no')
            //->having('id', '>', 0)
            ->get();

        $accounts = [];

        foreach ($disbursements as $key => $disburse) {

            $account = BankAccount::select('bankaccount.id', 'bankaccount.bank', 'bankaccount.accountno', 'bankaccount.accountname', 'bankaccount.company_code', 'bankaccount.buid', 'bankno.bankno')
                ->join('bankno', 'bankaccount.bankno', '=', 'bankno.id')
                ->where('bankaccount.bankno', $disburse->id)
                ->where('company_code', $disburse->company_code)
                ->where('buid', $disburse->bu_unit)
                ->first();


            $accounts[$key][] = $account;
            $accounts[$key]['user'] = $disburse->uploaded_by;

        }

        $template = '';

        $pagetitle = "Disbursements";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "disbursements";

        return view('admin.disbursement.index', compact('pagetitle', 'accounts', 'title', 'template', 'doctitle'));
    }

    public function month_r($id, $company, $bu, $user, $code) {

        $pagetitle = "Disbursements in a month list";

        $title = "Bank Reconciliation System - ".$pagetitle;

        $doctitle = "Disbursements months";

        $account = BankAccount::findOrFail($id);

        $months = PdcLine::select('cv_date', 'uploaded_by', 'date_upload')
            ->where('baccount_no', $code)
            ->where('uploaded_by', $user)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->groupBy(DB::raw('DATE_FORMAT(cv_date, "%M %Y")'))
            ->get();

        //dd($months);

        return view('admin.disbursement.month', compact('pagetitle', 'months', 'title', 'doctitle', 'account', 'id', 'company', 'bu', 'user', 'code'));

    }

    public function in_month_r($id, $company, $bu, $user, $code, $year, $month) {

        $views = PdcLine::select('cv_date', 'check_no', 'check_amount', 'label_match')
            ->where('baccount_no', $code)
            ->where('uploaded_by', $user)
//            ->where('company', $company)
//            ->where('bu_unit', $bu)
            ->where(DB::raw('YEAR(cv_date)'), $year)
            ->where(DB::raw('MONTH(cv_date)'), $month)
//            ->limit(8)
            ->paginate(10);

        $uploader = User::findOrFail($user);

        $account = BankAccount::findOrFail($id);

        $newdate = date('F Y', strtotime($year.'-'.$month.'-01'));

        $title = "Bank Reconciliation System - Lists of disbursements in a month";
        $pagetitle = "Disbursements lists";
        $doctitle = "Disbursements in the month of";

        return view('admin.disbursement.viewmonth', compact('pagetitle', 'title', 'months', 'views', 'id', 'doctitle', 'newdate', 'company', 'bu', 'user', 'code', 'year', 'month', 'account', 'uploader'));

    }

}
