<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //
	public function index()
	{
		$users = User::select('user_id','firstname','lastname','email')->orderBy('user_id','desc')->paginate(5);
		return response()->json($users);
	}
	
	public function destroy($id)
	{
		$user = User::find($id);
		$user->delete();
		
		return response()->json('User Deleted');
	}
	
	public function store(Request $request)
	{
		$this->validate($request,[
			"firstname" => "required",
			"lastname"  => "required",
			"email"     => "required|email|unique:users",
			"password"  => "required|min:6"
		]);
		
		$user = new User();
		$user->firstname = $request->get('firstname');
		$user->lastname  = $request->get('lastname');
		$user->email     = $request->get('email');
		$user->password  = bcrypt($request->get('password'));
		$user->username  = "";
		$user->save();
		return response()->json($user);
	}
	
	public function show($id)
	{
		$user = User::find($id);
		
		return response()->json($user);
	}
	
	public function update($id, Request $request)
	{
		$user  = User::find($id);
		$user->firstname = $request->get('firstname');
		$user->lastname  = $request->get('lastname');
		$user->email     = $request->get('email');
		
		$user->update();
		
		return response()->json($user);
	}
}
