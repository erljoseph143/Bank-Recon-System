<?php

namespace App\Http\Controllers\Admin;

use App\BankAccount;
use App\BankStatement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SettingBankStatementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function getBankStatementsnoBU(Request $request) {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;

        $title = "BRS Admin - Tools - General - Bank statements with no bu and company";
        $pagetitle = "List of bank statements with no business unit and company";

        $qall = BankStatement::noBU();

        $qtrash = BankStatement::noBU()
            ->onlyTrashed();

        $countall = $qall->count();
        $counttrash = $qtrash->count();

        if ($request->p == 'trash') {
            $bankstatements = $qtrash->get();

            $template = 'trash';
        } else {
            $bankstatements = $qall->get();
            $template = 'all';
        }

        return view('admin.settings.bs-no-bu.index', compact('title', 'login_user_firstname', 'pagetitle', 'bankstatements', 'template', 'countall', 'counttrash'));

    }

    public function getBankStatementDuplicateCount() {

        $countduplicatebs = BankStatement::select('bank_date', 'description', 'bank_check_no', 'bank_amount', 'bank_balance', 'bank_account_no', DB::raw('COUNT(*) as duplicate_count'))
            ->withTrashed()
            ->groupBy('bank_date', 'description', 'bank_check_no', 'bank_amount', 'bank_balance', 'bank_account_no')
            ->havingRaw('COUNT(*) > ?', [1])
            ->get()
            ->count();

        return $countduplicatebs;

    }

    public function getBankStatementsnoBUCount() {

        $count = BankStatement::noBU()
            ->count();

        return $count;

    }

    public function getBankAccountLists(Request $request) {

        $bank_accounts = BankAccount::whereRaw("bankno = (SELECT id FROM bankno WHERE bankno='{$request->code}')")
            ->get();

        return view('admin.settings.bs-no-bu.viewbankaccount', compact('bank_accounts'));

    }

    public function trashBankStatementsnoBU(Request $request) {

        if ($request->id) {
            if ($request->action=="trash") {
                $data = $request->id;
                $numdata = array_map('intval', $data);
                BankStatement::destroy($numdata);
            } elseif ($request->action=="delete") {
                foreach ($request->id as $ids) {
                    $code = BankStatement::withTrashed()
                        ->where('bank_id', $ids)
                        ->forceDelete();
                }
            }
        }

//        $test = array_map('intval', explode(',', '23434'));
//        $code->delete();
//        return response()->json(['id'=>$id]);

//        return $numdata;
        return $request->id;
    }

}
