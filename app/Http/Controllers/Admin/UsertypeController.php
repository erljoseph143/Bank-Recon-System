<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Usertype;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UsertypeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function viewusertypes() {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;

        $title = 'Bank Reconciliation System - User types';

        $pagetitle = "User Types";

        $doctitle = "user types";

        $usertypes = Usertype::get();

        $template = 'all';

        $countall = Usertype::all()->count();

        $counttrash = Usertype::onlyTrashed()->count();


        return view('admin.usertype.view', compact('title', 'login_user_firstname', 'pagetitle', 'doctitle', 'usertypes', 'template', 'countall', 'counttrash'));

    }

    public function allTrash() {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;
        $login_user_lastname = $login_user->lastname;

        $usertypes = Usertype::onlyTrashed()
            ->get();

        $countall = Usertype::all()->count();
        $counttrash = Usertype::onlyTrashed()->count();

        $query = "
            SELECT CONCAT(MONTHNAME(updated_at), ' ', YEAR(updated_at)) as created_at
            FROM user_type
            GROUP BY YEAR(updated_at) DESC, MONTH(updated_at) DESC 
        ";

        $date = DB::select($query);

        $title = "Bank Reconciliation System - User types trash";
        $pagetitle = "User Types";
        $doctitle = "user types";

        $template = 'trash';

        return view('admin.usertype.view', compact('usertypes', 'title', 'pagetitle', 'countall', 'date', 'template', 'counttrash', 'doctitle', 'login_user_firstname'));

    }

    public function getData(Request $request) {

        return $request;

    }

    public function saveUsertype(Request $request) {

        $login_user = Auth::user();

        $validator = Validator::make($request->all(), [
            'usertype' => 'required|unique:user_type,user_type_name'
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'error','mes'=>$validator->errors()->first(),'action'=>'save']);
        }

        try {

            $usertype = new Usertype();

            $usertype->user_type_name = $request->usertype;
            $usertype->created_by = $login_user->user_id;
            $usertype->updated_by = $login_user->user_id;

            $usertype->save();

            $created = $usertype->created_at->format('F d, Y');
            $updated = $usertype->updated_at->format('F d, Y');

            $response = [
                'user_type_name' => $usertype->user_type_name,
                'created_at'  => $created,
                'created_by'  => $usertype->created_by,
                'updated_at'  => $updated,
                'updated_by'  => $usertype->updated_by,
            ];

            return response($response);

        } catch (Exception $e) {
            return 1;
        }

        return 2;

    }

    public function updateUsertype(Request $request, $id) {

        $login_user = Auth::user();

        $validator = Validator::make($request->all(), [
            'usertype' => 'required|unique:user_type,user_type_name,'.$id.',user_type_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'error','mes'=>$validator->errors()->first(),'action'=>'save']);
        }

        try {
            $usertype = Usertype::find($id);
            $usertype->user_type_name = $request->usertype;
            $usertype->updated_by = $login_user->user_id;
            $usertype->save();

            $user = User::select('firstname', 'lastname')->where('user_id', $usertype->created_by)->first();

            $user2 = User::select('firstname', 'lastname')->where('user_id', $usertype->updated_by)->first();

            $created = ($usertype->created_at)?$usertype->created_at->format('F d, Y'):"";
            $updated = $usertype->updated_at->format('F d, Y');

            if ( $usertype->created_by == 0 || is_null($usertype->created_by) || empty($usertype->created_by) ) {
                $added_by = '';
            } else {
                $added_by = $user->firstname . ' ' . $user->lastname;
            }

            $response = [
                'user_type_id'        => $usertype->user_type_id,
                'user_type_name'    => $usertype->user_type_name,
                'created_by'  => $added_by,
                'created_at'=> $created,
                'updated_by'=> $user2->firstname . ' ' . $user2->lastname,
                'updated_at'=> $updated,
            ];

            return response($response);
        } catch (\Throwable $e) {
            return response()->json(['status'=>'error','mes'=>$e->getMessage(),'action'=>'save']);
        }

    }

    public function getUsertype($id) {

        $data = json_decode($id);

        if ($data[1] == 'restore') {

            $code = Usertype::withTrashed()
                ->where('user_type_id', $data[0])
                ->restore();

            return \response($code);
        }

        $user_type = Usertype::find($data[0]);

        return response($user_type);

    }

    public function trashUsertype($id) {

        try {
            $data = json_decode($id);

            if ($data[1] == 'delete') {
                $code = Usertype::withTrashed()
                    ->where('user_type_id', $data[0])
                    ->forceDelete();

                return $code;
            }

            $code = Usertype::findOrFail($data[0]);
            $code->delete();

            return response($code);

        }catch (Exception $e) {
            dd($e);
        }

    }

}
