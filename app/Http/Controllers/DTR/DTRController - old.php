<?php

namespace App\Http\Controllers\DTR;

use App\BankAccount;
use App\BankNo;
use App\Businessunit;
use App\Company;
use App\DTR;
use App\Functions\DTRUploadingCSV;
use App\Functions\DTRUploadingExcel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DTRController extends Controller
{
    //
	public $dtrExcel;
	public $dtrCSV;
	
	public function __construct()
	{
		$this->middleware('auth');
		//session()->forget('mgaerrors');
		//echo view('layouts.progressbar');
	}
	
	public function uploadDTR()
	{
		$company = Company::pluck('company','company_code')->all();
		return view('DTR.finance.uploadForm',compact('company'));
	}
	
	public function bu($com)
	{
		$bu = Businessunit::where('company_code',$com)
							->get()
							->pluck('bname','unitid')
							->all();
		return view('DTR.finance.layouts.bu',compact('bu'));
	}
	
	public function bankAcct($com,$bu)
	{
		$bankAcct = BankAccount::where('company_code',$com)
								->where('buid',$bu)
								->get()
								->pluck('BankAccountList','id')
								->all();
		return view('DTR.finance.layouts.bankAccount',compact('bankAcct'));
	}
	
	public function DTRsaving(Request $request)
	{
		$this->dtr = new DTRUploadingExcel();
		$this->dtrCSV = new DTRUploadingCSV();
		
		$file = $request->file('dtr');
		foreach($file as $key => $files)
		{
			
			$filepath = $files->getPathName();
			$filename = $files->getClientOriginalName();
			$extension = \File::extension($filename);


			DB::transaction(function()use($filepath,$filename,$extension,$request)
			{
				if($extension!='csv')
				{
					$this->dtr->excel($filepath,$filename,$extension,$request);
				}
				else
				{
					$this->dtrCSV->CSV($filepath,$filename,$extension,$request);
				}
			});

		}
	}
	
	//DTR Viewing
	public function dtrBankAct($buid)
	{
		$bank = BankAccount::where('buid',$buid)->get()->pluck('BankAccountList','id')->all();
		
		if(count($bank)<=0)
		{
			$bank = [0=>'No Result Found'];
		}
		else
		{
			$bank = $bank;
		}
		
		//dd($bank);
		
		return view('DTR.finance.bankAcct',compact('bank'));
	}
	
	public function bsTable($buid,$bankID)
	{
		$dateYear  = date("Y");
		$dateMonth = date("m");
		
		$bankno    = BankAccount::find($bankID)->bankcode->bankno;
		$com       = Businessunit::find($buid)->company->company_code;
		$dtr       = DTR::where('bank_account_no', $bankno)
					 ->whereMonth('bank_date',trim($dateMonth))
					 ->whereYear('bank_date',trim($dateYear))
					 ->where('company', $com)
					 ->where('bu_unit', $buid)
					 ->get();
		$day       = DTR::select(DB::raw('DATE_FORMAT(bank_date,"%Y-%m-%d") as datein'))
					 ->where('bank_account_no', $bankno)
					 ->whereMonth('bank_date',$dateMonth)
					 ->whereYear('bank_date',$dateYear)
					 ->where('company', $com)
					 ->where('bu_unit', $buid)
					 ->groupBy(DB::raw('DAY(bank_date)'))
					 ->get();
		$countDay  = Array();
		$allDay    = Array();


		//dd($day->toArray());
		

		$date                    = $dateYear . '-' . $dateMonth . '-01';
		$currentMonthFirstDay    = date("N", strtotime($date));
		$totalDaysOfMonth        = cal_days_in_month(CAL_GREGORIAN, $dateMonth, $dateYear);
		$totalDaysOfMonthDisplay = ($currentMonthFirstDay == 7) ? ($totalDaysOfMonth) : ($totalDaysOfMonth + $currentMonthFirstDay);
		$boxDisplay              = ($totalDaysOfMonthDisplay <= 35) ? 35 : 42;
		
		//last day of the previous month
		$datel = new \DateTime("$dateYear-$dateMonth-01");
		$datel->modify("last day of previous month");
		$lastDayPrevMonth = $datel->format("t");
		//Last day of teh next month
		$datel = new \DateTime("$dateYear-$dateMonth-01");
		$datel->modify("last day of next month");
		$lastDayNextMonth = $datel->format("t");
		
		$dayPrev   = $currentMonthFirstDay;
		$nextmonth = 1;
		$dayCount  = 1;
		$array     = Array();
		$arDay     = Array();
		$arStyle   = Array();
		$style     = Array();
		for ($cb = 1; $cb <= $boxDisplay; $cb++)
		{
			if (($cb >= $currentMonthFirstDay + 1 || $currentMonthFirstDay == 7) && $cb <= ($totalDaysOfMonthDisplay))
			{
				$style[]    = "";
				$array[]    = $dayCount;
				$dayCount <10?$dayfor = trim("0".$dayCount) : $dayfor  = trim($dayCount);
				$setDate    = trim("$dateYear-$dateMonth-$dayfor");
				$countDay[] = DTR::where('bank_account_no', $bankno)
					->where('company', $com)
					->where('bu_unit', $buid)
					->where('bank_date',$setDate)->count('id');
				$dayCount++;
			}
			elseif (($cb <= $currentMonthFirstDay + 1 || $currentMonthFirstDay == 7) && $cb <= ($totalDaysOfMonthDisplay))
			{
				$style[]    = "color:#ccc";
				$array[]    = ($lastDayPrevMonth - $dayPrev) + 1;
				$countDay[] = "void";
				$dayPrev--;
			}
			else
			{
				$style[]    = "color:#ccc";
				$array[]    = $nextmonth;
				$countDay[] = "void";
				$nextmonth++;
			}
			
			if (($cb % 7) == 0)
			{
				$arDay[]   = $array;
				$arStyle[] = $style;
				$allDay[]  = $countDay;
				$style     = Array();
				$array     = Array();
				$countDay  = Array();
			}
		}
		
		$curDate = date("Y-m-d");
		
		$months = Array();
		for ($i = 1; $i <= 12; $i++)
		{
			$value = ($i < 10) ? '0' . $i : $i;
			$months[$value] = date("F", mktime(0, 0, 0, $i + 1, 0, 0));
		}
		
		$years = Array();
		for ($y = date('Y'); $y >= 2010; $y--)
		{
			$years[$y] = $y;
		}
		return view('DTR.finance.dtrData', compact('dtr', 'day', 'arDay', 'curDate', 'dateYear', 'dateMonth', 'arStyle', 'months','years','bankID','buid','allDay'));

	}
	
	public function monthsAndYearData($bankID,$buid,$dateYear,$dateMonth)
	{
		
		$bankno    = BankAccount::find($bankID)->bankcode->bankno;
		$com       = Businessunit::find($buid)->company->company_code;
		$dtr       = DTR::where('bank_account_no', $bankno)
					->whereMonth('bank_date',$dateMonth)
					->whereYear('bank_date',$dateYear)
					->where('company', $com)
					->where('bu_unit', $buid)
					->get();
		$day       = DTR::select(DB::raw('DATE_FORMAT(bank_date,"%Y-%m-%d") as datein'))
					->where('bank_account_no', $bankno)
					->whereMonth('bank_date',$dateMonth)
					->whereYear('bank_date',$dateYear)
					->where('company', $com)
					->where('bu_unit', $buid)
					->groupBy(DB::raw('DAY(bank_date)'))
					->get();
		//dd($day->toArray());
		
		$countDay  = Array();
		$allDay    = Array();
		
		$dataDTR   = DTR::where('bank_account_no', $bankno)
			->where('company', $com)
			->where('bu_unit', $buid);
		
		
		$date                    = $dateYear . '-' . $dateMonth . '-01';
		$currentMonthFirstDay    = date("N", strtotime($date));
		$totalDaysOfMonth        = cal_days_in_month(CAL_GREGORIAN, $dateMonth, $dateYear);
		$totalDaysOfMonthDisplay = ($currentMonthFirstDay == 7) ? ($totalDaysOfMonth) : ($totalDaysOfMonth + $currentMonthFirstDay);
		$boxDisplay              = ($totalDaysOfMonthDisplay <= 35) ? 35 : 42;
		
		//last day of the previous month
		$datel = new \DateTime("$dateYear-$dateMonth-01");
		$datel->modify("last day of previous month");
		$lastDayPrevMonth = $datel->format("t");
		//Last day of teh next month
		$datel = new \DateTime("$dateYear-$dateMonth-01");
		$datel->modify("last day of next month");
		$lastDayNextMonth = $datel->format("t");
		
		$dayPrev = $currentMonthFirstDay;
		$nextmonth = 1;
		
		$dayCount = 1;
		
		$array = Array();
		$arDay = Array();
		$arStyle = Array();
		$style = Array();
		for ($cb = 1; $cb <= $boxDisplay; $cb++)
		{
			if (($cb >= $currentMonthFirstDay + 1 || $currentMonthFirstDay == 7) && $cb <= ($totalDaysOfMonthDisplay))
			{
				$style[]    = "";
				$array[]    = $dayCount;
				$dayCount <10?$dayfor = trim("0".$dayCount) : $dayfor  = trim($dayCount);
				$setDate    = trim("$dateYear-$dateMonth-$dayfor");
				$countDay[] = DTR::where('bank_account_no', $bankno)
					->where('company', $com)
					->where('bu_unit', $buid)
					->where('bank_date',$setDate)->count('id');
				$dayCount++;
			}
			elseif (($cb <= $currentMonthFirstDay + 1 || $currentMonthFirstDay == 7) && $cb <= ($totalDaysOfMonthDisplay))
			{
				$style[]    = "color:#ccc";
				$array[]    = ($lastDayPrevMonth - $dayPrev) + 1;
				$countDay[] = "void";
				$dayPrev--;
			}
			else
			{
				$style[]    = "color:#ccc";
				$array[]    = $nextmonth;
				$countDay[] = "void";
				$nextmonth++;
			}
			
			if (($cb % 7) == 0)
			{
				$arDay[]   = $array;
				$arStyle[] = $style;
				$allDay[]  = $countDay;
				$style     = Array();
				$array     = Array();
				$countDay  = Array();
			}
			
		}
		
		//dd($allDay);
		
		$curDate = date("Y-m-d");
		
		return view('DTR.finance.calendar',compact('dtr','day','curDate','dateYear','dateMonth','arStyle','arDay','allDay','bankID','buid'));
	}
	
	public function tabular($bankID,$buid,$dateYear,$dateMonth)
	{
		
		$bankno    = BankAccount::find($bankID)->bankcode->bankno;
		$com       = Businessunit::find($buid)->company->company_code;
		$dtr       = DTR::where('bank_account_no', $bankno)
					->whereMonth('bank_date',$dateMonth)
					->whereYear('bank_date',$dateYear)
					->where('company', $com)
					->where('bu_unit', $buid)
					->get();
		return view('DTR.finance.tabular',compact('dtr'));
	}
	
	public function daily($bankID,$buid,$date)
	{
		$bankno    = BankAccount::find($bankID)->bankcode->bankno;
		$com       = Businessunit::find($buid)->company->company_code;
		$dtr       = DTR::where('bank_account_no', $bankno)
					->where('bank_date',$date)
					->where('company', $com)
					->where('bu_unit', $buid)
					->get();
		return view('DTR.finance.tabular',compact('dtr','date'));
	}
	
	public function dashboard()
	{
		$allBU         = Businessunit::pluck('bname','unitid')->all();
		$content_title = 'Daily Transaction Record';
		$ajax_load     = "load";
		return view('DTR.finance.dtrUploaded',compact('allBU','content_title','ajax_load'));
	}
}
