<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CustomLoginController extends Controller
{
    //
    public function customLogin(Request $request)
    {
      $this->validate($request,['username'=>'required','password'=>'required']);

        if(Auth::attempt(['username'=>$request->username,'password'=>$request->password]))
        {
//            $user = User::whereUsername($request->username)->get()->all();
//           // dd($user);
//            //var_dump($user);
//
//            foreach($user as $users)
//            {
//                return 'login successfully' . $users->firstname;
//            }
                return view('accounting.home');
        }

        return 'something wrong happens';

    }

    public function showLogin()
    {
        return view('admin.login');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();

        return redirect('/');
    }

}
