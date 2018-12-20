<?php

namespace App\Http\Controllers\Designex;

use App\DxAccount;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    //
    public function index(Request $request) {
        $title = 'Bank Reconciliation System - Designex - Accounting - File Settings Accounts';
        $ptitle = 'account';

        $accounts = DxAccount::orderBy('updated_at', 'DESC')
            ->paginate(8);
        if ($request->has('search')) {
            $accounts = DxAccount::filter([$request->plradio, $request->search, 'updated_at'])->paginate(8);
        }

        if ($request->ajax()) {
            return response()->json($this->ajaxload($accounts));
        }

        return view('designex.file-settings.accounts.index', compact('title', 'ptitle', 'accounts'));

    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'row.data.account_code' => 'required',
            'row.data.account_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'error','mes'=>$validator->errors()->first(),'action'=>'add']);
        }

        try {

            DxAccount::create($request->row['data']);
            $accounts = DxAccount::orderBy('updated_at', 'DESC')->paginate(8);

            return response()->json($this->ajaxload($accounts));

        } catch (QueryException $e) {
            return response()->json(['status'=>'error', 'mes' => $e->errorInfo[2], 'action' => 'add']);
        }
    }

    protected function ajaxload($accounts) {
        $data = view('designex.file-settings.accounts.load', compact('accounts'))->render();
        $pagination = view('designex.file-settings.accounts.pagination', compact('accounts'))->render();
        return ['data'=>$data, 'pagination'=>$pagination, 'from'=>number_format($accounts->firstItem()), 'to'=>number_format($accounts->lastItem()), 'total'=>number_format($accounts->total())];
    }

}
