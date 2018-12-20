<?php

namespace App\Http\Controllers\Designex;

use App\DxSubsidiaryLedger;
use App\DxTransaction;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GeneralSettingController extends Controller
{
    //
    public function index() {

        $title = "BRS - Designex - Accounting General Settings";
        $ptitle = "general-setting";

//        return DesignexSubsidiaryLedger::high()->get();
        return view('designex.general-settings.index', compact('title', 'ptitle'));
    }
}
