<?php

namespace App\Http\Controllers\Admin;

use App\BankStatement;
use App\Checkingaccounts;
use App\Http\Controllers\Controller;
use App\PdcLine;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function dashboard() {

        $login_user = Auth::user();

        $login_user_firstname = $login_user->firstname;
        $login_user_lastname = $login_user->lastname;

        //Users query
        $users_active = User::select(DB::raw('COUNT(*) as count'))
            ->where('status', 'a')
            ->first();
        $users = User::select(DB::raw('COUNT(*) as count'))
            ->first();
        $user_active_percent = (100/$users->count)*$users_active->count;
        $users_percent = ceil($user_active_percent);

        //Bank statement query
        $bs_active = BankStatement::select(DB::raw('COUNT(*) as count'))
            ->where('label_match', 'match check')
            ->first();
        $bs = BankStatement::select(DB::raw('COUNT(*) as count'))
            ->first();
        $bs_active_percent = (100/$bs->count)*$bs_active->count;
        $bs_percent = ceil($bs_active_percent);

        //Disbursement query
        $dis_active = PdcLine::select(DB::raw('COUNT(*) as count'))
            ->where('label_match', 'match check')
            ->first();
        $dis = PdcLine::select(DB::raw('COUNT(*) as count'))
            ->first();
        $dis_active_percent = (100/$dis->count)*$dis_active->count;
        $dis_percent = ceil($dis_active_percent);

        //Checks query
        $check_active = Checkingaccounts::select(DB::raw('COUNT(*) as count'))
            ->where('match_type', 'match check')
            ->first();
        $check = Checkingaccounts::select(DB::raw('COUNT(*) as count'))
            ->first();
        $check_active_percent = (100/$check->count)*$check_active->count;
        $check_percent = ceil($check_active_percent);

        $title = "Bank Reconciliation System - Dashboard";
        $pagetitle = "Dashboard";

        return view('admin.home', compact('title', 'pagetitle', 'users', 'users_percent', 'bs', 'bs_percent', 'dis', 'dis_percent', 'check', 'check_percent', 'login_user_firstname'));

    }

}