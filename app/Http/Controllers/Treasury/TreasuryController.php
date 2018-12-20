<?php

namespace App\Http\Controllers\Treasury;

use App\AdjustmentLogs;
use App\BankAccount;
use App\CashLogBook;
use App\CashPullOut;
use App\CheckReceiving;
use App\Checks;
use App\CPOLedger;
use App\LogbookAdjustment;
use App\LogbookCheck;
use App\LogbookCheckLabel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TreasuryController extends Controller
{
	//
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function forDeposit()
	{
		$title = 'Logbook - Home';
		$ptitle = 'Home';
		$content_title = "Monthly List for Cash to be Deposit";
		$login_user = Auth::user();
		$month = CashLogBook::select('sales_date')
			->where('company', $login_user->company_id)
			->where('bu_unit', $login_user->bunitid)
			->where('company', $login_user->company_id)
			->where('bu_unit', $login_user->bunitid)
			->where('deposit_date', null)
			->groupBy(DB::raw('MONTH(sales_date)'))
			->orderBy('sales_date', 'DESC')
			->get();
		//->paginate(10);
		return view('treasury.add.monthlydep', compact('month', 'content_title'));
	}
	
	public function dailyDep($date)
	{
		$login_user = Auth::user();
		$com = Auth::user()->company_id;
		$bu = Auth::user()->bunitid;
		$month = date("m", strtotime($date));
		$year = date("Y", strtotime($date));
		$daily = CashLogBook::whereMonth('sales_date', $month)
			->whereYear('sales_date', $year)
			->where('deposit_date', null)
			->where('company', $login_user->company_id)
			->where('bu_unit', $login_user->bunitid)
			->orderBy('sales_date', 'DESC')
			->distinct()->get(['sales_date']);
		return view('treasury.add.dailyDep', compact('daily', 'login_user', 'date'));
	}
	
	public function viewDep($date)
	{
		session()->forget('check_data_receive');
		$login_user  = Auth::user();
		$title       = 'Logbook - Add';
		$ptitle      = 'Add';
		$month       = session('month');
		//Cash LOG Book Posted by liquidation
		$cashLog     = CashLogBook::where('sales_date', $date)
						->where('company', $login_user->company_id)
						->where('bu_unit', $login_user->bunitid)
						->where('status_clerk', 'posted')
						->orderBy('cash_id', 'asc')
						->get();
//		$adj        = LogbookAdjustment::where('sales_date',$date)
//					->where('company',$login_user->company_id)
//					->where('bu_unit',$login_user->bunitid)
//					->get();
		$adjs        = Array();
		$dueCheck    = Array();
		$totalDue    = 0;
		$totalPDC    = 0;
		$checktype   = Array();
		$checkClass  = Array();
		
		$totalChecks = 0;
		
		$cpoledger   = CPOLedger::where('paid_date', $date)
			->where('company', $login_user->company_id)
			->where('bu_unit', $login_user->bunitid);
		$cpolg       = $cpoledger->get();
		$cpolgsum    = $cpoledger->sum('check_amount');

			$adj3    = Checks::where('check_received','<=',$date)->where('date_deposit',null)->where('businessunit_id',$login_user->bunitid)->get();
			session(['check_data_receive'=>$adj3]);
			foreach($adj3->unique('check_class') as $key => $checkCl)
			{
				$totalCl      = $adj3->where('check_class',$checkCl->check_class)->sum('check_amount');
				$checkClass[] = (object)['check_class'=>$checkCl->check_class,'check_from'=>'ATP','check_amt_total'=>$totalCl];
			}
			
			foreach ($adj3 as $ck)
			{
				$checktype[] = $ck;
				$d1          = strtotime($date);
				$d2          = strtotime($ck->check_date);
				if ($d2 > $d1)
				{
					$totalPDC  += $ck->check_amount;
					$adjs[]     = [$ck->check_no, $ck->check_amount];
				}
				elseif ($d2 <= $d1)
				{
					$totalDue   += $ck->check_amount;
					$dueCheck[] = [$ck->check_no, $ck->check_amount];
				}
				$totalChecks += $ck->check_amount;
			}
		
		$allChecks[] = ['Due Checks', $totalDue+$cpolgsum];
		$allChecks[] = ['PDC', $totalPDC];
		
		$check       = LogbookCheck::where('sales_date', $date)
						->where('bu', $login_user->bunitid)
						->orderby('id', 'DESC')
						->get();

		
		//dd($cpolg);
		
		$checks      = $this->checkandCPOlg($check, $cpolg);
		$checksum    = LogbookCheck::where('sales_date', $date)
						->where('bu', $login_user->bunitid)
						->sum('amount_edited');
		$labels      = LogbookCheckLabel::get(['label_id', 'description']);
		
		
		$bankact     = BankAccount::where('company_code', $login_user->company_id)
						->where('buid', $login_user->bunitid)
						->get()
						->pluck('BankAccountList', 'id')
						->all();
		$cpoData     = CashPullOut::where('pull_out_date', $date)
						->where('bu_unit', $login_user->bunitid)
						->where('company', $login_user->company_id)
						->where('approve_by', '!=', '');
		$cposum      = $cpoData->sum('amount_edited');
		$cpo         = $cpoData->get();
		
		//$adjustment = $this->adjandCPO($cpo,$adj);
		$cashForDep  = $cashLog->sum('amount_edited') - ($cposum + $totalPDC);
		$adjustment  = $this->adjandCPO($cpo, $allChecks);
		
		$salesdate   = date_create($date);
		$now         = date_create(date("Y-m-d"));
		$diff        = date_diff($salesdate, $now);
		$format      = $diff->format("%a");
		//End Cash Log Book Posted by liquidation
		return view('treasury.add.viewDep', compact('auth', 'title', 'ptitle', 'login_user', 'date', 'month', 'cashLog', 'checks', 'checksum', 'labels', 'bankact', 'adjustment', 'allChecks', 'format', 'cpo', 'cposum', 'checktype','totalChecks','checkClass','cashForDep','cpolg','cpolgsum'));
		
	}
	
	public function checkdetails($checkClass,$date)
	{
		return view('treasury.add.checkDetails.details',compact('checkClass','date'));
	}
	
	public function pdc($checkClass,$date)
	{
		return view('treasury.add.checkDetails.pdc',compact('checkClass','date'));
	}
	
	public function due($checkClass,$date)
	{
		return view('treasury.add.checkDetails.due',compact('checkClass','date'));
	}
	
	public function adjandCPO($cpo, $adj)
	{
		$data = Array();
		$id = 0;
		foreach ($adj as $key => $ad) {
			//$data[]  = [$ad->id,$ad->description,$ad->amount_edited];
			$data[] = [$key, $ad[0], $ad[1]];
			$id = $key;
			//$id      = $ad->id;
		}
		foreach ($cpo as $key => $cp) {
			$id += $cp->id;
			$dep = explode("/", $cp->department->dep_name);
			$dep = trim($dep[1]);
			$data[] = [$id, $dep, $cp->amount_edited];
		}
		return $data;
	}
	
	public function checkandCPOlg($checks, $cpolg)
	{
		$data = Array();
		$id = 0;
		foreach ($checks as $key => $ch) {
			$data[] = [$ch->id, $ch->check_label->description, $ch->ds_number, $ch->amount_edited, ''];
			$id = $ch->id;
		}
		foreach ($cpolg as $key => $cpo) {
			$id += $cpo->id;
			$data[] = [$id, $cpo->check_no, 'Payment for ' . date('M d Y', strtotime($cpo->cpo_date)), $cpo->check_amount, 'hidden'];
		}
		return $data;
	}
	
	public function editAdjustment($id)
	{
		$adj = LogbookAdjustment::find($id);
		return view('treasury.add.editAdjustment', compact('adj'));
	}
	
	public function saveEdit(Request $request)
	{
		CashLogBook::where('id', $request->pk)->update(['amount_edited' => str_replace(",", "", $request->value)]);
	}
	
	public function addAdjustment($date)
	{
		$adj = AdjustmentLogs::pluck('description', 'adj_log_id')->all();
		return view('treasury.add.adjustment', compact('date', 'adj'));
	}
	
	public function saveAdjustment(Request $request)
	{
		$com = Auth::user()->company_id;
		$bu = Auth::user()->bunitid;
		$data = [
			'sales_date' => $request->date_adj,
			'description' => $request->description,
			'amount' => str_replace(",", "", $request->amount),
			'amount_edited' => str_replace(",", "", $request->amount),
			'company' => $com,
			'bu_unit' => $bu
		];
		$id = LogbookAdjustment::updateOrCreate($data)->id;
	}
	
	public function saveEditAdjustment($id, Request $request)
	{
		LogbookAdjustment::where('id', $id)->update(['description' => $request->description, 'amount_edited' => str_replace(',', '', $request->amount)]);
	}
	
	public function monDeposited()
	{
		$com        = Auth::user()->company_id;
		$bu         = Auth::user()->bunitid;
		$login_user = Auth::user();
		$cashdep    = CashLogBook::where('deposit_date','!=',null)
			->where('company',$com)
			->where('bu_unit',$bu)
			->groupBy(DB::raw('MONTH(sales_date)'))
			->orderBy('sales_date','DESC')
			->get();
		$title      = 'Logbook - Daily list';
		$ptitle     = 'Cash Deposited';
		$content_title = "Monthly list of deposited cash";
		return view('treasury.cashdeposited.monthly',compact('content_title','cashdep'));
		
	}
	
	public function dailyDeposited($date)
	{
		$com        = Auth::user()->company_id;
		$bu         = Auth::user()->bunitid;
		$login_user = Auth::user();
		$month      = date("m",strtotime($date));
		$year       = date("Y",strtotime($date));
		$dailydep    = CashLogBook::where('deposit_date','!=',null)
			->where('company',$com)
			->where('bu_unit',$bu)
			->whereMonth('sales_date',$month)
			->whereYear('sales_date',$year)
			->groupBy('sales_date')
			->orderBy('sales_date','DESC')
			->get();
		$content_title  = 'Daily list of deposited cash';
		return view('treasury.cashdeposited.daily',compact('dailydep','content_title','date'));
	}
	
	public function deposited($date)
	{
		$com        = Auth::user()->company_id;
		$bu         = Auth::user()->bunitid;
		$login_user = Auth::user();
		$month      = date("m",strtotime($date));
		$year       = date("Y",strtotime($date));
		$cashdep    = CashLogBook::where('deposit_date','!=',null)
			->where('company',$com)
			->where('bu_unit',$bu)
			->where('sales_date',$date)
			->get();
		//Cash LOG Book Posted by liquidation
//		$adj        = LogbookAdjustment::where('sales_date',$date)
//			->where('company',$login_user->company_id)
//			->where('bu_unit',$login_user->bunitid);
//		$adjsum     = $adj->sum('amount_edited');
		
		$adjs = Array();
		$dueCheck = Array();
		$totalDue = 0;
		$totalPDC = 0;
		$checktype = Array();
		$adj3    = Checks::where('check_received','<=',$date)->where('batch_date',$date)->where('businessunit_id',$login_user->bunitid)->get();
			
			foreach ($adj3 as $ck) {
				$checktype[] = $ck;
				$d1 = strtotime($date);
				$d2 = strtotime($ck->check_date);
				if ($d2 > $d1) {
					$totalPDC += $ck->check_amount;
					$adjs[] = [$ck->check_no, $ck->check_amount];
				} elseif ($d2 <= $d1) {
					$totalDue += $ck->check_amount;
					$dueCheck[] = [$ck->check_no, $ck->check_amount];
				}
			}
	
		$allChecks[] = ['Due Checks', $totalDue];
		$allChecks[] = ['PDC', $totalPDC];
		
		$adjsum     = $totalPDC;
		
		$cpoData    = CashPullOut::where('pull_out_date',$date)
			->where('bu_unit',$login_user->bunitid)
			->where('company',$login_user->company_id)
			->where('approve_by','!=','');
		$cposum     = $cpoData->sum('amount_edited');
		$cpo        = $cpoData->get();
		$adjustment = $this->adjandCPO($cpo,$allChecks);
		//dd($adjustment,$adjsum,$date);
		$title      = 'Logbook - Daily list';
		$ptitle     = 'Cash Deposited';
		$total      = 0;
		return view('treasury.cashdeposited.deposited',compact('cashdep','date','adjustment','adjsum','cposum','total'));
	}
	
	public function viewSMDetails($id,$date)
	{
		$login_user = Auth::user();
		
		$adjs = Array();
		$dueCheck = Array();
		$totalDue = 0;
		$totalPDC = 0;
		$checktype = Array();
		$adj3    = Checks::where('check_received','<=',$date)->where('batch_date',$date)->where('businessunit_id',$login_user->bunitid)->get();
			
			foreach ($adj3 as $ck) {
				$checktype[] = $ck;
				$d1 = strtotime($date);
				$d2 = strtotime($ck->check_date);
				if ($d2 > $d1) {
					$totalPDC += $ck->check_amount;
					$adjs[] = [$ck->check_no, $ck->check_amount];
				} elseif ($d2 <= $d1) {
					$totalDue += $ck->check_amount;
					$dueCheck[] = [$ck->check_no, $ck->check_amount];
				}
			}
		
		
		
		
		$allChecks[] = ['Due Checks', $totalDue];
		$allChecks[] = ['PDC', $totalPDC];
		
		$adjsum     = $totalPDC;
		$cpoData    = CashPullOut::where('pull_out_date',$date)
			->where('bu_unit',$login_user->bunitid)
			->where('company',$login_user->company_id)
			->where('approve_by','!=','');
		$cposum     = $cpoData->sum('amount_edited');
		$cpo        = $cpoData->get();
		$adjustment = $this->adjandCPO($cpo,$allChecks);
		$sm         = CashLogBook::find($id);
		$totalAmount= $sm->amount_edited - ($cposum + $adjsum);
		return view('treasury.cashdeposited.viewSMDetails',compact('sm','adjustment','totalAmount'));
	}
	
	public function cashRelease()
	{
		$auth          = Auth::user()->usertype->user_type_name;
		$login_user    = Auth::user();
		$com           = $login_user->company_id;
		$bu            = $login_user->bunitid;
		$cpo           = CashPullOut::where('cpo_status','Approve')
						->where('company',$com)
						->where('bu_unit',$bu)
						->orderBy('pull_out_date','DESC')
						->get();
		$content_title = "Cash Releasing";
		return view('treasury.cashrelease.cashRelease',compact(  'content_title','cpo'));

		
	}
	
	public function release($id)
	{
		$user_id     = Auth::user()->user_id;
		CashPullOut::where('id',$id)->update(['release_by'=>$user_id,'release_status'=>'release']);
	}
	
	public function printAll($date)
	{
		return view('treasury.printData.data',compact('date'));
	}
	
	public function postingLogs($saledate,$depositdate)
	{
		$com = Auth::user()->company_id;
		$bu  = Auth::user()->bunitid;
		CashLogBook::where('sales_date',$saledate)
			->where('bu_unit',$bu)
			->where('company',$com)
			->update(['deposit_date'=>$depositdate,'status_treasury'=>'posted']);
		
		$allcheck = Checks::where('check_received','<=',$saledate)->where('batch_date',$saledate)->where('businessunit_id',$bu);
		foreach($allcheck->get() as $ck)
		{
			$d1          = strtotime($saledate);
			$d2          = strtotime($ck->check_date);
			$checkid     = $ck->checks_id;
			
            if($d2 <= $d1)
            {
                Checks::where('checks_id',$checkid)
	                ->where('businessunit_id',$bu)
	                ->where('date_deposit',null)
	                ->update([
	                	'date_deposit'=>$depositdate,
		                'batch_date'=>$saledate
	                ]);
            }
            else
            {
	            Checks::where('checks_id',$checkid)
		            ->where('businessunit_id',$bu)
		            ->where('date_deposit',null)
		            ->update([
			            'batch_date'=>$saledate
		            ]);
            }
		}
		
	}
}
