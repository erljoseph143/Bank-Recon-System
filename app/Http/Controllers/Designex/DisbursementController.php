<?php

namespace App\Http\Controllers\Designex;

use App\DxTransaction;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DisbursementController extends Controller
{
    //
    public function index(Request $request) {

//        $request->user()->authorizeRoles(['admin','finance']);
        $title = "BRS - Designex Accounting View Disbursements";
        $login_user = Auth::user();
        $ptitle = "disbursement";
        return view('designex.disbursement.index', compact('title', 'login_user', 'ptitle'));
    }

    public function all(Request $request) {

        if ($request->p == 'all') {
            $columns = [
                'doc_date',
                'doc_no',
                'payee',
                'amount',
                'check_date',
                'check_no',
                'ledger_code',
            ];
            $totalData = DxTransaction::count('id');
            $totalFiltered = $totalData;
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            if(empty($request->input('search.value'))) {
                $transactions = DxTransaction::offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
            } else {

            }
            $data = [];
            if (!empty($transactions)) {
                foreach ($transactions as $transaction) {
                    $nestedData['doc_date'] = $transaction->doc_date->format('M d Y');
                    $nestedData['doc_no'] = $transaction->doc_no;
                    $nestedData['payee'] = $transaction->payee;
                    $nestedData['amount'] = $transaction->amount;
                    $nestedData['check_date'] = $transaction->check_date->format('M d Y');
                    $nestedData['check_no'] = $transaction->check_no;
                    $nestedData['ledger_code'] = $transaction->ledger_code;
                    $data[] = $nestedData;
                }
            }
            return response()->json([
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data,
            ]);
        }
    }

}
