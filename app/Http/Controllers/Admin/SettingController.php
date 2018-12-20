<?php

namespace App\Http\Controllers\admin;

use App\BankStatement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index() {

        $login_user = Auth::user();

        $login_user_firstname = $login_user->firstname;

        $title = "BRS Admin - Tools - General";
        $pagetitle = "General Settings";

        return view('admin.settings.index', compact('title', 'login_user_firstname', 'pagetitle'));

    }

    public function scanningbs() {

        set_time_limit(0);
        ob_implicit_flush(true);
        ob_end_flush();

        echo json_encode([
            "status" => "Scanning",
            "url"   => ""
        ]);

        $duplicatebs = BankStatement::select('bank_date', 'description', 'bank_check_no', 'bank_amount', 'bank_balance', 'bank_account_no', DB::raw('COUNT(*) as duplicate_count'))
            ->withTrashed()
            ->groupBy('bank_date', 'description', 'bank_check_no', 'bank_amount', 'bank_balance', 'bank_account_no')
            ->havingRaw('COUNT(*) > ?', [1])
            ->get();

        session(['duplicatebs' => $duplicatebs]);

        return response()->json([
            "status" => "Successfully Scaned!",
            "url"   => route('adminscanedduplicatebs')
        ]);

    }

    public function scanedduplicatebs() {

        $login_user = Auth::user();

        $login_user_firstname = $login_user->firstname;

        $title = "BRS Admin - Tools - General - Lists of bank statements with duplicate entry";
        $pagetitle = "General Settings - Bank Statements";

        $duplicatebs = session('duplicatebs');


        return view("admin.settings.listsduplicatebs", compact('title', 'login_user_firstname', 'pagetitle', 'duplicatebs'));

    }

    public function viewduplicatebs(Request $request) {

        $data = explode("|", $request->data);

        $duplicatebs = BankStatement::select('bank_id', 'bank_date', 'description', 'bank_account_no', 'bank_check_no', 'bank_amount', 'bank_balance', 'status', 'type', 'date_added', 'bu_unit', 'deleted_at')
            ->withTrashed()
            ->where('bank_check_no', $data[1])
            ->where('bank_account_no', $data[0])
            ->where('bank_amount', $data[2])
            ->where('bank_balance', $data[3])
            ->get();

        return view('admin.settings.viewduplicatebs', compact('duplicatebs'));

    }

    public function viewprevbs(Request $request) {

        $prevbs = BankStatement::withTrashed()
            ->whereRaw("bank_id = (select max(bank_id) from `bank_statement` where bank_id < {$request->id})")
            ->first();

        return view('admin.settings.prevbs', compact('prevbs'));

    }

    public function viewnextbs(Request $request) {

        $nextbs = BankStatement::withTrashed()
            ->whereRaw("bank_id = (select min(bank_id) from `bank_statement` where bank_id > {$request->id})")
            ->first();

        return view('admin.settings.nextbs', compact('nextbs'));

    }

    public function trashduplicatebs(Request $request) {

        if ($request->action == 'trash') {
            $bankstatements = BankStatement::where('bank_id', $request->id)
                ->delete();
            return response()->json(['id'=>$request->id, 'action'=>$request->action]);
        } else if ($request->action == 'restore') {
            $bankstatements = BankStatement::where('bank_id', $request->id)
                ->restore();
            return response()->json(['id'=>$request->id, 'action'=>$request->action]);
        } else {
            return 'wa';
        }

    }

}
