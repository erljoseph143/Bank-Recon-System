<?php

namespace App\Http\Controllers\Admin;

use App\BankAccount;
use App\BankNo;
use App\BankStatement;
use App\Businessunit;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BankstatementController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;
        $bus = BankStatement::join('businessunit', 'bank_statement.bu_unit', '=', 'businessunit.unitid')
            ->groupBy('businessunit.unitid')
            ->get(['businessunit.unitid', 'businessunit.bname']);
        $mode = ' : Business units';
        $pagetitle = "Bank Statements";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "Business units contained bank statements";

        return view('admin.bankstatement.index', compact('pagetitle', 'bus', 'users_no', 'title', 'mode', 'doctitle', 'mode', 'login_user_firstname'));
    }

    public function listUsers($id) {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;
        $users = User::join('bank_statement', 'users.user_id', '=', 'bank_statement.uploaded_by')
            ->join('user_type', 'users.privilege', '=', 'user_type.user_type_id')
            ->where('bank_statement.bu_unit', $id)
            ->groupBy('users.firstname')
            ->withTrashed()
            ->get(['users.firstname', 'users.lastname', 'user_type.user_type_name', 'users.user_id', 'users.deleted_at']);

        $bu = Businessunit::select('bname')->findOrFail($id);
        $mode = ' : Users uploaded';
        $pagetitle = "Bank Statements";
        $crumbtitle = "Users uploaded";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "users uploaded a bank statement";

        return view('admin.bankstatement.userlist', compact('pagetitle', 'users', 'id', 'title', 'mode', 'doctitle', 'crumbtitle', 'bu', 'login_user_firstname'));

    }

    public function listAccounts($id) {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;

        $bs = BankStatement::join('bankaccount as b', 'bank_statement.bank_account_no', DB::raw('(SELECT bankno FROM bankno WHERE id=b.bankno AND b.buid='.$id.')'))
//            ->where('uploaded_by', $userid)
            ->where('bu_unit', $id)
            ->groupBy('bank_account_no')
            ->get(['bank_statement.bank_account_no', 'b.id', 'b.bank', 'b.accountno', 'b.accountname']);

        $bu = Businessunit::select('bname')->findOrFail($id);
//        $user = User::select('firstname', 'lastname')->findOrFail($userid);
//        session(['bu' => $bu, 'user' => $user]);
        session(['bu' => $bu]);

        $mode = ' : Bank Accounts';
        $crumbtitle = "Bank Accounts";
        $pagetitle = "Bank Statements";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "bank accounts contained bank statements";

        return view('admin.bankstatement.accountlist', compact('pagetitle', 'id', 'userid', 'title', 'bu', 'user','doctitle', 'bs', 'crumbtitle', 'mode', 'login_user_firstname'));

    }

    public function monthlistBankStatements($id, $account, $code) {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;
        $pagetitle = "Bank Statement month list";
        $title = "Bank Reconciliation System - ".$pagetitle;
        $doctitle = "bank statements months";

        $months = BankStatement::select('bank_date', 'date_added')
            ->where('bank_account_no', $code)
            ->where('bu_unit', $id)
            ->withTrashed()
            ->groupBy(DB::raw('DATE_FORMAT(bank_date, "%M %Y")'))
            ->get();

        $bu = session('bu');
        $user = session('user');
        $accountname = BankAccount::select('bank', 'accountno', 'accountname')->findOrFail($account);
        session(['accountname' => $accountname]);
        return view('admin.bankstatement.month', compact('pagetitle', 'months', 'title', 'doctitle', 'account', 'id', 'company', 'bu', 'user', 'code', 'userid', 'accountname', 'login_user_firstname'));

    }

    public function listBankStatements(Request $request, $id, $account, $code, $year, $month) {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;

        $views = BankStatement::select('bank_id', 'bank_date', 'description', 'bank_check_no', 'bank_amount', 'bank_balance', 'date_modified', 'modified_by')
            ->where('bank_account_no', $code)
            ->where('bu_unit', $id)
            ->where(DB::raw('YEAR(bank_date)'), $year)
            ->where(DB::raw('MONTH(bank_date)'), $month)
            ->simplePaginate(10);

        $newdate = date('F Y', strtotime($year.'-'.$month.'-01'));

        $countall = BankStatement::where('bank_account_no', $code)
            ->where('bu_unit', $id)
            ->where(DB::raw('YEAR(bank_date)'), $year)
            ->where(DB::raw('MONTH(bank_date)'), $month)
            ->count('bank_id');

        $counttrash = BankStatement::where('bank_account_no', $code)
            ->where('bu_unit', $id)
            ->where(DB::raw('YEAR(bank_date)'), $year)
            ->where(DB::raw('MONTH(bank_date)'), $month)
            ->onlyTrashed()
            ->count('bank_id');

        $bu = session('bu');
        $uploader = session('user');
        $accountdata = session('accountname');

        $title = "Bank Reconciliation System - Lists of bank statements in a month";
        $pagetitle = "Bank Statements lists";
        $doctitle = "Bank Statements in the month of";
        if ($request->p == 'trash') {
            $template = "trash";
        } else {
            $template = "all";
        }

        return view('admin.bankstatement.viewmonth', compact('pagetitle', 'title', 'months', 'views', 'id', 'doctitle', 'newdate', 'company', 'bu', 'userid', 'code', 'year', 'month', 'account', 'uploader', 'template', 'countall', 'counttrash', 'accountdata', 'login_user_firstname'));

    }

    public function viewAjax(Request $request) {

        $columns = ['bank_date', 'description', 'bank_check_no', 'bank_amount', 'bank_balance'];
        $where = [['bank_account_no', $request->code], ['bu_unit', $request->bu]];

        if ($request->page == 'all') {
            $totalData = BankStatement::where($where)->whereYear('bank_date', $request->year)
                ->whereMonth('bank_date', $request->month)
                ->count('bank_id');
            $title="trash";
        } else {
            $totalData = BankStatement::where($where)->whereYear('bank_date', $request->year)
                ->whereMonth('bank_date', $request->month)
                ->onlyTrashed()
                ->count('bank_id');
            $title="delete";
        }
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value'))) {
            if ($request->page == 'all') {
                $transactions = BankStatement::where($where)->whereYear('bank_date', $request->year)
                    ->whereMonth('bank_date', $request->month)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get(['bank_id', 'bank_date', 'description', 'bank_check_no', 'bank_amount', 'bank_balance']);
            } else {
                $transactions = BankStatement::onlyTrashed()->where($where)->whereYear('bank_date', $request->year)
                    ->whereMonth('bank_date', $request->month)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get(['bank_id', 'bank_date', 'description', 'bank_check_no', 'bank_amount', 'bank_balance']);
            }
        } else {
            $search = $request->input('search.value');
            if ($request->page == 'all') {
                $transactions = BankStatement::where(function ($query) use($search) {
                    $query->where('bank_check_no', 'LIKE', '%'.$search.'%')
                        ->orWhere('bank_amount', 'LIKE', '%'.$search.'%')
                        ->orWhere('bank_balance', 'LIKE', '%'.$search.'%');
                })
                    ->where($where)->whereYear('bank_date', $request->year)
                    ->whereMonth('bank_date', $request->month)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get(['bank_id', 'bank_date', 'description', 'bank_check_no', 'bank_amount', 'bank_balance']);
                $totalFiltered = BankStatement::where(function ($query) use($search) {
                    $query->where('bank_check_no', 'LIKE', '%'.$search.'%')
                        ->orWhere('bank_amount', 'LIKE', '%'.$search.'%')
                        ->orWhere('bank_balance', 'LIKE', '%'.$search.'%');
                })
                    ->where($where)->whereYear('bank_date', $request->year)
                    ->whereMonth('bank_date', $request->month)
                    ->count('bank_id');
            } else {
                $transactions = BankStatement::onlyTrashed()->where($where)->whereYear('bank_date', $request->year)
                    ->whereMonth('bank_date', $request->month)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get(['bank_id', 'bank_date', 'description', 'bank_check_no', 'bank_amount', 'bank_balance']);
                $totalFiltered = BankStatement::where(function ($query) use($search) {
                    $query->where('bank_check_no', 'LIKE', '%'.$search.'%')
                        ->orWhere('bank_amount', 'LIKE', '%'.$search.'%')
                        ->orWhere('bank_balance', 'LIKE', '%'.$search.'%');
                })
                    ->where($where)->whereYear('bank_date', $request->year)
                    ->whereMonth('bank_date', $request->month)
                    ->count('bank_id');
            }
        }
        $data = [];
        if (!empty($transactions)) {
            foreach ($transactions as $transaction) {
                $restore = ($title == "delete")?'<a href="'.route('bank-statements.destroy', $transaction->bank_id).'" class="on-default remove-row" title="restore" onclick="$.Notification.notify(\'white\',\'top left\', \'\', \'Successfully restored!\')"><i class="fa fa-mail-reply"></i></a>':'';
                $nestedData['bank_date'] = $transaction->bank_date->format('M d Y');
                $nestedData['description'] = $transaction->description;
                $nestedData['bank_check_no'] = $transaction->bank_check_no;
                $nestedData['bank_amount'] = $transaction->bank_amount;
                $nestedData['bank_balance'] = $transaction->bank_balance;
                $nestedData['action'] = '<div class="actions">'.
                    $restore.'<a href="'.route('bank-statements.destroy',$transaction->bank_id).'" class="on-default remove-row" title="'.$title.'"><i class="fa fa-trash" onclick="$.Notification.notify(\'white\',\'top left\', \'\', \'Successfully move to trash!\')"></i></a>'.'</div>';
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
            $bs = BankStatement::where($where)
                ->whereMonth('bank_date', $request->month)
                ->whereYear('bank_date', $request->year);
            $bs->delete();
            return response()->json(['a'=>'month', 'b'=>'trash']);
        }
        $bs = BankStatement::withTrashed()
            ->where($where)
            ->whereMonth('bank_date', $request->month)
            ->whereYear('bank_date', $request->year)
            ->restore();
        return response()->json(['a'=>'month','b'=>'restore']);
    }

    public function destroy(Request $request, $id) {
        try {
            if ($request->p == 'restore') {
                $disburse = BankStatement::withTrashed()
                    ->where('bank_id', $id)
                    ->restore();
                return response()->json(['a'=>$id, 'b'=>'restore']);
            }
//
            $disb = BankStatement::where('bank_id', $id);
            $disb->delete();
            return response()->json(['a'=>$id,'b'=>'trash']);

        }catch (\Exception $e) {
            return response()->json($e);
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

    public function show() {
        return 'sdfsdf';
    }

}
