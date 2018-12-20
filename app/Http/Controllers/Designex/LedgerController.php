<?php

namespace App\Http\Controllers\Designex;

use App\DxLedger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LedgerController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    //
    public function index(Request $request) {
        $title = 'Bank Reconciliation System - Designex - Accounting - File Settings Ledgers';
        $ptitle = 'ledger';

        $ledgers = DxLedger::orderBy('updated_at', 'DESC')->paginate(8);
        if ($request->has('search')) {
            $ledgers = DxLedger::filter([$request->plradio, $request->search, 'updated_at'])->paginate(8);
        }

        if ($request->ajax()) {
            return response()->json($this->ajaxload($ledgers));
        }

        return view('designex.file-settings.ledger.index', compact('title', 'ptitle', 'ledgers'));
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'row.data.ledger_code' => 'required',
            'row.data.ledger_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'error','mes'=>$validator->errors()->first(),'action'=>'add']);
        }

        DxLedger::create($request->row['data']);
        $ledgers = DxLedger::orderBy('updated_at', 'DESC')->paginate(8);

        return response()->json($this->ajaxload($ledgers));
    }

    protected function ajaxload($ledgers) {
        $data = view('designex.file-settings.ledger.load', compact('ledgers'))->render();
        $pagination = view('designex.file-settings.ledger.pagination', compact('ledgers'))->render();
        return ['data'=>$data, 'pagination'=>$pagination, 'from'=>number_format($ledgers->firstItem()), 'to'=>number_format($ledgers->lastItem()), 'total'=>number_format($ledgers->total())];
    }

    public function update(Request $request, $id) {

        $update = DxLedger::find($id)->update($request->row['data']);

        return $request->row['data'];
    }
}