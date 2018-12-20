<?php

namespace App\Http\Controllers\Deposit;

use App\BankAccount;
use App\BankNo;
use App\BankStatement;
use App\Businessunit;
use App\Company;
use App\Deposit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Shared_Date;
use PHPExcel_Style_NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DepMatchingController extends Controller
{
    //
	protected $viewExcelData;
	public function __construct()
	{
		$this->middleware('auth');
		$this->viewExcelData = Array();
	}
	
	public function deposit()
	{
		$banks = BankAccount::select('id','bank','accountno','accountname','bankno')
			->where('buid',Auth::user()->bunitid)
			->where('company_code', Auth::user()->company_id)
			->get();
		//()->pluck('BankAccountList','id')->all();
		$content_title = "Deposit Matching";
		return view('deposit.home',compact('banks','content_title'));
	}
	
	public function fileList($bankID)
	{
		$bu      = Auth::user()->bunitid;
		$com     = Auth::user()->company_id;
		$bu1     = Businessunit::find($bu)->bname;
		$com1    = Company::find($com)->company;
		$path    = storage_path("exports/deposit-excel/$com1/$bu1");
		$month   = Array();
		$file    = Array();
		$list    = glob("$path/*.xlsx");
		$bankAct = BankAccount::find($bankID);
		$bAcct   = $bankAct->bank;
		$bNum    = $bankAct->accountno;
		$flist   = Array();
		foreach($list as $li):
			
			$exp        = explode("/",$li);
			$n          = explode(" ",$exp[4]);
			$bankName   = $n[5];
			$bankNumber = $n[7];
			if($bAcct == $bankName and $bNum == $bankNumber)
			{
				$flist[] = $li;
				$file[]  = $exp[4];
			}
		endforeach;
		return view('deposit.fileList',compact('file','bAcct','bNum','flist','bankID'));
	}
	
	public function viewExcel($file)
	{
//		$files = Storage::files('exports/sample-excel/');
//		echo $files;
		$arrayExcel = Array();
		Excel::load(base64_decode($file),function($reader){
			$objWorksheet = $reader->getActiveSheet();
			$highestRow   = $objWorksheet->getHighestRow();
			
			
			for($y=2;$y<=$highestRow;$y++):
				$entryno     = $objWorksheet->getCellByColumnAndRow(0,$y)->getValue();
				$bankno      = $objWorksheet->getCellByColumnAndRow(1,$y)->getValue();
				$postingdate = date('m/d/Y',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(2, $y)->getValue()));
				$docType     = $objWorksheet->getCellByColumnAndRow(3,$y)->getValue();
				$docno       = $objWorksheet->getCellByColumnAndRow(4,$y)->getValue();
				$extdocno    = $objWorksheet->getCellByColumnAndRow(5,$y)->getValue();
				$des         = $objWorksheet->getCellByColumnAndRow(6,$y)->getValue();
				$userID      = $objWorksheet->getCellByColumnAndRow(7,$y)->getValue();
				$amount      = $objWorksheet->getCellByColumnAndRow(8,$y)->getValue();
				$this->viewExcelData[] = [
					$entryno,
					$bankno,
					$postingdate,
					$docType,
					$docno,
					$extdocno,
					$des,
					$userID,
					$amount
				];
			endfor;
		});
		
		$excelData = $this->viewExcelData;
		
		return view('deposit.viewDSUploaded',compact('excelData'));
	}
	
	public function loadMonthlist($bankno)
	{
		$com  = Auth::user()->company_id;
		$bu   = Auth::user()->bunitid;
		$bnum = BankNo::find($bankno);
		$dep  = Deposit::select(DB::raw("distinct(DATE_FORMAT(posting_date,'%Y-%m')) as datein"))
			->where('bank_account_no',$bnum->bankno)
			->where('company',$com)
			->where('bu_unit',$bu)
			->orderBy('posting_date','asc')
			->get()->map(function ($model) {
				return $model->datein;

			})->all();
		$new_array = [];
		foreach ($dep as $value):
			$new_array[$value] = date("F, Y",strtotime($value));
		endforeach;
		//dd($new_array);
		return view('deposit.monthlist',compact('new_array'));
	}
	
	public function countBS(Request $request)
	{
		$file    = $request->filename;
		$bankId  = $request->bankAct;
		$exp     = explode(" ",$file);
		$month   = date("m",strtotime(trim($exp[2])));
		$year    = trim($exp[3]);
		$bankno  = BankAccount::find($bankId)->bankcode->bankno;
		$com     = Auth::user()->company_id;
		$bu      = Auth::user()->bunitid;
		
		$bank    = BankStatement::where('bank_account_no',$bankno)
								->whereMonth('bank_date',$month)
								->whereYear('bank_date',$year)
								->where('company',$com)
								->where('bu_unit',$bu)
								->count('bank_id');
		if($bank>0)
		{
			return $bank;
		}
		else
		{
			return date("F, Y",strtotime($year."-".$month)) ." - $exp[5] - $exp[7]";
		}
		
	}
	
	public function depMatching(Request $request)
	{
		session()->forget('bankAct');
		session()->forget('dateIn');
		session()->forget('bu');
		session()->forget('bu1');
		session()->forget('com');
		session()->forget('com1');
		session()->forget('bankno');
		
		session()->forget('bankDep');
		session()->forget('bookDep');
		session()->forget('extDocArray');
		
		session()->forget('sameDateAmt');
		session()->forget('DupsameDateAmt');
		session()->forget('plus5Days');
		session()->forget('minus5Days');
		session()->forget('batchDS');
		session()->forget('branchCode');
		session()->forget('unmatchBanK');
		session()->forget('unmatchBooK');
		
		$com         = Auth::user()->company_id;
		$bu          = Auth::user()->bunitid;
		$bankno      = BankAccount::find($request->bank_account)->bankcode->bankno;
		$month       = date('m',strtotime($request->datein));
		$year        = date('Y',strtotime($request->datein));
		$bsArray     = Array();
		$bkArray     = Array();
		$data        = Array();
		$data1       = Array();
		$bsDateArray = Array();
		$bkDateArray = Array();
		$extDocArray = Array();
		
		session(['com'=>$com]);
		session(['bu'=>$bu]);
		session(['bankno'=>$bankno]);
		
		$bA_1  = BankAccount::find($request->bank_account);
		$com_1 = Company::find(Auth::user()->company_id);
		$bu_1  = Businessunit::find(Auth::user()->bunitid);
		
		session(['bankAct'=>$bA_1]);
		session(['com1'=>$com_1]);
		session(['bu1'=>$bu_1]);
		session(['dateIn'=>$request->datein]);
		
		$selBs   = BankStatement::select('bank_date','bank_amount','description','bank_balance','bank_ref_no')
			->where('bank_account_no',$bankno)
			->whereMonth('bank_date',$month)
			->whereYear('bank_date',$year)
			->where('company',$com)
			->where('bu_unit',$bu)
			->where('type','AR')
			->orderBy('bank_date','asc')
			->get();

		$selBk   = Deposit::select('posting_date','bank_account_no','doc_no','ext_doc_no','amount','entry_no','users','description')
			->where('bank_account_no',$bankno)
			->whereMonth('posting_date',$month)
			->whereYear('posting_date',$year)
			->where('company',$com)
			->where('bu_unit',$bu)
			->get();
		
		foreach($selBk as $key => $bk)
		{
			$extDocArray[] = $bk->ext_doc_no;
		}

		session(['bankDep'=>$selBs]);
		session(['bookDep'=>$selBk]);
		session(['extDocArray'=>$extDocArray]);
		
		//$batchDS = $this->externalDoc();
		$sameDate = $this->sameDateAndAmount();
		//$this->duplicateEntry();
		//dd(count($batchDS),count($selBk));
		//return view('deposit.ajax.extDoc',compact('batchDS'));
		//dd($batchDS);
//		session(['bsArray' => $bsArray]);
//		session(['bkArray'=>$bkArray]);
//		session(['extDocArray'=>$extDocArray]);
//		session(['bkDateArray'=>$bkDateArray]);
//		session(['bsDateArray'=>$bsDateArray]);
		return view('deposit.matching.matchingDep',compact('sameDate'));
		//return view('deposit.matching.matchingDep');
		
	}
	
	
	public function sameDateAndAmount()
	{
		$selBs       = session()->get('bankDep');
		$selBk       = session()->get('bookDep');
		$com         = session()->get('com');
		$bu          = session()->get('bu');
		$bankno      = session()->get('bankno');
		$extDocArray = session()->get('extDocArray');
		$extdocArray = Array();
		$batchDS     = Array();
		$sameDate    = Array();
		//echo "BS data => " . count($selBs) ." Book data => ". count($selBk) . "</br>";
		
		$x = Array();
		$flag = Array();
		foreach ($selBs as $key => $bs)
		{
			//$exp     = explode("|",$bs);
			$bsdate  = $bs->bank_date;
			$bsdes   = $bs->description;
			$bsamt   = $bs->bank_amount;
			$tagamt  = $bsamt;
			$tagdate = $bsdate;
			$tagdes  = $bsdes;
			

			foreach($selBk as $key1 => $bk)
			{
				//$exp2    = explode("|",$bk);
				$bkdate  = $bk->posting_date;
				$docno   = $bk->doc_no;
				$extdoc  = $bk->ext_doc_no;
				$bkamt   = $bk->amount;
				$bkEntry = $bk->entry_no;
				$bkUsers = $bk->users;
				$bkDes   = $bk->description;
				
				if(trim(date("m/d/Y",strtotime($bkdate)))==trim(date("m/d/Y",strtotime($bsdate))) and trim($bkamt) == trim($bsamt))
				{
					$dep = \App\Deposit::where('posting_date',$bsdate)->where('amount',$bsamt)
						->where('bank_account_no',$bankno)
						->where('company',$com)
						->where('bu_unit',$bu)
						->count('id');
					if($dep==1)
					{
						$occurences = array_count_values($x);
						if(in_array($key, $x))
						{
							$var = $occurences[$key];
						}
						else
						{
							$var = 0;
						}
						if($var<=0)
						{
							$x[] = $key;
						}
						else
						{
							// $expBS   = explode('|',$bsArray[$key]);
							// 	$dateBS = $expBS[0];
							// 	$desBS  = $expBS[1];
							// 	$amtBS  = $expBS[2];
							$tagamt = "";
							$tagdate = "";
							$tagdes  = "";
							//$bsdes.="hahahah";
						}
						$bsDATE  = strtotime($tagdate);
						$tagdate = PHPExcel_Shared_Date::PHPToExcel($bsDATE);
						$bkDATE  = strtotime($bkdate);
						$bkdate  = PHPExcel_Shared_Date::PHPToExcel($bkDATE);

                                $sameDate[] = [$tagdate,$tagdes,$tagamt,' ',$bkEntry,$bkdate,$docno,$extdoc,$bkamt,$bkUsers,$bkDes];
                                session(['sameDateAmt'=>$sameDate]);
                                    unset($selBk[$key1]);
                                    unset($extDocArray[$key1]);
                                    unset($selBs[$key]);
                                   

					}
				}
			}
		}
		
		session()->forget('bankDep');
		session()->forget('bookDep');
		session()->forget('extDocArray');

		session(['bankDep'=>$selBs]);
		session(['bookDep'=>$selBk]);
		session(['extDocArray'=>$extDocArray]);
		//echo "BS data => " . count($selBs) ." Book data => ". count($selBk) . "</br>";
//		session(['bsArray' => $bsArray]);
//		session(['bkArray'=>$bkArray]);
//		session(['extDocArray'=>$extDocArray]);
//		session(['bkDateArray'=>$bkDateArray]);
//		session(['bsDateArray'=>$bsDateArray]);
		return $sameDate;
	}
	
	public function duplicateEntry()
	{
		$bsArray     = session()->get('bankDep');
		$bkArray     = session()->get('bookDep');
		$com         = session()->get('com');
		$bu          = session()->get('bu');
		$bankno      = session()->get('bankno');
		$extDocArray = session()->get('extDocArray');
		
		$dupEntry    = Array();
		
		$x = Array();
		$flag = Array();
		foreach ($bsArray as $key => $bs)
		{
			//$exp     = explode("|",$bs);
			$bsdate  = $bs->bank_date;
			$bsdes   = $bs->description;
			$bsamt   = $bs->bank_amount;
			$tagamt  = $bsamt;
			$tagdate = date("m/d/Y",strtotime($bsdate));
			$tagdes  = $bsdes;
			foreach($bkArray as $key1 => $bk)
			{
				//$exp2    = explode("|",$bk);
				$bkdate  = $bk->posting_date;
				$docno   = $bk->doc_no;
				$extdoc  = $bk->ext_doc_no;
				$bkamt   = $bk->amount;
				$bkEntry = $bk->entry_no;
				$bkUsers = $bk->users;
				$bkDes   = $bk->description;
				if(trim(date("m/d/Y",strtotime($bkdate)))==trim(date("m/d/Y",strtotime($bsdate))) and trim($bkamt) == trim($bsamt))
				{
					$dep = \App\Deposit::where('posting_date',date("Y-m-d",strtotime($bsdate)))
						->where('amount',$bsamt)
						->where('bank_account_no',$bankno)
						->where('company',$com)
						->where('bu_unit',$bu)
						->count('id');
					if($dep>1)
					{
						$occurences = array_count_values($x);
						if(in_array($key, $x))
						{
							$var = $occurences[$key];
						}
						else
						{
							$var = 0;
						}
						if($var<=0)
						{
							$x[] = $key;
						}
						else
						{
							// $expBS   = explode('|',$bsArray[$key]);
							// 	$dateBS = $expBS[0];
							// 	$desBS  = $expBS[1];
							// 	$amtBS  = $expBS[2];
							$tagamt  = "";
							$tagdate = "";
							$tagdes  = "";
							//$bsdes.="hahahah";
						}
						
						$bsDATE  = strtotime($tagdate);
						$tagdate = PHPExcel_Shared_Date::PHPToExcel($bsDATE);
						$bkDATE  = strtotime($bkdate);
						$bkdate  = PHPExcel_Shared_Date::PHPToExcel($bkDATE);
						
						$dupEntry[]  = [$tagdate,$tagdes,$tagamt,' ',$bkEntry,$bkdate,$docno,$extdoc,$bkamt,$bkUsers,$bkDes];
						session(['DupsameDateAmt'=>$dupEntry]);

                        }
					
					
					unset($bkArray[$key1]);
					unset($extDocArray[$key1]);
					unset($bsArray[$key]);
//					unset($bkDateArray[$key1]);
//					unset($bsDateArray[$key]);
				}
			}
		}
		
		session()->forget('bankDep');
		session()->forget('bookDep');
		session()->forget('extDocArray');
		
		session(['bankDep'=>$bsArray]);
		session(['bookDep'=>$bkArray]);
		session(['extDocArray'=>$extDocArray]);
//		session(['bsArray' => $bsArray]);
//		session(['bkArray'=>$bkArray]);
//		session(['extDocArray'=>$extDocArray]);
//		session(['bkDateArray'=>$bkDateArray]);
//		session(['bsDateArray'=>$bsDateArray]);
		
		//echo "bank data => ". count($bsArray) . " book data => " . count($bkArray);
		
		return view('deposit.matching.dupEntry',compact('dupEntry'));
	}
	
	public function plus5days()
	{
		$bsArray     = session()->get('bankDep');
		$bkArray     = session()->get('bookDep');
		$com         = session()->get('com');
		$bu          = session()->get('bu');
		$bankno      = session()->get('bankno');
		$extDocArray = session()->get('extDocArray');
		
		
		$plus5days   = Array();
		
		$x = Array();
		foreach($bsArray as $key => $bs)
		{
			
			$bsdate  = $bs->bank_date;
			$bsdes   = $bs->description;
			$bsamt   = $bs->bank_amount;
			$tagamt  = $bsamt;
			$tagdate = date("m/d/Y",strtotime($bsdate));
			$tagdes  = $bsdes;
			foreach ($bkArray as $key1 => $bk)
			{
				$bkdate  = $bk->posting_date;
				$docno   = $bk->doc_no;
				$extdoc  = $bk->ext_doc_no;
				$bkamt   = $bk->amount;
				$bkEntry = $bk->entry_no;
				$bkUsers = $bk->users;
				$bkDes   = $bk->description;
				
				// $day   = date('d',strtotime($bsdate));
				// $month = date('m',strtotime($bsdate));
				// $year  = date('Y',strtotime($bsdate));
				for($xx=1;$xx<=5;$xx++)
				{
					//echo $bkdate ."</br>";
					$date    = date_create("$bkdate");
					date_add($date, date_interval_create_from_date_string("$xx days"));
					$bookdate      = date_format($date, 'Y-m-d');
					if(trim(date("Y-m-d",strtotime($bookdate)))==trim(date("Y-m-d",strtotime($bsdate))) and trim($bkamt)==trim($bsamt))
					{
						$occurences = array_count_values($x);
						if(in_array($key, $x))
						{
							$var = $occurences[$key];
						}
						else
						{
							$var = 0;
						}
						if($var<=0)
						{
							$x[] = $key;
						}
						else
						{
							$tagamt = "";
							$tagdate = "";
							$tagdes  = "";
						}
						
						$bsDATE  = strtotime($tagdate);
						$tagdate = PHPExcel_Shared_Date::PHPToExcel($bsDATE);
						$bkDATE  = strtotime($bkdate);
						$bkdateExcel  = PHPExcel_Shared_Date::PHPToExcel($bkDATE);
						
						$plus5days[] = [$tagdate,$tagdes,$tagamt,' ',$bkEntry,$bkdateExcel,$docno,$extdoc,$bkamt,$bkUsers,$bkDes];
						session(['plus5Days'=>$plus5days]);

			
			           unset($bkArray[$key1]);
			           unset($extDocArray[$key1]);
			           unset($bsArray[$key]);


                    }
				}
			}
		}
		session()->forget('bankDep');
		session()->forget('bookDep');
		session()->forget('extDocArray');
		
		session(['bankDep'=>$bsArray]);
		session(['bookDep'=>$bkArray]);
		session(['extDocArray'=>$extDocArray]);

		return view('deposit.matching.plus5days',compact('plus5days'));
	}
	
	public function minus5days()
	{
		$bsArray     = session()->get('bankDep');
		$bkArray     = session()->get('bookDep');
		$com         = session()->get('com');
		$bu          = session()->get('bu');
		$bankno      = session()->get('bankno');
		$extDocArray = session()->get('extDocArray');
		
		$minus5days   = Array();
		
		$x = Array();
		foreach($bsArray as $key => $bs)
		{
			
			$bsdate  = $bs->bank_date;
			$bsdes   = $bs->description;
			$bsamt   = $bs->bank_amount;
			$tagamt  = $bsamt;
			$tagdate = date("m/d/Y",strtotime($bsdate));
			$tagdes  = $bsdes;
			foreach ($bkArray as $key1 => $bk)
			{
				$bkdate  = $bk->posting_date;
				$docno   = $bk->doc_no;
				$extdoc  = $bk->ext_doc_no;
				$bkamt   = $bk->amount;
				$bkEntry = $bk->entry_no;
				$bkUsers = $bk->users;
				$bkDes   = $bk->description;
				
				// $day   = date('d',strtotime($bsdate));
				// $month = date('m',strtotime($bsdate));
				// $year  = date('Y',strtotime($bsdate));
				for($xx=1;$xx<=5;$xx++)
				{
					$date    = date_create("$bkdate");
					date_sub($date, date_interval_create_from_date_string("$xx days"));
					$bookdate      = date_format($date, 'Y-m-d');
					if(trim(date("Y-m-d",strtotime($bookdate)))==trim(date("Y-m-d",strtotime($bsdate))) and trim($bkamt)==trim($bsamt))
					{
						$occurences = array_count_values($x);
						if(in_array($key, $x))
						{
							$var = $occurences[$key];
						}
						else
						{
							$var = 0;
						}
						if($var<=0)
						{
							$x[] = $key;
						}
						else
						{
							$tagamt = "";
							$tagdate = "";
							$tagdes  = "";
						}
						
						$bsDATE  = strtotime($tagdate);
						$tagdate = PHPExcel_Shared_Date::PHPToExcel($bsDATE);
						$bkDATE  = strtotime($bkdate);
						$bkdateExcel  = PHPExcel_Shared_Date::PHPToExcel($bkDATE);
						
						$minus5days[] = [$tagdate,$tagdes,$tagamt,' ',$bkEntry,$bkdateExcel,$docno,$extdoc,$bkamt,$bkUsers,$bkDes];
						session(['minus5Days'=>$minus5days]);

			
			           unset($bkArray[$key1]);
			           unset($extDocArray[$key1]);
			           unset($bsArray[$key]);


                    }
				}
			}
		}
		session()->forget('bankDep');
		session()->forget('bookDep');
		session()->forget('extDocArray');
		
		session(['bankDep'=>$bsArray]);
		session(['bookDep'=>$bkArray]);
		session(['extDocArray'=>$extDocArray]);

		return view('deposit.matching.minus5days',compact('minus5days'));
	}
	
	public function externalDoc()
	{
		$selBs = session()->get('bankDep');
		$selBk = session()->get('bookDep');
		$com         = session()->get('com');
		$bu          = session()->get('bu');
		$bankno      = session()->get('bankno');
		$extdocArray = Array();
		$batchDS     = Array();
		
		$extDocArray = session()->get('extDocArray');
		
		foreach ($selBk as $key1 => $row)
		{
			$bkdate = $row->posting_date;
			$docno  = $row->doc_no;
			$extdoc = $row->ext_doc_no;
			$bkamt  = $row->amount;
			$preg   = preg_match('/^DS#/',$extdoc);
			if($preg > 0)
			{
				$extdocArray[] = $extdoc;
				$occurences    = array_count_values($extdocArray);
				if($occurences[$extdoc] ==1)
				{
					$sumtotal = Deposit::where('ext_doc_no',$extdoc)
						->where('company',$com)
						->where('bu_unit',$bu)
						->sum('amount');
					//	echo "$bkdate => $extdoc => $sumtotal </br>";
					foreach($selBs as $key => $bs)
					{
						$exp     = explode("|",$bs);
						$bsdate  = $bs->bank_date;
						$bsdes   = $bs->description;
						$bsamt   = $bs->bank_amount;
						if(trim($sumtotal)==trim($bsamt))
						{
							//echo "$extdoc => $sumtotal == $bsamt </br>";
							$allbook = Deposit::select('posting_date','amount','doc_no','ext_doc_no','entry_no','users','description')
								->where('ext_doc_no',$extdoc)
								->where('bank_account_no',$bankno)
								->where('company',$com)
								->where('bu_unit',$bu)
								->get();
							unset($selBs[$key]);
							
							$bkArrayKeys = array_keys($extDocArray,$extdoc);
							foreach($bkArrayKeys as $keys)
							{
								unset($selBk[$keys]);
							}
							
							foreach($allbook as $book):

								$bkDATE  = strtotime($book->posting_date);
								$bkdate  = PHPExcel_Shared_Date::PHPToExcel($bkDATE);
								$batchDS[] = [$book->entry_no,$bkdate,$book->doc_no,$book->ext_doc_no,$book->amount,$book->users,$book->description,'','','',''];
							endforeach;
							$bsDATE  = strtotime($bsdate);
							$bsdate = PHPExcel_Shared_Date::PHPToExcel($bsDATE);
							$batchDS[] = ['','','','','','','',$sumtotal,$bsdate,$bsdes,$bsamt];
							session(['batchDS'=>$batchDS]);
						}
					}
				}
			}
		}
		
		session(['bankDep' => $selBs]);
		session(['bookDep'=>$selBk]);
		session(['extDocArray'=>$extDocArray]);
		
		return view('deposit.matching.dsNumber',compact('batchDS'));
	}
	
	public function branchCode()
	{
		$selBs = session()->get('bankDep');
		$selBk = session()->get('bookDep');
		$com         = session()->get('com');
		$bu          = session()->get('bu');
		$bankno      = session()->get('bankno');
		$extdocArray = Array();
		$batchDS     = Array();
		$branchCode  = Array();
		$extDocArray = session()->get('extDocArray');
		$dateIn      = session()->get('dateIn');
		$month       = date("m",strtotime(trim($dateIn)));
		$year        = date("Y",strtotime(trim($dateIn)));
		
		foreach($selBs as $key => $bs)
		{
			$bsamount = $bs->bank_amount;
			$bsDes    = $bs->description;
			$bsdate   = $bs->bank_date;
			$bsDATE   = strtotime($bsdate);
			$bsdate   = PHPExcel_Shared_Date::PHPToExcel($bsDATE);
			$bsCode = "BRC#".$bs->bank_ref_no;
			
			foreach($selBk as $key1=> $bk)
			{
				$bk_amt  = $bk->amount;
				$entryno = $bk->entry_no;
				$docno   = $bk->doc_no;
				$extDoc  = $bk->ext_doc_no;
				$bkUsers = $bk->users;
				$bkDes   = $bk->description;
				
				$bkdate  = $bk->posting_date;
				$bkDATE  = strtotime($bkdate);
				$bkdate  = PHPExcel_Shared_Date::PHPToExcel($bkDATE);
				
				$preg    = preg_match("/$bsCode/",$bk->ext_doc_no);
				if($preg == 1 and trim($bk_amt) == trim($bsamount))
				{
					$allbook = Deposit::select('posting_date','amount','doc_no','ext_doc_no','entry_no','users','description')
						->where('ext_doc_no','REGEXP',$bsCode)
						->where('bank_account_no',$bankno)
						->whereMonth('posting_date',$month)
						->whereYear('posting_date',$year)
						->where('amount',$bsamount)
						->where('company',$com)
						->where('bu_unit',$bu)
						->get();
					if($allbook->count('id')>0)
					{
						//echo  $bsCode . " => ". $amount."</br>";
						$branchCode[] = [$bsdate,$bsDes,$bsamount,' ',$entryno,$bkdate,$docno,$extDoc,$bk_amt,$bkUsers,$bkDes];
						session(['branchCode'=>$branchCode]);
					}
				}
			}
		}
		
		session(['bankDep' => $selBs]);
		session(['bookDep'=>$selBk]);
		session(['extDocArray'=>$extDocArray]);
		
		return view('deposit.matching.branchCode',compact('branchCode'));
	}
	
	public function unMatchBS()
	{
		$unmatchBS = session()->get('bankDep');
//		$unmatchBS = $unmatchBS->map(function($item,$key)
//		{
//			return (object)[
//				'bank_date'=>$item->bank_date,
//				'description'=>$item->description,
//				'bank_amount'=>$item->bank_amount
//			];
//		});
//		//date('m/d/Y',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(2, $y)->getValue()))
//		$unmatchBSExcel = $unmatchBS->map(function($item,$key)
//		{
//			$bsDATE  = strtotime($item->bank_date);
//			$bsdate  = PHPExcel_Shared_Date::PHPToExcel($bsDATE);
//			return (object)[
//				'bank_date'=>date("m/d/Y",strtotime($bsdate)),
//				'description'=>$item->description,
//				'bank_amount'=>$item->bank_amount
//			];
//		});
		
		session(['unmatchBanK'=>$unmatchBS]);
		return view('deposit.matching.unmatchBS',compact('unmatchBS'));
	}
	
	public function unMatchBK()
	{
		$unmatchBK  = session()->get('bookDep');
//		//dd($unmatchBK->first());
//		$unmatchBK = $unmatchBK->map(function ($item, $key) {
//			return [
//				'entry_no'=>$item->entry_no,
//				'posting_date'=>$item->posting_date,
//				'doc_no'=>$item->doc_no,
//				'ext_doc_no'=>$item->ext_doc_no,
//				'amount'=>$item->amount,
//				'users'=>$item->users,
//				'description'=>$item->description
//			];
//		});
//
//		$unmatchBKExcel = $unmatchBK->map(function ($item, $key) {
//			$bkDATE  = strtotime($item->posting_date);
//			$bkdate  = PHPExcel_Shared_Date::PHPToExcel($bkDATE);
//			return (object)[
//				'entry_no'=>$item->entry_no,
//				'posting_date'=>$bkdate,
//				'doc_no'=>$item->doc_no,
//				'ext_doc_no'=>$item->ext_doc_no,
//				'amount'=>$item->amount,
//				'users'=>$item->users,
//				'description'=>$item->description
//			];
//		});
			//dd($unmatchBK);
		session(['unmatchBooK'=>$unmatchBK]);
		return view('deposit.matching.unmatchBK',compact('unmatchBK'));
	}
	
	public function depExcel()
	{
		$bankAct_1 = session()->get('bankAct');
		Excel::create("Deposit ".date("F, Y",strtotime(session()->get('dateIn')))." $bankAct_1->bank - $bankAct_1->accountno", function($excel)  {
			
			// Set the title
			$excel->setTitle('DISBURSEMENT SUMMARY REPORTS');
			// Chain the setters
			$excel->setCreator('BRS PROGRAMMER REY JOSEPH T. BAAY, JELLARY CADUTDUT,GLENN MICHAEL MEJEIAS')->setCompany('Coporate AGC');
			$excel->setDescription('Summary Reports of disbursement of book and bank');
			/*
			 |----------------------------------------------------------------------------------------------------------------------------
			 | Same Date and Amount
			 |----------------------------------------------------------------------------------------------------------------------------
			*/
			$excel->sheet('Same Date and Amount', function ($sheet)  {
				
				$sheet->setOrientation('landscape');
				//$count = count(session()->get('sameDateAmt')) + 5;
				$count = session()->get('sameDateAmt')!=''?count(session()->get('sameDateAmt'))+5:0 + 5;
				$sheet->setBorder('A4:K'.$count, 'thin');
				
				$headings = array('BANK DATE', 'DESCREPTION','BANK AMOUNT','','ENTRY NO','BOOK DATE','DOCUMENT NO','EXTERNAL DOCUMENT NO','AMOUNT','USER ID','DESCRIPTION');
				$sheet->prependRow(5, $headings);
				$sheet->fromArray(session()->get('sameDateAmt'), NULL, 'A6',false,false);
				$sheet->mergecells('A4:K4');
				$sheet->mergecells('A1:K1');
				
				for($s=4;$s<=$count;$s++)
				{
					$sheet->getStyleByColumnAndRow(0, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyleByColumnAndRow(5, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyle('C'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
					$sheet->getStyle('I'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
					
				}
				
				$countall = $count + 1;
				$sheet->setCellValue("H{$countall}", "TOTAL");
				$sheet->getStyle("I6:I{$countall}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->setCellValue("I{$countall}", "=SUM(I6:I{$count})");
				
				$sheet->setCellValue("B{$countall}", "TOTAL");
				$sheet->getStyle("C6:C{$countall}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->setCellValue("C{$countall}", "=SUM(C6:C{$count})");
				
				$sheet->row(4,array('Deposit Same Date and Amount'));
				$com_1     = session()->get('com1');
				$bu_1      = session()->get('bu1');
				$bankAct_1 = session()->get('bankAct');
				
				$sheet->row(1,array("$com_1->company - $bu_1->bname : $bankAct_1->bank - $bankAct_1->accountno"));
				$sheet->row(2,array(date("F, Y",strtotime(session()->get('dateIn')))));
				$sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
			});
			
			/*
			 |----------------------------------------------------------------------------------------------------------------------------
			 |  Duplicate Same Date and Amount
			 |----------------------------------------------------------------------------------------------------------------------------
			*/
			$excel->sheet('Duplicate Same Date and Amount', function ($sheet)  {
				
				$sheet->setOrientation('landscape');
				//$count = count(session()->get('DupsameDateAmt')) + 5;
				$count = session()->get('DupsameDateAmt')!=''?count(session()->get('DupsameDateAmt'))+5:0 + 5;
				$sheet->setBorder('A4:K'.$count, 'thin');
				
				$headings = array('BANK DATE', 'DESCREPTION','BANK AMOUNT','','ENTRY NO','BOOK DATE','DOCUMENT NO','EXTERNAL DOCUMENT NO','AMOUNT','USER ID','DESCRIPTION');
				$sheet->prependRow(5, $headings);
				$sheet->fromArray(session()->get('DupsameDateAmt'), NULL, 'A6',false,false);
				$sheet->mergecells('A4:K4');
				$sheet->mergecells('A1:K1');
				
				for($s=4;$s<=$count;$s++)
				{
					$sheet->getStyleByColumnAndRow(0, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyleByColumnAndRow(5, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyle('C'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
					$sheet->getStyle('I'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
				}
				
				$countall = $count + 1;
				$sheet->setCellValue("H{$countall}", "TOTAL");
				$sheet->getStyle("I6:I{$countall}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->setCellValue("I{$countall}", "=SUM(I6:I{$count})");
				
				$sheet->setCellValue("B{$countall}", "TOTAL");
				$sheet->getStyle("C6:C{$countall}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->setCellValue("C{$countall}", "=SUM(C6:C{$count})");
				
				$sheet->row(4,array('Duplicate Same Date and Amount'));
				$com_1     = session()->get('com1');
				$bu_1      = session()->get('bu1');
				$bankAct_1 = session()->get('bankAct');
				
				$sheet->row(1,array("$com_1->company - $bu_1->bname : $bankAct_1->bank - $bankAct_1->accountno"));
				$sheet->row(2,array(date("F, Y",strtotime(session()->get('dateIn')))));
				$sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
			});
			/*
			 |----------------------------------------------------------------------------------------------------------------------------
			 |  Plus 5 Days
			 |----------------------------------------------------------------------------------------------------------------------------
			*/
			$excel->sheet('Plus 5 Days', function ($sheet)  {
				
				$sheet->setOrientation('landscape');
				//$count = count(session()->get('plus5Days')) + 5;
				$count = session()->get('plus5Days')!=''?count(session()->get('plus5Days'))+5:0 + 5;
				$sheet->setBorder('A4:K'.$count, 'thin');
				
				$headings = array('BANK DATE', 'DESCREPTION','BANK AMOUNT','','ENTRY NO','BOOK DATE','DOCUMENT NO','EXTERNAL DOCUMENT NO','AMOUNT','USER ID','DESCRIPTION');
				$sheet->prependRow(5, $headings);
				$sheet->fromArray(session()->get('plus5Days'), NULL, 'A6',false,false);
				$sheet->mergecells('A4:K4');
				$sheet->mergecells('A1:K1');
				
				for($s=4;$s<=$count;$s++)
				{
					$sheet->getStyleByColumnAndRow(0, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyleByColumnAndRow(5, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyle('C'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
					$sheet->getStyle('I'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
				}
				
				$countall = $count + 1;
				$sheet->setCellValue("H{$countall}", "TOTAL");
				$sheet->getStyle("I6:I{$countall}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->setCellValue("I{$countall}", "=SUM(I6:I{$count})");
				
				$sheet->setCellValue("B{$countall}", "TOTAL");
				$sheet->getStyle("C6:C{$countall}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->setCellValue("C{$countall}", "=SUM(C6:C{$count})");
				
				
				$sheet->row(4,array('Plus 5 Days'));
				$com_1     = session()->get('com1');
				$bu_1      = session()->get('bu1');
				$bankAct_1 = session()->get('bankAct');
				
				$sheet->row(1,array("$com_1->company - $bu_1->bname : $bankAct_1->bank - $bankAct_1->accountno"));
				$sheet->row(2,array(date("F, Y",strtotime(session()->get('dateIn')))));
				$sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
			});
			/*
			 |----------------------------------------------------------------------------------------------------------------------------
			 |  Minus 5 Days
			 |----------------------------------------------------------------------------------------------------------------------------
			*/
			$excel->sheet('Minus 5 Days', function ($sheet)  {
				
				$sheet->setOrientation('landscape');
				//$count = count(session()->get('minus5Days')) + 5;
				$count = session()->get('minus5Days')!=''?count(session()->get('minus5Days'))+5:0 + 5;
				$sheet->setBorder('A4:K'.$count, 'thin');
				
				$headings = array('BANK DATE', 'DESCREPTION','BANK AMOUNT','','ENTRY NO','BOOK DATE','DOCUMENT NO','EXTERNAL DOCUMENT NO','AMOUNT','USER ID','DESCRIPTION');
				$sheet->prependRow(5, $headings);
				$sheet->fromArray(session()->get('minus5Days'), NULL, 'A6',false,false);
				$sheet->mergecells('A4:K4');
				$sheet->mergecells('A1:K1');
				
				for($s=4;$s<=$count;$s++)
				{
					$sheet->getStyleByColumnAndRow(0, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyleByColumnAndRow(5, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyle('C'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
					$sheet->getStyle('I'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
				}
				
				$countall = $count + 1;
				$sheet->setCellValue("H{$countall}", "TOTAL");
				$sheet->getStyle("I6:I{$countall}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->setCellValue("I{$countall}", "=SUM(I6:I{$count})");
				
				$sheet->setCellValue("B{$countall}", "TOTAL");
				$sheet->getStyle("C6:C{$countall}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->setCellValue("C{$countall}", "=SUM(C6:C{$count})");
				
				$sheet->row(4,array('Minus 5 Days'));
				$com_1     = session()->get('com1');
				$bu_1      = session()->get('bu1');
				$bankAct_1 = session()->get('bankAct');
				
				$sheet->row(1,array("$com_1->company - $bu_1->bname : $bankAct_1->bank - $bankAct_1->accountno"));
				$sheet->row(2,array(date("F, Y",strtotime(session()->get('dateIn')))));
				$sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
			});
			/*
			 |----------------------------------------------------------------------------------------------------------------------------
			 |  Match By DS No
			 |----------------------------------------------------------------------------------------------------------------------------
			*/
			$excel->sheet('Match By DS No', function ($sheet)  {
				
				$sheet->setOrientation('landscape');
				//$count = count(session()->get('batchDS')) + 5;
				$count = session()->get('batchDS')!=''?count(session()->get('batchDS'))+5:0 + 5;
				$sheet->setBorder('A4:K'.$count, 'thin');
				
				$headings = array('ENTRY NO','BOOK DATE','DOCUMENT NO','EXTERNAL DOCUMENT NO','AMOUNT','USER ID','DESCRIPTION','TOTAL','BANK DATE', 'DESCREPTION','BANK AMOUNT');
				$sheet->prependRow(5, $headings);
				$sheet->fromArray(session()->get('batchDS'), NULL, 'A6',false,false);
				$sheet->mergecells('A4:K4');
				$sheet->mergecells('A1:K1');
				
				for($s=4;$s<=$count;$s++)
				{
					$sheet->getStyleByColumnAndRow(1, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyleByColumnAndRow(8, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					
					$sheet->getStyle('E'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
					$sheet->getStyle('H'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
					$sheet->getStyle('K'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
				}
				
				$countall = $count + 1;
				$sheet->setCellValue("D{$countall}", "TOTAL");
				$sheet->getStyle("E6:E{$countall}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->setCellValue("E{$countall}", "=SUM(E6:E{$count})");
				
				$sheet->setCellValue("G{$countall}", "TOTAL");
				$sheet->getStyle("H6:H{$countall}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->setCellValue("H{$countall}", "=SUM(H6:H{$count})");
				
				$sheet->setCellValue("J{$countall}", "TOTAL");
				$sheet->getStyle("K6:K{$countall}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->setCellValue("K{$countall}", "=SUM(K6:K{$count})");
				
				
				$sheet->row(4,array('Match by Book DS No'));
				$com_1     = session()->get('com1');
				$bu_1      = session()->get('bu1');
				$bankAct_1 = session()->get('bankAct');
				
				$sheet->row(1,array("$com_1->company - $bu_1->bname : $bankAct_1->bank - $bankAct_1->accountno"));
				$sheet->row(2,array(date("F, Y",strtotime(session()->get('dateIn')))));
				$sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
			});

			/*
 |----------------------------------------------------------------------------------------------------------------------------
 |  Branch Code
 |----------------------------------------------------------------------------------------------------------------------------
*/
			$excel->sheet('Match by branch code', function ($sheet)  {
				
				$sheet->setOrientation('landscape');
				$br    = session()->get('branchCode');
				$count = $br==null?0+5:count($br) + 5;
				$sheet->setBorder('A4:K'.$count, 'thin');
				
				$headings = array('BANK DATE', 'DESCREPTION','BANK AMOUNT','','ENTRY NO','BOOK DATE','DOCUMENT NO','EXTERNAL DOCUMENT NO','AMOUNT','USER ID','DESCRIPTION');
				$sheet->prependRow(5, $headings);
				$sheet->fromArray(session()->get('branchCode'), NULL, 'A6',false,false);
				$sheet->mergecells('A4:K4');
				$sheet->mergecells('A1:K1');
				
				for($s=4;$s<=$count;$s++)
				{
					$sheet->getStyleByColumnAndRow(0, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyleByColumnAndRow(5, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyle('C'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
					$sheet->getStyle('I'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
				}
				
				$countall = $count + 1;
				$sheet->setCellValue("H{$countall}", "TOTAL");
				$sheet->getStyle("I6:I{$countall}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->setCellValue("I{$countall}", "=SUM(I6:I{$count})");
				
				$sheet->setCellValue("B{$countall}", "TOTAL");
				$sheet->getStyle("C6:C{$countall}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->setCellValue("C{$countall}", "=SUM(C6:C{$count})");
				
				$sheet->row(4,array('Matching by Branch Code'));
				$com_1     = session()->get('com1');
				$bu_1      = session()->get('bu1');
				$bankAct_1 = session()->get('bankAct');
				
				$sheet->row(1,array("$com_1->company - $bu_1->bname : $bankAct_1->bank - $bankAct_1->accountno"));
				$sheet->row(2,array(date("F, Y",strtotime(session()->get('dateIn')))));
				$sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
			});			
			
			
			/*
			 |----------------------------------------------------------------------------------------------------------------------------
			 |  Zero Out Amount in Bank
			 |----------------------------------------------------------------------------------------------------------------------------
			*/
			$excel->sheet('ZeroOut in Bank', function ($sheet)  {
//				[date("m/d/Y",strtotime($tagdate)),$tagdes,number_format($tagamt,2)];
				$posArray = Array();
				
				$unMatchBS = session()->get('unmatchBanK');
				//dd(session()->get('unmatchBanK'));
				foreach($unMatchBS as $keys => $bsdata)
				{
					$bsdate  = $bsdata->bank_date;
					$bsDes   = $bsdata->description;
					$bsamt   = str_replace(",","",$bsdata->bank_amount);
					$bsDATE  = strtotime($bsdate);
					$bsdate  = PHPExcel_Shared_Date::PHPToExcel($bsDATE);
					
					if($bsamt > 0)
					{
						foreach($unMatchBS as $keys2 => $bsdata2)
						{
							$bsdate2  = $bsdata2->bank_date;
							$bsDes2   = $bsdata2->description;
							$bsamt2   = str_replace(",","",$bsdata2->bank_amount);
							$bsDATE2  = strtotime($bsdate2);
							$bsdate2  = PHPExcel_Shared_Date::PHPToExcel($bsDATE2);
							if($bsamt2 < 0)
							{
								$diff = $bsamt + $bsamt2;
								if($diff == 0)
								{
									
									unset($unMatchBS[$keys]);
									unset($unMatchBS[$keys2]);
									for($x=1;$x<=3;$x++)
									{
										
										if($x==1)
										{
											$posArray[] = [$bsdate,$bsDes,number_format($bsamt,2)];
										}
										elseif($x==2)
										{
											$posArray[] = [$bsdate2,$bsDes2,number_format($bsamt2,2)];
										}
										else
										{
											$posArray[] = ['','',number_format(0,2)];
										}
									}
									break;
								}
							}
						}
					}
				}
				
				session()->forget('unmatchBanK');
				session(['unmatchBanK'=>$unMatchBS]);
				
				$sheet->setOrientation('landscape');
				$count = count($posArray) + 5;
				$sheet->setBorder('A4:C'.$count, 'thin');
				
				$headings = array('BANK DATE', 'DESCREPTION','BANK AMOUNT');
				$sheet->prependRow(5, $headings);
				$sheet->fromArray($posArray, NULL, 'A6',false,false);
				$sheet->mergecells('A4:C4');
				$sheet->mergecells('A1:C1');
				
				for($s=4;$s<=$count;$s++)
				{
					$sheet->getStyleByColumnAndRow(0, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyleByColumnAndRow(2, $s)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					$sheet->getStyle('C'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
				}
				

				
				$sheet->row(4,array('ZeroOut Amount in Bank'));
				$com_1     = session()->get('com1');
				$bu_1      = session()->get('bu1');
				$bankAct_1 = session()->get('bankAct');
				
				$sheet->row(1,array("$com_1->company - $bu_1->bname : $bankAct_1->bank - $bankAct_1->accountno"));
				$sheet->row(2,array(date("F, Y",strtotime(session()->get('dateIn')))));
				$sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
			});
			/*
			 |----------------------------------------------------------------------------------------------------------------------------
			 |  Unmatch in Bank
			 |----------------------------------------------------------------------------------------------------------------------------
			*/
			$excel->sheet('Unmatch in Bank', function ($sheet)  {
				
				$sheet->setOrientation('landscape');
				//$count = count(session()->get('unmatchBanK')) + 5;
				$count = session()->get('unmatchBanK')!=''?count(session()->get('unmatchBanK'))+5:0 + 5;
				//dd(session()->get('unmatchBanK'));
				$sheet->setBorder('A4:C'.$count, 'thin');
				
				$arrayData = Array();
				foreach(session()->get('unmatchBanK') as $key => $data):
					$bsDATE  = strtotime($data->bank_date);
					$bsdate  = PHPExcel_Shared_Date::PHPToExcel($bsDATE);
					$arrayData[] = [$bsdate,$data->description,$data->bank_amount];
				endforeach;
				
				$headings = array('BANK DATE', 'DESCREPTION','BANK AMOUNT');
				$sheet->prependRow(5, $headings);
				$sheet->fromArray($arrayData, NULL, 'A6',false,false);
				$sheet->mergecells('A4:C4');
				$sheet->mergecells('A1:C1');
				
				for($s=4;$s<=$count;$s++)
				{
					$sheet->getStyleByColumnAndRow(0, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);

					$sheet->getStyle('C'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
				}
				
				$countall = $count + 1;
				$sheet->setCellValue("B{$countall}", "TOTAL");
				$sheet->getStyle("C6:C{$countall}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->setCellValue("C{$countall}", "=SUM(C6:C{$count})");
				
				$sheet->row(4,array('Unmatch in Bank'));
				$com_1     = session()->get('com1');
				$bu_1      = session()->get('bu1');
				$bankAct_1 = session()->get('bankAct');
				
				$sheet->row(1,array("$com_1->company - $bu_1->bname : $bankAct_1->bank - $bankAct_1->accountno"));
				$sheet->row(2,array(date("F, Y",strtotime(session()->get('dateIn')))));
				$sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
			});
			
			/*
			 |----------------------------------------------------------------------------------------------------------------------------
			 |  Zero Out Amount in Book
			 |----------------------------------------------------------------------------------------------------------------------------
			*/
			$excel->sheet('ZeroOut in Book', function ($sheet)  {
				//[$bkEntry,date("m/d/Y",strtotime($bkdate)),$docno,$extdoc,number_format($bkamt,2),$bkUsers,$bkDes];
				$posArray = Array();
				$negArray = Array();
				$zeroAmt  = Array();
				$unMatchBK = session()->get('unmatchBooK');
				foreach($unMatchBK as $keys => $bkdata)
				{
					$entryno = $bkdata->entry_no;
					$bkdate  = $bkdata->posting_date;
					$bkamt   = str_replace(",","",$bkdata->amount);
					$docno   = $bkdata->doc_no;
					$extdoc  = $bkdata->ext_doc_no;
					$userID  = $bkdata->users;
					$bkDes   = $bkdata->description;
					
					$bkDATE  = strtotime($bkdate);
					$bkdate  = PHPExcel_Shared_Date::PHPToExcel($bkDATE);
					
					if($bkamt > 0)
					{
						foreach($unMatchBK as $keys2 => $bkdata2)
						{
							$entryno2 = $bkdata2->entry_no;
							$bkdate2  = $bkdata2->posting_date;
							$bkamt2   = str_replace(",","",$bkdata2->amount);
							$docno2   = $bkdata2->doc_no;
							$extdoc2  = $bkdata2->ext_doc_no;
							$userID2  = $bkdata2->users;
							$bkDes2   = $bkdata2->description;
							
							$bkDATE2  = strtotime($bkdate2);
							$bkdate2  = PHPExcel_Shared_Date::PHPToExcel($bkDATE2);
							if($bkamt2 < 0)
							{
								$diff = $bkamt + $bkamt2;
								if($diff == 0)
								{
									
									unset($unMatchBK[$keys]);
									unset($unMatchBK[$keys2]);
									for($x=1;$x<=3;$x++)
									{
										
										if($x==1)
										{
											$posArray[] = [$entryno,$bkdate,$docno,$extdoc,number_format($bkamt,2),$userID,$bkDes];
										}
										elseif($x==2)
										{
											$posArray[] = [$entryno2,$bkdate2,$docno2,$extdoc2,number_format($bkamt2,2),$userID2,$bkDes2];
										}
										else
										{
											$posArray[] = ['','','','',number_format(0,2),'',''];
										}
									}
									break;
								}
							}
						}
					}
				}
				
				session()->forget('unmatchBooK');
				session(['unmatchBooK'=>$unMatchBK]);
				
				$sheet->setOrientation('landscape');
				$count = count($posArray) + 5;
				$sheet->setBorder('A4:G'.$count, 'thin');
				
				$headings = array('ENTRY NO','BOOK DATE','DOCUMENT NO','EXTERNAL DOCUMENT NO','AMOUNT','USER ID','DESCRIPTION');
				$sheet->prependRow(5, $headings);
				$sheet->fromArray($posArray, NULL, 'A6',false,false);
				$sheet->mergecells('A4:G4');
				$sheet->mergecells('A1:G1');
				
				for($s=4;$s<=$count;$s++)
				{
					$sheet->getStyleByColumnAndRow(1, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyleByColumnAndRow(4, $s)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					$sheet->getStyle('E'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
				}
				
				
				$sheet->row(4,array('ZeroOut Amount in Book'));
				$com_1     = session()->get('com1');
				$bu_1      = session()->get('bu1');
				$bankAct_1 = session()->get('bankAct');
				
				$sheet->row(1,array("$com_1->company - $bu_1->bname : $bankAct_1->bank - $bankAct_1->accountno"));
				$sheet->row(2,array(date("F, Y",strtotime(session()->get('dateIn')))));
				$sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
			});
			/*
			 |----------------------------------------------------------------------------------------------------------------------------
			 |  Unmatch in Book
			 |----------------------------------------------------------------------------------------------------------------------------
			*/
			$excel->sheet('Unmatch in Book', function ($sheet)  {
				
				$sheet->setOrientation('landscape');
				//$count = count(session()->get('unmatchBooK')) + 5;
				$count = session()->get('unmatchBooK')!=''?count(session()->get('unmatchBooK'))+5:0 + 5;
				$sheet->setBorder('A4:G'.$count, 'thin');
				
				$arrayData  = Array();
				foreach(session()->get('unmatchBooK') as $key => $item):
					$bkDATE  = strtotime($item->posting_date);
					$bkdate  = PHPExcel_Shared_Date::PHPToExcel($bkDATE);
					$arrayData[] = [
							'entry_no'=>$item->entry_no,
							'posting_date'=>$bkdate,
							'doc_no'=>$item->doc_no,
							'ext_doc_no'=>$item->ext_doc_no,
							'amount'=>$item->amount,
							'users'=>$item->users,
							'description'=>$item->description
						];
				endforeach;
				
				$headings = array('ENTRY NO','BOOK DATE','DOCUMENT NO','EXTERNAL DOCUMENT NO','AMOUNT','USER ID','DESCRIPTION');
				$sheet->prependRow(5, $headings);
				$sheet->fromArray($arrayData, NULL, 'A6',false,false);
				$sheet->mergecells('A4:G4');
				$sheet->mergecells('A1:G1');
				
				for($s=4;$s<=$count;$s++)
				{
					$sheet->getStyleByColumnAndRow(1, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyle('E'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
				}
				
				$countall = $count + 1;
				$sheet->setCellValue("D{$countall}", "TOTAL");
				$sheet->getStyle("E6:E{$countall}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->setCellValue("E{$countall}", "=SUM(E6:E{$count})");
				
				$sheet->row(4,array('Unmatch in Book'));
				$com_1     = session()->get('com1');
				$bu_1      = session()->get('bu1');
				$bankAct_1 = session()->get('bankAct');
				
				$sheet->row(1,array("$com_1->company - $bu_1->bname : $bankAct_1->bank - $bankAct_1->accountno"));
				$sheet->row(2,array(date("F, Y",strtotime(session()->get('dateIn')))));
				$sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
			});
			
			
			$excel->setActiveSheetIndex(0);
		})->download('xlsx');
	}
}
