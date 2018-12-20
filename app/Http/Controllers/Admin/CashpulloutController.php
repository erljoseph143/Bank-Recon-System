<?php

namespace App\Http\Controllers\Admin;

use App\Purpose;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CashpulloutController extends Controller
{
    //
    public function index(Request $request) {
        $title = "Bank Reconciliation System - Admin | Setup Cash Pull Out";
        $login_user_firstname = Auth::user()->firstname;
        $pagetitle = "Setup Cash Pull Out Purpose";

        $purposes = Purpose::paginate(8);

        if ($request->ajax()) {
            if ($request->has('page')) {
                $data = view('admin.cashpullout.load', compact('purposes'))->render();
                $pagination = view('admin.cashpullout.pagination', compact('purposes'))->render();

                return response()->json(['data'=>$data, 'pagination'=>$pagination]);
            }
            $validator = Validator::make($request->all(), [
                'data.*.description' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status'=>'error','mes'=>$validator->errors()->first(),'action'=>'add']);
            }
            Purpose::create($request->data[0]);
            return response()->json(['status'=>'success', 'mes'=>'Successfully saved', 'action'=>'add']);
        }

        return view('admin.cashpullout.index', compact('title', 'login_user_firstname', 'pagetitle', 'purposes'));
    }

}
