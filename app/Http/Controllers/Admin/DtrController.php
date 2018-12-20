<?php

namespace App\Http\Controllers\Admin;

use App\BankAccount;
use App\Businessunit;
use App\DTR;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DtrController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

        $title = "Bank Reconciliation System Admin - Dtr";
        $login_user_firstname = "";
        $pagetitle = "Bank statement Dtr";
        $doctitle = "";
        $mode = ' : Business units';

        $bus = DTR::join('businessunit', 'dtr.bu_unit', '=', 'businessunit.unitid')
            ->groupBy('businessunit.unitid')
            ->get(['businessunit.unitid', 'businessunit.bname']);

        return view('admin.dtr.index', compact('title', 'login_user_firstname', 'pagetitle', 'doctitle', 'bus', 'mode'));

    }

    public function listAccounts($id) {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;

        $accounts = DTR::select('dtr.bank_account_no', 'b.id', 'b.bank', 'b.accountno', 'b.accountname')
            ->join('bankaccount as b', 'dtr.bank_account_no', DB::raw('(SELECT bankno FROM bankno WHERE id=b.bankno AND b.buid='.$id.')'))
            ->where('bu_unit', $id)
            ->groupBy('bank_account_no')
            ->get();

        $bu = Businessunit::select('bname')->findOrFail($id);
        session(['bu' => $bu]);

        $mode = ' : Bank Accounts';
        $crumbtitle = "Bank Accounts";
        $pagetitle = "Dtr";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "bank accounts contained dtr";

        return view('admin.dtr.accountlist', compact('pagetitle', 'id', 'userid', 'title', 'bu','doctitle', 'bs', 'crumbtitle', 'mode', 'login_user_firstname', 'accounts'));

    }

    public function monthlist($id, $account, $code) {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;
        $pagetitle = "DTR month list";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "dtr months";

        $months = DTR::select('bank_date', 'created_at')
            ->where('bank_account_no', $code)
            ->where('bu_unit', $id)
            ->withTrashed()
            ->groupBy(DB::raw('DATE_FORMAT(bank_date, "%M %Y")'))
            ->get();

        $bu = session('bu');
        $accountname = BankAccount::select('bank', 'accountno', 'accountname')->findOrFail($account);
        session(['accountname' => $accountname]);
        return view('admin.dtr.month', compact('pagetitle', 'months', 'title', 'doctitle', 'account', 'id', 'company', 'bu', 'user', 'code', 'userid', 'accountname', 'login_user_firstname'));

    }

    public function listDTR(Request $request, $id, $account, $code, $year, $month) {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;

        $views = DTR::select('bank_date', 'check_no', 'branch', 'trans_des', 'bank_amount', 'bank_balance')
            ->where('bank_account_no', $code)
            ->where('bu_unit', $id)
            ->where(DB::raw('YEAR(bank_date)'), $year)
            ->where(DB::raw('MONTH(bank_date)'), $month);

        $newdate = date('F Y', strtotime($year.'-'.$month.'-01'));

        $countall = $views->count();

        $counttrash = $views->onlyTrashed()->count();

//        $views = $views->simplePaginate(10);

        $bu = session('bu');
        $uploader = session('user');
        $accountdata = session('accountname');

        $title = "Bank Reconciliation System - Lists of DTR in a month";
        $pagetitle = "DTR lists";
        $doctitle = "DTR in the month of";
        if ($request->p == 'trash') {
            $template = "trash";
        } else {
            $template = "all";
        }

        return view('admin.dtr.viewmonth', compact('pagetitle', 'title', 'months', 'id', 'doctitle', 'newdate', 'company', 'bu', 'userid', 'code', 'year', 'month', 'account', 'uploader', 'template', 'countall', 'counttrash', 'accountdata', 'login_user_firstname'));

    }

    public function viewAjax(Request $request) {

        $columns = ['bank_date', 'description', 'bank_check_no', 'bank_amount', 'bank_balance'];
        $where = [['bank_account_no', $request->code], ['bu_unit', $request->bu]];

        $totalData = DTR::where($where)->whereYear('bank_date', $request->year)
            ->whereMonth('bank_date', $request->month);

        if ($request->page == 'all') {
            $totalData = $totalData->count();
            $title="trash";
        } else {
            $totalData = DTR::onlyTrashed()->count();
            $title="delete";
        }
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value'))) {
            $transactions = DTR::select('id', 'bank_date', 'check_no', 'trans_des', 'bank_amount', 'bank_balance')
                ->where($where)->whereYear('bank_date', $request->year)
                ->whereMonth('bank_date', $request->month)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir);
            if ($request->page == 'all') {
                $transactions = $transactions->get();
            } else {
                $transactions = DTR::onlyTrashed()->get();
            }
        } else {
            $search = $request->input('search.value');

            $transactions = DTR::select('id', 'bank_date', 'check_no', 'trans_des', 'bank_amount', 'bank_balance')
                ->where($where)->whereYear('bank_date', $request->year)
                ->whereMonth('bank_date', $request->month)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir);

            $totalFiltered = DTR::where(function ($query) use($search) {
                $query->where('check_no', 'LIKE', '%'.$search.'%')
                    ->orWhere('bank_amount', 'LIKE', '%'.$search.'%')
                    ->orWhere('bank_balance', 'LIKE', '%'.$search.'%');
            })
                ->where($where)->whereYear('bank_date', $request->year)
                ->whereMonth('bank_date', $request->month);

            if ($request->page == 'all') {
                $transactions = $transactions->where(function ($query) use($search) {
                    $query->where('check_no', 'LIKE', '%'.$search.'%')
                        ->orWhere('bank_amount', 'LIKE', '%'.$search.'%')
                        ->orWhere('bank_balance', 'LIKE', '%'.$search.'%');
                })->get();

                $totalFiltered = $totalFiltered->count();

            } else {
                $transactions = $transactions->onlyTrashed()->get();
                $totalFiltered = $totalFiltered->count();

            }
        }
        $data = [];
        if (!empty($transactions)) {
            foreach ($transactions as $transaction) {
                $restore = ($title == "delete")?'<a href="'.route('dtr.destroy', $transaction->id).'" class="on-default remove-row" title="restore" onclick="$.Notification.notify(\'white\',\'top left\', \'\', \'Successfully restored!\')"><i class="fa fa-mail-reply"></i></a>':'<a href="'.route('dtr.destroy',$transaction->id).'" class="on-default remove-row" title="'.$title.'"><i class="fa fa-trash" onclick="$.Notification.notify(\'white\',\'top left\', \'\', \'Successfully move to trash!\')"></i></a>';
                $nestedData['bank_date'] = $transaction->bank_date;
                $nestedData['bank_check_no'] = $transaction->check_no;
                $nestedData['description'] = $transaction->trans_des;
                $nestedData['bank_amount'] = $transaction->bank_amount;
                $nestedData['bank_balance'] = $transaction->bank_balance;
                $nestedData['action'] = '<div class="actions">'.$restore.'</div>';
                $data[] = $nestedData;
            }
        }

        return response()->json([
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => $totalData,
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data,
        ]);
    }

    public function deletemonth(Request $request) {
        $where = [
            ['bu_unit', $request->bu],
            ['bank_account_no', $request->code],
        ];
        if ($request->action == 'trash') {
            $bs = DTR::where($where)
                ->whereMonth('bank_date', $request->month)
                ->whereYear('bank_date', $request->year);
            $bs->delete();
            return response()->json(['a'=>'month', 'b'=>'trash']);
        }
        $bs = DTR::withTrashed()
            ->where($where)
            ->whereMonth('bank_date', $request->month)
            ->whereYear('bank_date', $request->year)
            ->restore();
        return response()->json(['a'=>'month','b'=>'restore']);
    }

    public function destroy(Request $request, $id) {
        try {
            if ($request->p == 'restore') {
                $disburse = DTR::withTrashed()
                    ->where('id', $id)
                    ->restore();
                return response()->json(['a'=>$id, 'b'=>'restore']);
            }
//
            $disb = DTR::where('id', $id);
            $disb->delete();
            return response()->json(['a'=>$id,'b'=>'trash']);

        }catch (\Exception $e) {
            return response()->json($e);
        }
    }

}
