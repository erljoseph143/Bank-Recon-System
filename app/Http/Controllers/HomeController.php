<?php

namespace App\Http\Controllers;

use App\BankStatement;
use App\Businessunit;
use App\Cashlog;
use App\CashLogBook;
use App\CashPullOut;
use App\Checkingaccounts;
use App\Company;
use App\PdcLine;
use App\Purpose;
use App\TCPOF;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $auth  = Auth::user()->usertype->user_type_name;
        $comId = Auth::user()->company_id;
        $buId  = Auth::user()->bunitid;
        $ses   = Auth::user();
        if($auth == "Admin")
        {
            return redirect()->route('adminhome');
        }
        elseif (strtolower($auth) === 'designex') {
            return redirect()->route('designex.dashboard');
        }
        // else if ($auth == 'Accounting-colonnade') {

            // $login_user = Auth::user();

            // $login_user_firstname = $login_user->firstname;
            // $login_user_lastname = $login_user->lastname;

            // $login_user_type = Usertype::select('user_type_name')
                // ->where('user_type_id', $login_user->privilege)
                // ->first();

            // $title = "Bank Reconciliation System - Colonnade Accounting";
            // $pagetitle = "Dashboard";
            // $userid = 1;

            // $created = $login_user->created_at;

            // return view('colacct.home', compact('title', 'pagetitle', 'users', 'users_percent', 'bs', 'bs_percent', 'dis', 'dis_percent', 'check', 'check_percent', 'login_user', 'userid', 'login_user_type', 'created'));
        // }
        elseif(strtolower($auth) =="uploader")
        {
	        $com = "CV & Deposit";
	        $bu  = "Uploader";
	        return view('CV.masterCV',compact('auth','com','bu'));
        }
		
		elseif($auth=="Treasury" )
        {
            $title         = 'Logbook - Home';
            $ptitle        = 'Home';
	        $content_title = "Monthly List for Cash to be Deposit";
            $login_user    = Auth::user();
	        $month         = CashLogBook::select('sales_date')
					        ->where('company',$login_user->company_id)
					        ->where('bu_unit',$login_user->bunitid)
					        ->where('company',$login_user->company_id)
					        ->where('bu_unit',$login_user->bunitid)
					        ->where('deposit_date',null)
							->groupBy(DB::raw('MONTH(sales_date)'))
					        ->orderBy('sales_date', 'DESC')
		                    ->get();
					        //->paginate(10);
	        return view('treasury.home',compact('month','login_user','content_title'));
        }
        elseif($auth=="Liquidation clerk")
        {
	        $login_user = Auth::user();
	        $cashlog    = Auth::user()->cash_log;
	        $exp        = explode("|",$cashlog);
	        $cashList   = Array();
	        foreach($exp as $key => $val)
	        {
		        $cash       = Cashlog::find($val)->description;
		        $cashList[] = [$val,$cash];
	        }
	        $content_title  = "Cash Receive for ".date("F j, Y");
	        return view('liquidation.home',compact('content_title','cashList','login_user'));
        }
        elseif(strtolower($auth)=='cash pull out')
        {
        	$home          = 'home';
			$content_title = "Cash Pull Out";
	        $purpose       = Purpose::pluck('description','id')->all();
	        $tcpof         = TCPOF::where('company',$comId)
				                   ->where('bu_unit',$buId)
					               ->orderBy('id', 'desc')
					               ->first();
								   //dd($tcpof);
	        if($tcpof!=null)
	        {
		        if(count($tcpof->get())>0)
		        {
			        $tcpof_no = $tcpof->toArray()['tcpof_no'];
			        $tnum     = substr_count($tcpof_no,'0');
			        $tcpof_no = $tcpof_no + 1;
			        $tzero    = "";
			        for($x=1;$x<=$tnum;$x++)
			        {
				        $tzero .= '0';
			        }
			        $tcpof_no = $tzero.$tcpof_no;
		        }
		        else
		        {
			        $tcpof_no = '0001';
		        }
	        }
	        else
	        {
		        $tcpof_no = '0001';
	        }

          
            return view('CPO.borrower.home',compact('auth','comId', 'buId', 'purpose','ses','tcpof_no','content_title','home'));
        }
        elseif(strtolower($auth)=='mall manager')
        {
	        $page   = 'dashboard';
	        $title  = 'Logbook - Dashboard';
	        $ptitle = 'Dashboard';
	        $home   = 'home';
	        $content_title = 'Cash Pull Out Approving';
	        $com    = Auth::user()->company_id;
	        $bu     = Auth::user()->bunitid;
	        $cpo    = CashPullOut::where('bu_unit',$bu)
		            ->where('cpo_status','pending')
		            ->where('company',$com)
		            ->orderBy('pull_out_date','desc')
		            ->get();
	       // dd($cpo);
            //return view('cashpullout.approver.home',compact('auth','ses', 'ptitle', 'title', 'page','cpo'));
            return view('CPO.approver.home',compact('auth','ses','cpo','content_title','home'));
        }
        elseif(strtolower($auth)=='incorporator')
        {
//	        $page   = 'dashboard';
//	        $title  = 'Logbook - Dashboard';
//	        $ptitle = 'Dashboard';
//	        $com    = Company::pluck('company','company_code')->all();
//	        return view('cashpullout.layouts.approver',compact('com','auth','ses', 'ptitle', 'title', 'page'));
	        $page   = 'dashboard';
	        $title  = 'Logbook - Dashboard';
	        $ptitle = 'Dashboard';
	        $com    = Auth::user()->company_id;
	        $bu     = Auth::user()->bunitid;
	        $cpo    = CashPullOut::where('bu_unit',$bu)
		        ->where('company',$com)
		        ->orderBy('pull_out_date','desc')
		        ->get();
	        // dd($cpo);
	        $com    = Company::pluck('company','company_code')->all();
	        return view('cashpullout.approver.home',compact('auth','ses', 'ptitle', 'title', 'page','cpo','com'));
     
        }
        elseif(strtolower($auth)=='cash pull out payment')
        {
	        $page          = 'dashboard';
	        $title         = 'Logbook - Dashboard';
	        $ptitle        = 'Dashboard';
	        $com           = Auth::user()->company_id;
	        $bu            = Auth::user()->bunitid;
	        $cpo           = CashPullOut::where('company',$com)
						                ->where('bu_unit',$bu)
							            ->where('cpo_status','Approve')
							            ->where('release_by','!=','')
							            ->orderBy('pull_out_date','desc')
						                ->get();
	        $home          = 'Home';
	        $content_title = 'Cash Pull Out Payment';
	       // return view('cashpullout.payment.home',compact('page','title','ptitle','auth','ses','cpo'));
	        return view('CPO.payment.home',compact('page','title','ptitle','auth','ses','cpo','content_title','home'));
        }
        elseif($auth=="Data Admin")
        {
	        $com = Company::pluck('company','company_code')->all();
	        return view('data.home',compact('com'));
        }
		elseif(strtolower($auth) == 'finance')
		{
			$allBU         = Businessunit::pluck('bname','unitid')->all();
			$content_title = 'Daily Transaction Record';
			return view('DTR.finance.home',compact('content_title','allBU'));
		}
        else
        {
           // session()->flush();

            if(strtolower($auth) !="rms")
            {
                //echo $auth;
                $b   = \App\Businessunit::findOrFail($buId);
                $com = $b->company->company;
                $bu  = $b->bname;
	            return view('accounting.home',compact('auth','com','bu'));
            }
            else
            {
                $com = "Record";
                $bu  = "Management";
                $bUnits = Businessunit::all();
                $flag   = 1;
	            return view('rms.home',compact('auth','com','bu','bUnits','flag'));
            }
           // return view('accounting.home',compact('auth','com','bu'));
        }
    }
}
