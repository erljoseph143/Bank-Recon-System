<?php

namespace App\Http\Controllers\Designex;

use App\DxSubsidiaryLedger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SLController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index(Request $request) {
        $title = "Designex Subsidiary ledgers";
        $ptitle = 'sl';
        $sls = DxSubsidiaryLedger::where('doc_type', 'cv')->paginate(12);

        if ($request->has('fields') && $request->has('radio')) {
            if ($request->fields != '' && $request->radio != '') {
                $fields = $request->fields;
                $radio = $request->radio;
                $sls = DxSubsidiaryLedger::where('doc_type', 'cv')->where($request->radio, 'LIKE', '%'.$request->fields.'%')->paginate(12);
            }
        }

        if ( $request->ajax() ) {

            $data = view('designex.sl.load', compact('sls'))->render();
            $pagination = view('designex.sl.pagination', compact('sls'))->render();
            return response()->json([
                'data' => $data,
                'pagination' => $pagination,
                'from'  => number_format($sls->firstItem()),
                'to'    => number_format($sls->lastItem()),
                'total' => number_format($sls->total())
            ]);
        }

        return view('designex.sl.index', compact('title', 'sls', 'fields', 'radio', 'ptitle'));
    }
}
