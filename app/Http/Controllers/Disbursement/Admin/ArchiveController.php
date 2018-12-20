<?php

namespace App\Http\Controllers\Admin;

use App\Archive;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ArchiveController extends Controller
{
    //
    public function allArchives() {

        $archives = Archive::all();
        $tables = Archive::distinct()->get(['thetable']);

        $title = "Bank Reconciliation System - Archives";
        $pagetitle = "Archives";
        $panel_title = "Lists of archives";

        return view('admin.archive.index', compact('archives', 'title', 'pagetitle', 'panel_title', 'tables'));
    }
}
