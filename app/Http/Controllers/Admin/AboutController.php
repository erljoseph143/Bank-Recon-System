<?php

namespace App\Http\Controllers\Admin;

use App\Functions\ProfileTiles;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AboutController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function about() {

        $login_user = Auth::user();

        $login_user_firstname = $login_user->firstname;
        $login_user_lastname = $login_user->lastname;

        $title = 'Bank Reconciliation System - About Us';
        $pagetitle = 'About Us';

        $tiles = new ProfileTiles();

        $person1 = $tiles->queryTeam( url('admin/minton/assets/images/users/avatar-4.jpg'), 'Jelarry Cadutdut', 'Programmer', '(0921-4212-321)', 'cadutdutjedd@gmail.com', 'Bohol PH' );
        $person2 = $tiles->queryTeam( url('admin/minton/assets/images/users/avatar-7.jpg'), 'Rey Joseph Baay', 'Programmer', '(0935-4119-353)', 'reybaay@gmail.com', 'Bohol PH' );
        $person3 = $tiles->queryTeam( url('admin/minton/assets/images/users/avatar-3.jpg'), 'Glenn Michael Mejias', 'Programmer', '(0910-0368-184)', 'saijemikey12@gmail.com', 'Bohol PH' );
        $person4 = $tiles->queryTeam( url('admin/minton/assets/images/small/img2.jpg'), 'Helen Lumod', 'System Analyst', '(0910-2932-021)', 'helenlumod@gmail.com', 'Bohol PH' );

        return view('admin.about.index', compact('title', 'pagetitle', 'login_user_firstname', 'person1', 'person2', 'person3', 'person4'));

    }
}
