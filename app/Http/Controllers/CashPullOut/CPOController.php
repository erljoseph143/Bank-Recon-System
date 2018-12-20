<?php

namespace App\Http\Controllers\CashPullOut;

use App\Businessunit;
use App\CashPullOut;
use App\Company;
use App\CPOLedger;
use App\Purpose;
use App\TCPOF;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CPOController extends Controller
{
    //
	public  function __construct()
	{
		$this->middleware('auth');
	}
	
	public function CPOform()
	{
		$auth    = Auth::user()->usertype->user_type_name;
		$ses     = Auth::user();
		$buId    = Auth::user()->bunitid;
		$comId   = Auth::user()->company_id;
		$content_title = "Cash Pull Out";
		$purpose = Purpose::pluck('description','id')->all();
		$tcpof   = TCPOF::where('company',$comId)
			->where('bu_unit',$buId)
			->orderBy('id', 'desc')
			->first();
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
		return view('CPO.borrower.CPOform',compact('auth','comId', 'buId', 'ses','purpose','tcpof_no','content_title'));
	}
	
	public function saveCashPullOut(Request $request)
	{
	//requested_by	tcof_no	dep_sec	pull_out_date	amount	amt_words	purpose	approve_by	release_by	created_at	updated_at	deleted_at
		$com = Auth::user()->company_id;
		$bu  = Auth::user()->bunitid;
		$data = [
			'requested_by'=>$request->req_by,
			'tcpof_no'=>$request->tcpof,
			'dep_sec'=>$request->dep_sec,
			'pull_out_date'=>date('Y-m-d',strtotime($request->req_date)),
			'amount'=>str_replace(",","",$request->amount),
			'amount_edited'=>str_replace(",","",$request->amount),
			'amt_words'=>$request->amt_words,
			'purpose'=>$request->purpose,
			'cpo_status'=>'Pending',
			'company'=>$com,
			'bu_unit'=>$bu
		];
		
		CashPullOut::updateOrCreate($data);
		TCPOF::updateOrCreate(['tcpof_no'=>$request->tcpof,'company'=>$com,'bu_unit'=>$bu]);
	}
	
	public function viewRequest()
	{
		$com     = Auth::user()->company_id;
		$bu      = Auth::user()->bunitid;
		$user_id = Auth::user()->user_id;
		$cpo     = CashPullOut::where('requested_by',$user_id)
								->where('company',$com)
								->where('bu_unit',$bu)
								->orderBy('pull_out_date','desc')
								->get();
		return view('CPO.borrower.viewRequest',compact('cpo'));
	}
	
	public function viewApprove()
	{
		$com     = Auth::user()->company_id;
		$bu      = Auth::user()->bunitid;
		$user_id = Auth::user()->user_id;
		$cpo     = CashPullOut::where('requested_by',$user_id)
								->where('cpo_status','approve')
								->where('company',$com)
								->where('bu_unit',$bu)
								->orderBy('pull_out_date','desc')
								->get();
		return view('CPO.borrower.request.approve',compact('cpo'));
	}
	
	public function viewRelease()
	{
		$com     = Auth::user()->company_id;
		$bu      = Auth::user()->bunitid;
		$user_id = Auth::user()->user_id;
		$cpo     = CashPullOut::where('requested_by',$user_id)
								->where('cpo_status','approve')
								->where('release_status','release')
								->where('company',$com)
								->where('bu_unit',$bu)
								->orderBy('pull_out_date','desc')
								->get();
		return view('CPO.borrower.request.release',compact('cpo'));
	}
	
	public function viewPending()
	{
		$com     = Auth::user()->company_id;
		$bu      = Auth::user()->bunitid;
		$user_id = Auth::user()->user_id;
		$cpo     = CashPullOut::where('requested_by',$user_id)
			->where('cpo_status','Pending')
			->where('company',$com)
			->where('bu_unit',$bu)
			->orderBy('pull_out_date','desc')
			->get();
		return view('CPO.borrower.request.pending',compact('cpo'));
	}
	
	public function viewPaid()
	{
		$com     = Auth::user()->company_id;
		$bu      = Auth::user()->bunitid;
		$user_id = Auth::user()->user_id;
		
		$cpo     = CashPullOut::where('requested_by',$user_id)
								//->where('status_paid','Paid')
								->where('company',$com)
								->where('bu_unit',$bu)
								->orderBy('pull_out_date','desc')
								->get();
		return view('CPO.borrower.paid.cpoPaid',compact('cpo'));
	}
	
	public function viewledger($id)
	{
		$cpo_ledger = CPOLedger::where('cpo_id',$id)->get();
		return view('CPO.borrower.paid.ledger',compact('cpo_ledger'));
	}
	
	public function requestedCash()
	{
		$content_title = 'Cash Pull Out Approving';
		$com           = Auth::user()->company_id;
		$bu            = Auth::user()->bunitid;
		$user_id       = Auth::user()->user_id;
		$cpo           = CashPullOut::where('bu_unit',$bu)
									->where('cpo_status','pending')
									->where('company',$com)
									->orderBy('pull_out_date','desc')
									->get();
		return view('CPO.approver.requestedCash',compact('cpo','content_title'));
		
	}
	
	public function approve($id)
	{
		$cpo = CashPullOut::find($id);
		return view('CPO.approver.approve',compact('cpo'));
	}
	
	public function approveRequest(Request $request)
	{
		$id   = Auth::user()->user_id;
		$data = [
			'amount_edited'=>str_replace(',','',$request->amount),
			'amt_words'=>$request->amt_words,
			'cpo_status'=>'approve',
			'approve_by'=>$id
		];
		
		CashPullOut::where('id',$request->id)->update($data);
	}
	
	public function approveRequestedCash()
	{
		$content_title = 'Approved Cash Pull Out';
		$com           = Auth::user()->company_id;
		$bu            = Auth::user()->bunitid;
		$cpo           = CashPullOut::where('bu_unit',$bu)
							->where('cpo_status','approve')
							->where('company',$com)
							->orderBy('pull_out_date','desc')
							->get();
		return view('CPO.approver.approvedRequestedCash',compact('content_title','cpo'));
	}
	
	public function cpoReplenished()
	{
		$content_title = 'Replenished Cash Pull Out';
		$com           = Auth::user()->company_id;
		$bu            = Auth::user()->bunitid;
		$cpo           = CashPullOut::where('bu_unit',$bu)
									->where('cpo_status','approve')
									->where('status_paid','paid')
									->where('company',$com)
									->orderBy('pull_out_date','desc')
									->get();
		return view('CPO.approver.cashReplenished',compact('content_title','cpo'));
	}
	
	public function PrintData($id)
	{
		$cpo  = CashPullOut::find($id);
		//return view('cashpullout.approver.print',compact('cpo'));
		return view('CPO.printCPO.print',compact('cpo'));
	}
	
	public function getbu($id)
	{
		$bu = Businessunit::where('company_code',$id)->pluck('bname','unitid')->all();
		//dd($bu);
		return view('cashpullout.approver.blist',compact('bu'));
	}
	
	public function com_bu(Request $request)
	{
		$ses   = Auth::user();
		$auth  = Auth::user()->usertype->user_type_name;
		$data = ['company_id'=>$request->com,'bunitid'=>$request->bu];
		$id = Auth::user()->user_id;
		User::where('user_id',$id)
			->update($data);
		
		$page   = 'dashboard';
		$title  = 'Logbook - Dashboard';
		$ptitle = 'Dashboard';
		$com    = Auth::user()->company_id;
		$bu     = Auth::user()->bunitid;
		$cpo    = CashPullOut::where('bu_unit',$request->bu)
			->where('company',$request->com)
			->orderBy('pull_out_date','desc')
			->get();
		$com    = Company::pluck('company','company_code')->all();
		// dd($cpo);
		//return view('cashpullout.approver.home',compact('auth','ses', 'ptitle', 'title', 'page','cpo','com','ses'));
		return redirect('/home');
	}
	
	public function cpoList()
	{
		$com           = Auth::user()->company_id;
		$bu            = Auth::user()->bunitid;
		$cpo           = CashPullOut::where('company',$com)
							->where('bu_unit',$bu)
							->where('cpo_status','Approve')
							->where('release_by','!=','')
							->orderBy('pull_out_date','desc')
							->get();
		$content_title = 'Cash Pull Out Payment';
		return view('CPO.payment.cpo',compact('cpo','content_title'));
	}
	
	public function payment($id)
	{
		$cpo = CashPullOut::find($id);
		$cpoledger = CPOLedger::where('cpo_id',$id);
		$cpodata   = $cpoledger->get();
		$cposum    = $cpoledger->sum('check_amount');
		$bal       = $cpo->amount_edited - $cposum;
		//return view('cashpullout.payment.payment',compact('cpo','cpodata','cposum','bal'));
		return view('CPO.payment.payment',compact('cpo','cpodata','cposum','bal'));
	}
	
	public function paymentsave(Request $request)
	{
		$com            = Auth::user()->company_id;
		$bu             = Auth::user()->bunitid;
		$date           = date("Y-m-d");
		
		$sumCL          = CPOLedger::where('cpo_id',$request->cpo_id)->sum('check_amount');
		$checkAmt       = str_replace(",","",$request->check_amt);
		$totalTender    = $sumCL + $checkAmt;
		$cpoAmt         = str_replace(",","",$request->cpo_amt);
		$cpoAmt         = str_replace('₱','',$cpoAmt);
		if($totalTender == $cpoAmt || $totalTender < $cpoAmt)
		{
			$data = [
				'cpo_id'=>$request->cpo_id,
				'tcpof_no'=>$request->tcpof_no,
				'cpo_date'=>date("Y-m-d",strtotime($request->cpo_date)),
				'cpo_amount'=>str_replace(",","",$cpoAmt),
				'check_no'=>$request->checkno,
				'check_amount'=>str_replace(",","",$request->check_amt),
				'company'=>$com,
				'bu_unit'=>$bu,
				'paid_date'=>$date
			];
			
			CPOLedger::updateOrCreate($data);
			
			$sumCL  = CPOLedger::where('cpo_id',$request->cpo_id)->sum('check_amount');
			$sumCP  = CashPullOut::where('id',$request->cpo_id)->sum('amount_edited');
			
			if($sumCL == $sumCP)
			{
				CashPullOut::where('id',$request->cpo_id)
					->update(['status_paid'=>'Paid']);
			}
		}
		else
		{
			return "<p style='color:red'>The total amount you tender is greeter than amount to be paid, please view your ledger</p>";
		}
		

		
		return redirect('home');
	}
}
