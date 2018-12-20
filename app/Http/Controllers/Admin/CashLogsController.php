<?php

namespace App\Http\Controllers\Admin;

use App\Cashlog;
use App\DxTransaction;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CashLogsController extends Controller
{
    //
    public function index() {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;

        $pagetitle = 'User Cash Logs';
        $doctitle = $pagetitle;
        $title = 'Bank Reconciliation System - Admin - Cash Logs';

//        $cashlogs = Cashlog::select('id', 'description', 'cash_status')
//            ->orderBy('id')
//            ->get();
        $cashlogs = Cashlog::orderBy('id')
            ->get();

        //Get only treasury users
        $users = User::select('user_id', 'firstname', 'lastname', 'cash_log')
            ->where('privilege',11)
            ->get();

        return view('admin.cashlog.view', compact('pagetitle', 'login_user_firstname', 'title', 'doctitle', 'cashlogs', 'users'));

    }

    public function postAjax(Request $request) {

        try {

            $stringdata = '';

            $arrdatas = [];

//            if (isset($request->cashlog1)) {
//                $arrdatas[] = 1;
//            }
//            if (isset($request->cashlog2)) {
//                $arrdatas[] = 2;
//            }
//            if (isset($request->cashlog3)) {
//                $arrdatas[] = 3;
//            }
            $checkids = explode(',',$request->checkIDs);

            //return $checkids;

            foreach ($checkids as $checkid) {

                $arrdatas[] = $checkid;

            }

            $countarrdata = count($arrdatas);

            foreach ($arrdatas as $key => $arrdata) {

                if ($countarrdata-1 == $key) {
                    $stringdata .= $arrdata;
                } else {
                    $stringdata .= $arrdata.'|';
                }

            }

            $user = User::find($request->user);
            $user->cash_log = $stringdata;
            $user->save();
            return response()->json(['mes' => 'success']);
        }catch (Exception $e) {
            dd($e);
        }

    }

    public function postLogs(Request $request) {

        $validator = Validator::make($request->all(), [
            'cashlogdesc' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'error','mes'=>$validator->errors()->first(),'action'=>'add']);
        }

        $cashlogs = new Cashlog();

        $cashlogs->description = strtoupper($request->cashlogdesc);
        $cashlogs->save();

        return response()->json(['status'=>'success','mes'=>'success','action'=>'add']);

    }

    public function update(Request $request, $id) {

        Cashlog::where('id', $id)
            ->update(['cash_status' => $request->status]);
        return $id;
    }

}