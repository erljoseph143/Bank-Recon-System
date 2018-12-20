<?php

namespace App\Http\Controllers\Designex;

use App\DxLedger;
use App\DxTransactionType;
use App\DxTT;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FileSettingsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    //
    public function index(Request $request) {

        $title = "Designex file settings";
        $ptitle = 'file-setting';

        $trans_types = DxTT::orderBy('updated_at', 'DESC')->paginate(8);
        if ($request->action == 'search') {
            $trans_types = DxTT::filter([
                $request->plradio,
                $request->search,
                'updated_at'
            ])->paginate(8);
        }

        if ($request->ajax()) {
            return response()
                ->json($this->ajaxload($trans_types));
        }

        return view('designex.file-settings.index', compact('title', 'ptitle', 'trans_types'));
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'row.data.code' => 'required',
            'row.data.name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'=>'error',
                'mes'=>$validator->errors()->first(),
                'action'=>'add']);
        }

        DxTT::create($request->row['data']);
        $trans_types = DxTT::orderBy('updated_at', 'DESC')
            ->paginate(8);

        return response()->json($this->ajaxload($trans_types));
    }

    protected function ajaxload($trans_types) {
        $data = view('designex.file-settings.load', compact('trans_types'))
            ->render();
        $pagination = view('designex.file-settings.pagination', compact('trans_types'))
            ->render();
        return [
            'data'=>$data,
            'pagination'=>$pagination,
            'from'=>$trans_types->firstItem(),
            'to'=>$trans_types->lastItem(),
            'total'=>$trans_types->total()
        ];
    }
}
