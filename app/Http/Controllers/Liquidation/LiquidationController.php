<?php

namespace App\Http\Controllers\Liquidation;

use App\Cashlog;
use App\CashLogBook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LiquidationController extends Controller
{
    //
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function addCash()
	{
		$cashlog = Auth::user()->cash_log;
		$exp     = explode("|",$cashlog);
		$cashList = Array();
		foreach($exp as $key => $val)
		{
			$cash = Cashlog::find($val)->description;
			$cashList[] = [$val,$cash];
		}
		$content_title = "Cash Receive for ".date("F j, Y");
		return view('liquidation.addCash',compact('content_title','cashList'));
	}
	
	public function saveCash(Request $request)
	{
		$cash = CashLogBook::where('sales_date',date('Y-m-d'))->exists();
		if(!$cash):
			foreach($request->cash as $key =>$amount)
			{
				$data = [
					'sales_date'=>date("Y-m-d"),
					'amount'=>str_replace(",","",$amount),
					'amount_edited'=>str_replace(",","",$amount),
					'ds_no'=>$request->ds_no[$key],
					'ar_from'=>$request->fromAR[$key],
					'ar_to'=>$request->toAR[$key],
					'cash_id'=>$request->cash_log[$key],
					'company'=>Auth::user()->company_id,
					'bu_unit'=>Auth::user()->bunitid
				];
				CashlogBook::updateOrCreate($data);
			}
		else:
			echo "You Already Submitted cash for ". date('F d, Y');
		endif;
	}
	
	public function monthlyCash()
	{
		$com  = Auth::user()->company_id;
		$bu   = Auth::user()->bunitid;
		$month = CashLogBook::select(DB::raw('distinct(DATE_FORMAT(sales_date,"%Y-%m")) as datein'))
			->where('company',$com)
			->where('bu_unit',$bu)
			->orderBy('sales_date','DESC')
			->get();
		$content_title = "Monthly List of Cash Receive";
		return view('liquidation.monthlyCash',compact('month','content_title'));
	}
	
	public function dailyCash($date)
	{
		$com  = Auth::user()->company_id;
		$bu   = Auth::user()->bunitid;
		$month = date("m",strtotime($date));
		$year  = date("Y",strtotime($date));
		$daily = CashLogBook::whereMonth('sales_date',$month)
			->whereYear('sales_date',$year)
			->orderBy('sales_date','DESC')
			->distinct()->get(['sales_date as datein']);
		$content_title = "Daily list of Cash Receive as of " . date("F, Y",strtotime($date));
		return view('liquidation.dailyCash',compact('daily','content_title'));
	}
	
	public function viewCash($date)
	{
		
		$com  = Auth::user()->company_id;
		$bu   = Auth::user()->bunitid;
		$auth = Auth::user();
		$cashLog = CashLogBook::where('company',$com)
			->where('bu_unit',$bu)
			->where('sales_date',$date)
			->get();
		$content_title = "Cash Receive as of " . date("F j, Y",strtotime($date));
		return view('liquidation.viewCash',compact('cashLog','date','auth','content_title'));
	}
	
	public function saveEdit(Request $request)
	{
		CashLogBook::where('id',$request->pk)->update(['amount_edited'=>str_replace(",","",$request->value)]);
	}
	
	public function postingData($date)
	{
		CashLogBook::where('sales_date',$date)->update(['status_clerk'=>'posted']);
	}
}
