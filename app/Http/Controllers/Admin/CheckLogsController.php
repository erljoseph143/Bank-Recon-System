<?php

namespace App\Http\Controllers\Admin;

use App\LogbookCheckLabel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CheckLogsController extends Controller
{
    //
    public function index(Request $request) {
        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;

        $pagetitle = 'User Check Logs';

        $checklogs = LogbookCheckLabel::orderBy('label_id', "DESC")
            ->get();

        $title = 'Bank Reconciliation System - Admin - Check Logs';

        return view('admin.checklog.index', compact('pagetitle', 'login_user_firstname', 'title', 'doctitle', 'checklogs', 'users'));
    }

    public function postReq(Request $request) {

        if ($request->action == 'savechecklog') {

            $validator = Validator::make($request->all(), [
                'checklogdesc' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status'=>'error','mes'=>$validator->errors()->first(),'action'=>'add']);
            }

            $checklogs = new LogbookCheckLabel();

            $checklogs->description = strtoupper($request->checklogdesc);
            $checklogs->save();

            return response()->json(['status'=>'success','mes'=>$checklogs,'action'=>'add']);

        }

    }
}
