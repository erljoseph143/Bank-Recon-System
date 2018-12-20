<?php

namespace App\Http\Controllers\Designex;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index() {
        $login_user = Auth::user();
        $title = "Designex Profile";
        $ptitle = "profile";
        return view('designex.user.profile', compact('title', 'login_user', 'ptitle'));
    }
    public function update(Request $request) {
        $login_user_id = Auth::id();
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username,'.$login_user_id.',user_id',
            'email' => 'required|unique:users,email,'.$login_user_id.',user_id',
        ]);
//
        if ($validator->fails()) {
            return response()->json(['status'=>'error','mes'=>$validator->errors()->first(),'action'=>'add']);
        }
        $data = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'contact' => $request->contact,
            'gender' => $request->gender,
            'location' => $request->location,
            'about' => $request->about,
        ];
        $user = User::where('user_id', $login_user_id)
            ->update($data);
        return response()->json($data);
    }
}
