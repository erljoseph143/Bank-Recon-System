<?php

namespace App\Http\Controllers\Designex;

use App\DxTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProoflistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index(Request $request) {
        $title = 'Designex Prooflists';
        $ptitle = 'prooflist';

        $banks = json_decode($request->banks);

        if (empty($request->fields) && empty($banks) && empty($request->date)) {
            $checkbanks = DxTransaction::where('check_bank', '!=', '')
                ->groupBy('check_bank')
                ->get(['check_bank']);

            $prooflists = DxTransaction::paginate(12,['check_date', 'check_bank', 'doc_no', 'doc_date', 'payee', 'amount', 'check_no'],'page');
        } else {
            $date = strtotime($request->date);
            $year = date('Y', $date);
            $month = date('m', $date);


            if ($request->fields && !empty($banks)) {
                $prooflists = DxTransaction::whereIn('check_bank', $banks)
                    ->whereMonth('doc_date', $month)
                    ->whereYear('doc_date', $year)
                    ->where($request->plradio, 'LIKE','%'.$request->fields.'%')
                    ->paginate(12,['check_date', 'check_bank', 'doc_no', 'doc_date', 'payee', 'amount', 'check_no']);
            } elseif ($request->fields) {
                $prooflists = DxTransaction::where($request->plradio, 'LIKE','%'.$request->fields.'%')
                    ->paginate(12,['check_date', 'check_bank', 'doc_no', 'doc_date', 'payee', 'amount', 'check_no']);
            } else {
                $prooflists = DxTransaction::whereIn('check_bank', $banks)
                    ->whereMonth('doc_date', $month)
                    ->whereYear('doc_date', $year)
                    ->paginate(12,['check_date', 'check_bank', 'doc_no', 'doc_date', 'payee', 'amount', 'check_no']);
            }
        }

        if ($request->ajax()) {
            $data = view('designex.prooflist.load', compact('prooflists'))->render();
            $pagination = view('designex.prooflist.pagination', compact('prooflists'))->render();

            return response()->json([
                'data'=>$data,
                'pagination'=>$pagination,
                'from'=>number_format($prooflists->firstItem()),
                'to'=>number_format($prooflists->lastItem()),
                'total'=>number_format($prooflists->total())
            ]);
        }
        return view('designex.prooflist.bank', compact('title', 'ptitle', 'prooflists', 'checkbanks'));
    }
    public function search(Request $request) {
        if ($request->has('key')) {
            if ($request->key == 'banks') {
                $banks = DxTransaction::groupBy('check_bank')
                    ->where('check_bank', 'LIKE', '%'.$request->data.'%')
                    ->limit(10)
                    ->get(['check_bank']);
                return response()->json(['data'=>$banks]);
            }
            if ($request->key == 'date') {
                $date = DxTransaction::groupBy(DB::raw('DATE_FORMAT(doc_date,"%Y %M")'))
                    ->whereIn('check_bank', $request->data)
                    ->get([DB::raw('DATE_FORMAT(doc_date,"%Y %M") as docdate')]);

                return response()->json(['data'=>$date]);
            }
        }
    }
}