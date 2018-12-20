<?php

namespace App\Http\Controllers\Admin;

use App\AdjustmentLogs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdjustmentLogsController extends Controller
{
    //
    public function index() {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;

        $pagetitle = 'User Adjustment Logs';

        $logs = AdjustmentLogs::orderBy('adj_log_id', 'DESC')
            ->get();

        //$checklogs = LogbookCheckLabel::all();

        $title = 'Bank Reconciliation System - Admin - Adjustment Logs';

        return view('admin.adjustment.index', compact('pagetitle', 'login_user_firstname', 'title', 'doctitle', 'logs', 'users'));

    }

    public function postLogs(Request $request) {

        $login_user = Auth::user();

        $validator = Validator::make($request->all(), [
            'adjlogdesc' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'error','mes'=>$validator->errors()->first(),'action'=>'add']);
        }

        $adjlogs = new AdjustmentLogs();

        $adjlogs->description = strtoupper($request->adjlogdesc);
        $adjlogs->created_by = $login_user->user_id;
        $adjlogs->updated_by = $login_user->user_id;
        $adjlogs->save();

        return response()->json(['status'=>'success','mes'=>$adjlogs,'action'=>'add']);
    }
}
