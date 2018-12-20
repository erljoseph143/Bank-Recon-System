<?php

namespace App\Http\Controllers\DTR;

use App\BankAccount;
use App\BankNo;
use App\Businessunit;
use App\Company;
use App\DTR;
use App\Functions\DTRUploadingCSV;
use App\Functions\DTRUploadingExcel;
use App\Functions\DTRUploadingExcel2005;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Cell;
use PHPExcel_Shared_Date;
use PHPExcel_Style_NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DTRController extends Controller
{
    //
	public $dtrExcel;
	public $dtrExcel2005;
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
		$y  = date("Y");
		$yearData  = Array();
		$yearData[$y] = $y;
		for($x=1;$x<=5;$x++)
		{
			$index      = $y-$x;
			$yearData[$index] =$index;
		}
		//dd($yearData);
		return view('DTR.finance.uploadForm',compact('company','yearData'));
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
//		$this->dtrExcel     = new DTRUploadingExcel();
//		$this->dtrCSV       = new DTRUploadingCSV();
		$this->dtrExcel2005 = new DTRUploadingExcel2005();
		
		
		$file      = $request->file('dtr');
		$filepath  = $file->getPathName();
		$filename  = $file->getClientOriginalName();
		$extension = \File::extension($filename);

			DB::transaction(function()use($filepath,$filename,$extension,$request)
			{
				$this->dtrExcel2005->excel($filepath,$filename,$extension,$request);
			});
		
		$bankName = BankAccount::find($request->bankAcct);
		$bank     = $bankName->bank;
		$banknoid = $bankName->bankno;
		$bankno   = BankNo::find($banknoid);
		$bank_no  = $bankno->bankno;

		$dtr      = DTR::where('bank_account_no',$bank_no)
					->where('company',$request->com)
					->where('bu_unit',$request->bu)
					->orderBy('id','DESC');
		if($dtr->count('id')>0)
		{
			$arrayDTR = ["date"=>date("m/d/Y",strtotime($dtr->first()->bank_date)),"bank_balance"=>number_format($dtr->first()->bank_balance,2)];
			echo json_encode($arrayDTR);
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
				//$array[]    = ($lastDayPrevMonth - $dayPrev) + 1;
				$array[]    = "";
				$countDay[] = "void";
				$dayPrev--;
			}
			else
			{
				$style[]    = "color:#ccc";
//				$array[]    = $nextmonth;
				$array[]    = "";
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
			$months[" $value"] = date("F", mktime(0, 0, 0, $i + 1, 0, 0));
		}
		
		$years = Array();
		for ($y = date('Y'); $y >= 2010; $y--)
		{
			$years[" $y"] = (int)$y;
		}
		
		$curDate = date("Y-m-d");
		$rowArray = Array();
		$dataCalendar = Array(
			"day" => Array(),
			"liClass"=>Array(),
			"liStyle"=>Array(),
			"dataDate"=>Array(),
			"records"=>Array()
		);
		
		foreach($arDay as $key => $day):
			$dataCalendar["day"] = Array();
			$dataCalendar["liClass"] = Array();
			$dataCalendar["liStyle"] = Array();
			$dataCalendar["dataDate"] = Array();
			$dataCalendar["records"] = Array();
			foreach($day as $key2 => $d):
				$d<10?$dayof="0$d":$dayof=$d;
				$dateNew = "$dateYear-$dateMonth-$dayof";
				strtotime($curDate)==strtotime($dateNew)?$liClass = 'grey': $liClass = $dateNew;
				// <li class="{{strtotime($curDate)==strtotime($dateNew)?'grey':"$dateNew"}} date_cell" style="{{$arStyle[$key][$key2]}}">
				//  <span>
				//   {{$d}}
				// </span>
				$dataCalendar["day"][] = $d;
				$dataCalendar["liClass"][] = $liClass;
				$dataCalendar["liStyle"][] = $arStyle[$key][$key2];
				if(trim($allDay[$key][$key2])!='void' and trim($allDay[$key][$key2])!=0):
					//<div class="record" data-date="{{$dateNew}}" style="margin-top:30px;color:blue">
					//  {{$allDay[$key][$key2]}}
					// <br>
					//Records
					//</div>
					$dataCalendar["records"][] = $allDay[$key][$key2] ."</br> Records";
					$dataCalendar["dataDate"][] = $dateNew;
				else:
					$dataCalendar["records"][] = "";
					$dataCalendar["dataDate"][] = "";
				endif;
				//</li>
			endforeach;
			$rowArray[] = $dataCalendar;
		endforeach;
		
		$arrayAll = [
			"dateMonth" =>$dateMonth,
			"dateYear"=>$dateYear,
			"buid"=>$buid,
			"bankID"=>$bankID,
			"months"=>$months,
			"Years"=>$years,
			"calendar"=>$rowArray
			];
		
		return response()->json($arrayAll);
		//return view('DTR.finance.dtrData', compact('dtr', 'day', 'arDay', 'curDate', 'dateYear', 'dateMonth', 'arStyle', 'months','years','bankID','buid','allDay'));

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
				//$array[]    = ($lastDayPrevMonth - $dayPrev) + 1;
				$array[]    = "";
				$countDay[] = "void";
				$dayPrev--;
			}
			else
			{
				$style[]    = "color:#ccc";
				//$array[]    = $nextmonth;
				$array[]    = "";
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
		$rowArray = Array();
		$dataCalendar = Array(
			"day" => Array(),
			"liClass"=>Array(),
			"liStyle"=>Array(),
			"dataDate"=>Array(),
			"records"=>Array()
		);
		
		foreach($arDay as $key => $day):
			$dataCalendar["day"] = Array();
			$dataCalendar["liClass"] = Array();
			$dataCalendar["liStyle"] = Array();
			$dataCalendar["dataDate"] = Array();
			$dataCalendar["records"] = Array();
			foreach($day as $key2 => $d):
				$d<10?$dayof="0$d":$dayof=$d;
				$dateNew = "$dateYear-$dateMonth-$dayof";
				strtotime($curDate)==strtotime($dateNew)?$liClass = 'grey': $liClass = $dateNew;
				// <li class="{{strtotime($curDate)==strtotime($dateNew)?'grey':"$dateNew"}} date_cell" style="{{$arStyle[$key][$key2]}}">
				//  <span>
				//   {{$d}}
				// </span>
				$dataCalendar["day"][] = $d;
				$dataCalendar["liClass"][] = $liClass;
				$dataCalendar["liStyle"][] = $arStyle[$key][$key2];
				if(trim($allDay[$key][$key2])!='void' and trim($allDay[$key][$key2])!=0):
					//<div class="record" data-date="{{$dateNew}}" style="margin-top:30px;color:blue">
					//  {{$allDay[$key][$key2]}}
					// <br>
					//Records
					//</div>
					$dataCalendar["records"][] = $allDay[$key][$key2] ."</br> Records";
					$dataCalendar["dataDate"][] = $dateNew;
				else:
					$dataCalendar["records"][] = "";
					$dataCalendar["dataDate"][] = "";
				endif;
				//</li>
			endforeach;
			$rowArray[] = $dataCalendar;
		endforeach;
		
		$arrayAll = [
			"dateMonth" =>$dateMonth,
			"dateYear"=>$dateYear,
			"buid"=>$buid,
			"bankID"=>$bankID,
			"calendar"=>$rowArray
		];
		
		return response()->json($arrayAll);
		//dd($allDay);
		
		
		
		//return view('DTR.finance.calendar',compact('dtr','day','curDate','dateYear','dateMonth','arStyle','arDay','allDay','bankID','buid'));
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
	
	public function allbanks($bank)
	{
		$allbank = BankAccount::where('bank','REGEXP',$bank)->get();
		$bsData  = Array();
		$dtrdata = Array();
		foreach($allbank as $key => $banks)
		{
			$businessUnit = $banks->businessunit->bname;
			$dtrdata[$key] = ['date'=>'','amount'=>''];
			$bankno = $banks->bankcode->bankno;
			$com    = $banks->company_code;
			$bu     = $banks->buid;
			$dtr    = DTR::where('bank_account_no',$bankno)
						->where('company',$com)
						->where('bu_unit',$bu)
						->orderBy('id','DESC');

			if($dtr->count('id')>0)
			{
				$dtrdata[$key] = ["date"=>$dtr->first()->bank_date,"amount"=>$dtr->first()->bank_balance];
			}


		}
		$y  = date("Y");
		$yearData  = Array();
		$yearData[$y] = $y;
		for($x=1;$x<=5;$x++)
		{
			$index      = $y-$x;
			$yearData[$index] =$index;
		}
		//dd($allbank);
		return response()->json([$allbank,$dtrdata]);
		//return view('DTR.finance.banks.banklist',compact('allbank','dtrdata','yearData'));
		
	}
	
	public function accountingDTR()
	{
		return view('DTR.accounting.home');
	}
	
	public function allbanksAccounting($bank)
	{
		$com     = Auth::user()->company_id;
		$bu      = Auth::user()->bunitid;
		$allbank = BankAccount::where('bank','REGEXP',$bank)->where('company_code',$com)->where('buid',$bu)->get();
		$bsData  = Array();
		$dtrdata = Array();
		foreach($allbank as $key => $banks)
		{
			$businessUnit = $banks->businessunit->bname;
			$dtrdata[$key] = ['date'=>'','amount'=>''];
			$bankno = $banks->bankcode->bankno;
			$com    = $banks->company_code;
			$bu     = $banks->buid;
			$dtr    = DTR::where('bank_account_no',$bankno)
				->where('company',$com)
				->where('bu_unit',$bu)
				->orderBy('id','DESC');
			
			if($dtr->count('id')>0)
			{
				$dtrdata[$key] = ["date"=>$dtr->first()->bank_date,"amount"=>$dtr->first()->bank_balance];
			}
			
			
		}
		$y  = date("Y");
		$yearData  = Array();
		$yearData[$y] = $y;
		for($x=1;$x<=5;$x++)
		{
			$index      = $y-$x;
			$yearData[$index] =$index;
		}
		//dd($allbank);
		return response()->json([$allbank,$dtrdata]);
		//return view('DTR.finance.banks.banklist',compact('allbank','dtrdata','yearData'));
		
	}
	
	public function form($bank,$bankacct,$com,$bu)
	{
		$y  = date("Y");
		$yearData  = Array();
		$yearData[$y] = $y;
		for($x=1;$x<=5;$x++)
		{
			$index      = $y-$x;
			$yearData[$index] =$index;
		}
		
		return view('DTR.finance.ajax.form',compact('bank','bankacct','com','bu','datafor','yearData'));
	}
	
	public function progressBar()
	{
		return view('DTR.finance.ajax.progressBar');
	}
	
	public function notBalance($data)
	{
		$error = base64_decode($data);
		return response()->json(explode("/",$error));
		//return view('DTR.finance.errors.notEqual',compact('data'));
	}
	
	public function showErrors(Request $request)
	{
		return $errorArray = $request->errorArray;
		//$errorArray = json_decode($request->errorArray);
		//return view('DTR.finance.errors.errorlist',compact('errorArray'));
	}
	
	public function invalidFormat($message)
	{
		return $message;
		//return view('DTR.finance.errors.invalidFormat',compact('message'));
	}
	
	public function readExcel($filename,$col,$row,Request $request)
	{
		$localIP = getenv('REMOTE_ADDR');
		if($localIP == "::1")
		{
			$localIP = "172.16.40.12";
		}
		else
		{
			$localIP = $localIP;
		}
		
		
		
		Excel::load("functions/tempuploads/".$localIP."/".$filename,function($reader)use($col,$row){
			$objWorksheet   = $reader->getActiveSheet();
			
			
			$highestRow    = $objWorksheet->getHighestRow();
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			// $highestColumnIndex = $highestColumn;
			$error_format = $col;
			if($error_format !='Error')
			{
				$row_format = 0;
			}
			else
			{
				$row_format = $row;
			}
			
			if($row_format == 0)
			{
				$class_tr ="";
				$color  = "";
			}
			else
			{
				$class_tr ="red";
				$color = 'white';
			}
			
			echo $col;
//----end error bank format---//
			
			echo '<table border="1" style="width:100%">' ;
			echo "<tr><th></th><th style='text-align:center'>A</th><th style='text-align:center'>B</th><th style='text-align:center'>C</th><th style='text-align:center'>D</th><th style='text-align:center'>E</th><th style='text-align:center'>F</th><th style='text-align:center'>G</th></tr>";
			for ($row = 1; $row <= $highestRow; ++$row) {
				if($row_format == $row)
				{
					echo '<tr style="background-color:'.$class_tr.';color:'.$color.'">';
				}
				else
				{
					//echo "Error";
					echo '<tr>' ;
				}
				echo '<td>'.$row.'</td>';
				
				for ($col = 0; $col <= $highestColumnIndex; ++$col) {
					
					
					echo '<td style=""><font size="2.5">&nbsp;' . $objWorksheet->getCellByColumnAndRow($col, $row)->getFormattedValue() . '</font></td>';
					
				}
				
				echo '</tr>';
			}
			echo '</table>';
			
			
		});
	}
	
	public function dataBank($bankacct,$com,$bu,$year,$month)
	{
		$bankName = BankAccount::find($bankacct);
		$bank     = $bankName->bank;
		$banknoid = $bankName->bankno;
		$bankno   = BankNo::find($banknoid);
		$bank_no  = $bankno->bankno;
		
			$data = DTR::where('bank_account_no',$bank_no)
				->whereYear('bank_date',$year)
				->whereMonth('bank_date',$month)
			    ->where('company',$com)
			    ->where('bu_unit',$bu)
			    ->get();
		return $data;
	}
	
	public function dataBankPerDate($bankacct,$com,$bu,$date)
	{
		$bankName = BankAccount::find($bankacct);
		$bank     = $bankName->bank;
		$banknoid = $bankName->bankno;
		$bankno   = BankNo::find($banknoid);
		$bank_no  = $bankno->bankno;
		
		$data = DTR::where('bank_account_no',$bank_no)
			->where('bank_date',$date)
			->where('company',$com)
			->where('bu_unit',$bu)
			->get();
		return $data;
	}
//Route::get('excel/{bankacct}/{com}/{bu}/{month}/{year}','DTR\DTRController@getDTRExcel');
	public function getDTRExcel($bankacct,$com,$bu,$month,$year)
	{
		$date    = date("F, Y",strtotime("$year-$month"));
		$account = BankAccount::find($bankacct);
		$acctName = "$account->bank - $account->accountno";
		Excel::create("$acctName Daily Records as of $date", function($excel) use($account,$com,$bu,$month,$year) {
			
			// Set the title
			$excel->setTitle('Daily Transaction Records');
			
			// Chain the setters
			$excel->setCreator('BRS PROGRAMMER REY JOSEPH T. BAAY, JELLARY CADUTDUT,GLENN MICHAEL MEJEIAS')->setCompany('Coporate AGC');
			
			$excel->setDescription('Daily Transaction Record Per Banks');
			
			$excel->sheet('DTR', function ($sheet) use ($account,$com,$bu,$month,$year) {
				
				$bankNo   = $account->bankcode->bankno;
				$bank     = DTR::whereMonth('bank_date',$month)
							->whereYear('bank_date',$year)
							->where('bank_account_no',$bankNo)
							->where('company',$com)
							->where('bu_unit',$bu)
							->get();
				$data     = Array();
				if($account->bank=="MB" or $account->bank=="MBTC")
				{
					$headings = ["Date", "Check No.", "Description", "Debit", "Credit", "Balance", "Branch"];
					foreach($bank as $key => $b)
					{
						$date    = PHPExcel_Shared_Date::PHPToExcel(strtotime($b->bank_date));
						$checkno = $b->check_no;
						$des     = $b->trans_des;
						if($b->type_amount=="AR")
						{
							$credit   = $b->bank_amount;
							$debit    = "";
						}
						elseif($b->type_amount =="AP")
						{
							$debit    = $b->bank_amount;
							$credit   = "";
						}
						$balance = $b->bank_balance;
						$branch  = $b->branch;
						
						$debitLetter   = "D";
						$creditLetter  = "E";
						$balanceLetter = "F";
						$mergeLetter   = "A1:F1";
						$mergeLetter2  = "A2:F2";
						
						$data[]  = [$date,$checkno,$des,$debit,$credit,$balance,$branch];
					}
				}
				elseif($account->bank=="BPI")
				{
					$headings = ["Date", "Check Number", "SBA Reference No.", "Branch", "Transaction Code", "Transaction Description", "Debit", "Credit", "Running Balance"];
					foreach($bank as $key => $b)
					{
						$date       = PHPExcel_Shared_Date::PHPToExcel(strtotime($b->bank_date));
						$checkno    = $b->check_no;
						$sba_ref_no = $b->sba_ref_no;
						$branch     = $b->branch;
						$trans_code = $b->trans_code;
						$des        = $b->trans_des;
						if($b->type_amount=="AR")
						{
							$credit   = $b->bank_amount;
							$debit    = "";
						}
						elseif($b->type_amount =="AP")
						{
							$debit    = $b->bank_amount;
							$credit   = "";
						}
						$balance = $b->bank_balance;
						
						$debitLetter   = "G";
						$creditLetter  = "H";
						$balanceLetter = "I";
						$mergeLetter   = "A1:I1";
						$mergeLetter2  = "A2:I2";
						
						$data[]  = [$date,$checkno,$sba_ref_no,$branch,$trans_code,$des,$debit,$credit,$balance];
					}
				}
				elseif($account->bank=="LBP")
				{
					$headings = ["Date", "Description", "Debit", "Credit", "Balance", "Branch", "Cheque Number"];
					foreach($bank as $key => $b)
					{
						$date       = PHPExcel_Shared_Date::PHPToExcel(strtotime($b->bank_date));
						$checkno    = $b->check_no;
						$branch     = $b->branch;
						$des        = $b->trans_des;
						if($b->type_amount=="AR")
						{
							$credit   = $b->bank_amount;
							$debit    = "";
						}
						elseif($b->type_amount =="AP")
						{
							$debit    = $b->bank_amount;
							$credit   = "";
						}
						$balance = $b->bank_balance;
						
						$debitLetter   = "C";
						$creditLetter  = "D";
						$balanceLetter = "E";
						$mergeLetter   = "A1:E1";
						$mergeLetter2  = "A2:E2";
						
						$data[]  = [$date,$des,$debit,$credit,$balance,$branch,$checkno];
					}
				}
				elseif($account->bank=="PNB")
				{
					$headings = ["Post Date", "Value Date", "NEG BR", "Transaction Description", "Check/ Seq No", "Withdrawals", "Deposits", "Balance"];
					foreach($bank as $key => $b)
					{
						$date       = PHPExcel_Shared_Date::PHPToExcel(strtotime($b->bank_date));
						$checkno    = $b->check_no;
						$branch     = $b->branch;
						$des        = $b->trans_des;
						if($b->type_amount=="AR")
						{
							$credit   = $b->bank_amount;
							$debit    = "";
						}
						elseif($b->type_amount =="AP")
						{
							$debit    = $b->bank_amount;
							$credit   = "";
						}
						$balance = $b->bank_balance;
						
						$debitLetter   = "F";
						$creditLetter  = "G";
						$balanceLetter = "H";
						$mergeLetter   = "A1:H1";
						$mergeLetter2  = "A2:H2";
						
						$data[]  = [$date,$date,$branch,$des,$checkno,$debit,$credit,$balance];
					}
				}
				
				$date2    = date("F, Y",strtotime("$year-$month"));
				$acctName = "$account->bank - $account->accountno";
				$buUnit   = Businessunit::find($bu);
				$bu_Unit  = $buUnit->bname;
				$company  = $buUnit->company->company;
				
				$count  = count($data)+4;
				$sheet->prependRow(4, $headings);
				$sheet->fromArray($data, NULL, 'A5',false,false);
				
				$sheet->row(1,array("$company : $bu_Unit"));
				$sheet->row(2,array("Daily Transaction Record of $acctName as of $date2"));
				$sheet->mergecells("$mergeLetter");
				$sheet->mergecells("$mergeLetter2");
				
				for($x=2;$x<=$count;$x++)
				{
					
					$sheet->getStyle($debitLetter.$x)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
					$sheet->getStyle($creditLetter.$x)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
					$sheet->getStyle($balanceLetter.$x)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
					$sheet->getStyleByColumnAndRow(0, $x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
				}
				
				$sheet->getStyle("{$debitLetter}2:$debitLetter{$count}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->getStyle("{$creditLetter}2:$creditLetter{$count}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->getStyle("{$balanceLetter}2:$balanceLetter{$count}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				
			});
			
		})->download('xlsx');;
	}
	
}
