<?php

namespace App\Http\Controllers\Admin;

use App\BankNo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BankcodesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;
        $countall = BankNo::all()->count();
        $counttrash = BankNo::onlyTrashed()->count();
        $title = "Bank Reconciliation System - Bank Codes";
        $pagetitle = "Bank Codes";
        $doctitle = "Bank Codes";
        $date = BankNo::groupBy('updated_at')
            ->get(['updated_at']);
        if ($request->p == 'trash') {
            $codes = BankNo::onlyTrashed()
                ->get();
            $template = 'trash';
        } else {
            $codes = BankNo::all();
            $template = 'all';
        }
        return view('admin.bankcode.index', compact('codes', 'title', 'pagetitle', 'countall', 'date', 'template', 'counttrash', 'doctitle', 'login_user_firstname'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $login_userid = Auth::id();
        $validator = Validator::make($request->all(), [
            'bankcode' => 'required|unique:bankno,bankno'
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'error','mes'=>$validator->errors()->first(),'action'=>'add']);
        }

        $bankcode = new BankNo;
        $bankcode->bankno = strtoupper($request->bankcode);
        $bankcode->added_by = $login_userid;
        $bankcode->modified_by = $login_userid;
        $bankcode->save();
        $response = [
            'id'        => $bankcode->id,
            'bankno'    => $bankcode->bankno,
            'added_by'  => $bankcode->user1->firstname . ' ' . $bankcode->user1->lastname,
            'created_at'=> $bankcode->created_at->format('F d, Y'),
            'updated_at'=> $bankcode->updated_at->format('F d, Y'),
            'editroute' => route("bankcodes.edit",$bankcode->id),
            'deleteroute'=> route("bankcodes.destroy",$bankcode->id)
        ];
        return response($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bankcode = BankNo::find($id);
        return response($bankcode);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->action == 'restore') {
            $code = BankNo::withTrashed()
                ->where('id', $id)
                ->restore();
            return response()->json(['id'=>$id]);
        }
        $login_userid = Auth::id();
        $validator = Validator::make($request->all(), [
            'bankcode' => 'required|unique:bankno,bankno,'.$id
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'error','mes'=>$validator->errors()->first(),'action'=>'add']);
        }
        try {
            $bankcode = BankNo::find($id);
            $bankcode->bankno = strtoupper($request->bankcode);
            $bankcode->modified_by = $login_userid;
            $bankcode->save();
            $response = [
                'id'        => $bankcode->id,
                'bankno'    => $bankcode->bankno,
                'added_by'  => $bankcode->user1->firstname . ' ' . $bankcode->user1->lastname,
                'created_at'=> $bankcode->created_at->format('F d, Y'),
                'modified_by'=> $bankcode->user2->firstname . ' ' . $bankcode->user2->lastname,
                'updated_at'=> $bankcode->updated_at->format('F d, Y'),
            ];
            return response($response);
        }catch (Exception $e) {
            dd($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        try {
            if ( $request->action== 'trash') {
                $code = BankNo::findOrFail($id);
                $code->delete();
                return response()->json(['id'=>$id]);
            }
            $code = BankNo::withTrashed()
                ->where('id', $id)
                ->forceDelete();
            return response()->json(['id'=>$id]);
        }catch (Exception $e) {
            dd($e);
        }
    }

    public function selectedAction(Request $request) {
        $data = json_decode($request->data);
        switch ($request->action) {
            case 'trash':
                $code = BankNo::destroy($data);
                return response($code);
            case 'delete':
                foreach ($data as $ids) {
                    $code = BankNo::withTrashed()
                        ->where('id', $ids)
                        ->forceDelete();
                }
                return response($data);
            case 'restore':
                foreach ($data as $ids) {
                    $code = BankNo::withTrashed()
                        ->where('id', $ids)
                        ->restore();
                }
                return response($data);
            default:
                break;
        }
    }
}
