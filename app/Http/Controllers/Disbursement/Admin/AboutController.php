<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function about() {

        $title = 'Bank Reconciliation System - About Us';
        $pagetitle = 'About Us';

        return view('admin.about.index', compact('title', 'pagetitle'));

    }
}
