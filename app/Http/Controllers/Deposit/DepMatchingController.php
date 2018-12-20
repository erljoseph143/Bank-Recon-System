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

use Illuminate\Support\Facades\File;

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
		
		session()->forget('TotalsameDateAmt');
		session()->forget('TotalDupsameDateAmt');
		session()->forget('Totalplus5Days');
		session()->forget('Totalminus5Days');
		session()->forget('TotalbatchDS');
		session()->forget('TotalbranchCode');
		session()->forget('TotalunmatchBanK');
		session()->forget('TotalunmatchBooK');
		
		session(['DupsameDateAmt'=>Array()]);
		
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
		$totalBS     = 0;
		$totalBK     = 0;
		$totalDupBS  = 0;
		$totalDupBK  = 0;
		$x = Array();
		$flag = Array();
		
		
		
		foreach ($selBs as $key => $bs)
		{
			//$exp     = explode("|",$bs);
			$bsdate  = date("Y-m-d",strtotime($bs->bank_date));
			$bsdes   = $bs->description;
			$bsamt   = $bs->bank_amount;
			$tagamt  = $bsamt;
			$tagdate = $bsdate;
			$tagdes  = $bsdes;
			
			$data = $selBk->where('posting_date',$bsdate)->where('amount',$bsamt);
			
			if($data->count() ==1)
			{
				$bk      = $data[$data->keys()[0]];
				$bkdate  = $bk->posting_date;
				$docno   = $bk->doc_no;
				$extdoc  = $bk->ext_doc_no;
				$bkamt   = $bk->amount;
				$bkEntry = $bk->entry_no;
				$bkUsers = $bk->users;
				$bkDes   = $bk->description;
				
				$bsDATE  = strtotime($tagdate);
				$tagdate = PHPExcel_Shared_Date::PHPToExcel($bsDATE);
				$bkDATE  = strtotime($bkdate);
				$bkdate  = PHPExcel_Shared_Date::PHPToExcel($bkDATE);
				
				$sameDate[] = [$tagdate,$tagdes,$tagamt,' ',$bkEntry,$bkdate,$docno,$extdoc,$bkamt,$bkUsers,$bkDes];
				
				if($tagamt!="")
				{
					$totalBS +=$tagamt;
				}
				$totalBK+=$bkamt;
				
				session(['sameDateAmt'=>$sameDate]);
				unset($selBs[$key]);
				unset($selBk[$data->keys()[0]]);
				unset($extDocArray[$data->keys()[0]]);
			}
			elseif($data->count() > 1)
			{
				$countDataBS = $selBs->where('bank_date',$bs->bank_date)->where('bank_amount',$bsamt);
				if($countDataBS->count() == $data->count())
				{
					$keyTag = 0;
					foreach($countDataBS->keys() as $dataKey => $keyBS)
					{
						$dateBS = strtotime($selBs[$keyBS]->bank_date);
						$dateBS = PHPExcel_Shared_Date::PHPToExcel($dateBS); 
						$desBS  = $selBs[$keyBS]->description;
						$amtBS  = $selBs[$keyBS]->bank_amount;
						
						$bk      = $data[$data->keys()[$dataKey]];
						$bkdate  = $bk->posting_date;
						$docno   = $bk->doc_no;
						$extdoc  = $bk->ext_doc_no;
						$bkamt   = $bk->amount;
						$bkEntry = $bk->entry_no;
						$bkUsers = $bk->users;
						$bkDes   = $bk->description;
						
						$bkDATE  = strtotime($bkdate);
						$bkdate  = PHPExcel_Shared_Date::PHPToExcel($bkDATE);
						
						//dd($countDataBS->keys(),$data->keys());
						
						$sameDate[] = [$dateBS,$desBS,$amtBS,' ',$bkEntry,$bkdate,$docno,$extdoc,$bkamt,$bkUsers,$bkDes];
						session(['sameDateAmt'=>$sameDate]);
						unset($selBs[$keyBS]);
						unset($selBk[$data->keys()[$dataKey]]);
						unset($extDocArray[$data->keys()[$dataKey]]);
						
					}
					
				}
				else
				{
					$x = 1;
					foreach($data as $key2 => $bk)
					{
						if($x > 1)
						{
							$tagamt  = "";
							$tagdate = "";
							$tagdes  = "";
						}
						$bkdate  = $bk->posting_date;
						$docno   = $bk->doc_no;
						$extdoc  = $bk->ext_doc_no;
						$bkamt   = $bk->amount;
						$bkEntry = $bk->entry_no;
						$bkUsers = $bk->users;
						$bkDes   = $bk->description;
						
						$bsDATE  = strtotime($tagdate);
						$tagdate = PHPExcel_Shared_Date::PHPToExcel($bsDATE);
						$bkDATE  = strtotime($bkdate);
						$bkdate  = PHPExcel_Shared_Date::PHPToExcel($bkDATE);
						
						$dupEntry[]  = [$tagdate,$tagdes,$tagamt,' ',$bkEntry,$bkdate,$docno,$extdoc,$bkamt,$bkUsers,$bkDes];
						session(['DupsameDateAmt'=>$dupEntry]);
						
						if($tagamt!="")
						{
							$totalDupBS +=$tagamt;
						}
						$totalDupBK+=$bkamt;
						
						unset($selBs[$key]);
						unset($selBk[$key2]);
						unset($extDocArray[$key2]);
						$x++;
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
		
		session(['TotalsameDateAmt'=>[$totalBS,$totalBK]]);
		session(['TotalDupsameDateAmt'=>[$totalDupBS,$totalDupBK]]);
		
		return $sameDate;
	}
	
	public function duplicateEntry()
	{
		
		$dupEntry1   = session()->get('DupsameDateAmt');
		$dupEntry2   = Array();
		$bsArray     = session()->get('bankDep');
		$bkArray     = session()->get('bookDep');
		$extDocArray = session()->get('extDocArray');
		
		$totalDupBS = session()->get('TotalDupsameDateAmt')[0];
		$totalDupBK = session()->get('TotalDupsameDateAmt')[1];
		
		foreach($bkArray as $key => $bk)
		{
			$bkdate  = $bk->posting_date;
			$docno   = $bk->doc_no;
			$extdoc  = $bk->ext_doc_no;
			$bkamt   = $bk->amount;
			$bkEntry = $bk->entry_no;
			$bkUsers = $bk->users;
			$bkDes   = $bk->description;
			$data = $bsArray->where('bank_date',$bkdate ." 00:00:00")->where('bank_amount',$bkamt);
			if($data->count() > 1)
			{
				$x = 1;
				foreach($data as $key2 => $bs)
				{
					if($x > 1)
					{
						$bkdate  = "";
						$docno   = "";
						$extdoc  = "";
						$bkamt   = "";
						$bkEntry = "";
						$bkUsers = "";
						$bkDes   = "";
					}
					$bsdate  = date("Y-m-d",strtotime($bs->bank_date));
					$bsdes   = $bs->description;
					$bsamt   = $bs->bank_amount;
					
					$bsDATE  = strtotime($bsdate);
					$tagdate = PHPExcel_Shared_Date::PHPToExcel($bsDATE);
					$bkDATE  = strtotime($bkdate);
					$bkdate  = PHPExcel_Shared_Date::PHPToExcel($bkDATE);
					
					$dupEntry2[]  = [$tagdate,$bsdes,$bsamt,' ',$bkEntry,$bkdate,$docno,$extdoc,$bkamt,$bkUsers,$bkDes];
					
					if($bkamt!="")
					{
						$totalDupBK+=$bkamt;
					}
					$totalDupBS +=$bsamt;
					
					unset($bsArray[$key2]);
					unset($extDocArray[$key]);
					unset($bkArray[$key]);
					$x++;
				}
			}
			
		}
		
		$dupEntry = array_merge($dupEntry1,$dupEntry2);
		
		session(['bankDep'=>$bsArray]);
		session(['bookDep'=>$bkArray]);
		session(['DupsameDateAmt'=>$dupEntry]);
		session(['TotalDupsameDateAmt'=>[$totalDupBS,$totalDupBK]]);
		
		session(['extDocArray'=>$extDocArray]);
		
		//dd(session()->get('TotalDupsameDateAmt'));
		
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
		
		$totalPlusBS = 0;
		$totalPlusBK = 0;
		$plus5days   = Array();
		
		$x = Array();
		
		
		
		foreach($bsArray as $key => $bs)
		{
			
			$bsdate = date("Y-m-d",strtotime($bs->bank_date));
			$bsdes = $bs->description;
			$bsamt = $bs->bank_amount;
			$tagamt = $bsamt;
			$tagdate = date("m/d/Y", strtotime($bsdate));
			$tagdes = $bsdes;
			$x = 1;
			for($xx=1;$xx<=5;$xx++)
			{
				$date     = date_create("$bsdate");
				date_sub($date, date_interval_create_from_date_string("$xx days"));
				$bankdate = date_format($date, 'Y-m-d');
				
				$data     = $bkArray->where('posting_date',$bankdate)->where('amount',$bsamt);
				if($data->count() > 0)
				{
					foreach($data as $key2 => $bk)
					{
						$bkdate       = $bk->posting_date;
						$docno        = $bk->doc_no;
						$extdoc       = $bk->ext_doc_no;
						$bkamt        = $bk->amount;
						$bkEntry      = $bk->entry_no;
						$bkUsers      = $bk->users;
						$bkDes        = $bk->description;
						
						if($x>1)
						{
							$tagamt   = "";
							$tagdate  = "";
							$tagdes   = "";
						}
						$bsDATE       = strtotime($tagdate);
						$tagdate      = PHPExcel_Shared_Date::PHPToExcel($bsDATE);
						$bkDATE       = strtotime($bkdate);
						$bkdateExcel  = PHPExcel_Shared_Date::PHPToExcel($bkDATE);
						
						$plus5days[]  = [$tagdate,$tagdes,$tagamt,' ',$bkEntry,$bkdateExcel,$docno,$extdoc,$bkamt,$bkUsers,$bkDes];
						session(['plus5Days'=>$plus5days]);
						
						if($tagamt !="")
						{
							$totalPlusBS +=$tagamt;
						}
						$totalPlusBK+=$bkamt;
						
						unset($bkArray[$key2]);
						unset($extDocArray[$key2]);
						unset($bsArray[$key]);
						$x++;
					}
					
				}
			}
			
		}
		
		session(['bankDep'=>$bsArray]);
		session(['bookDep'=>$bkArray]);
		
		session(['extDocArray'=>$extDocArray]);
		session(['Totalplus5Days'=>[$totalPlusBS,$totalPlusBK]]);

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
		
		$totalMinusBS = 0;
		$totalMinusBK = 0;
		
		$minus5days   = Array();
		
		foreach($bsArray as $key => $bs)
		{
			
			$bsdate = date("Y-m-d",strtotime($bs->bank_date));
			$bsdes = $bs->description;
			$bsamt = $bs->bank_amount;
			$tagamt = $bsamt;
			$tagdate = date("m/d/Y", strtotime($bsdate));
			$tagdes = $bsdes;
			$x = 1;
			for($xx=1;$xx<=5;$xx++)
			{
				$date     = date_create("$bsdate");
				date_add($date, date_interval_create_from_date_string("$xx days"));
				$bankdate = date_format($date, 'Y-m-d');
				
				$data     = $bkArray->where('posting_date',$bankdate)->where('amount',$bsamt);
				if($data->count() > 0)
				{
					foreach($data as $key2 => $bk)
					{
						$bkdate       = $bk->posting_date;
						$docno        = $bk->doc_no;
						$extdoc       = $bk->ext_doc_no;
						$bkamt        = $bk->amount;
						$bkEntry      = $bk->entry_no;
						$bkUsers      = $bk->users;
						$bkDes        = $bk->description;
						
						if($x>1)
						{
							$tagamt   = "";
							$tagdate  = "";
							$tagdes   = "";
						}
						$bsDATE       = strtotime($tagdate);
						$tagdate      = PHPExcel_Shared_Date::PHPToExcel($bsDATE);
						$bkDATE       = strtotime($bkdate);
						$bkdateExcel  = PHPExcel_Shared_Date::PHPToExcel($bkDATE);
						
						$minus5days[]  = [$tagdate,$tagdes,$tagamt,' ',$bkEntry,$bkdateExcel,$docno,$extdoc,$bkamt,$bkUsers,$bkDes];
						session(['minus5Days'=>$minus5days]);
						
						if($tagamt!="")
						{
							$totalMinusBS += $tagamt;
						}
						$totalMinusBK += $bkamt;
						
						unset($bkArray[$key2]);
						unset($extDocArray[$key2]);
						unset($bsArray[$key]);
						$x++;
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
		
		session(['Totalminus5Days'=>[$totalMinusBS,$totalMinusBK]]);

		return view('deposit.matching.minus5days',compact('minus5days'));
	}
	
	public function externalDoc()
	{
		$selBs = session()->get('bankDep');
		$selBk = session()->get('bookDep');
		$com         = session()->get('com');
		$bu          = session()->get('bu');
		$bankno      = session()->get('bankno');
		
		$totalDSBS   = 0;
		$totalDSBK   = 0;
		
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

					$sumtotal = $selBk->where('ext_doc_no',$extdoc)->sum('amount');
						//echo "$bkdate => $extdoc => $sumtotal </br>";
					foreach($selBs as $key => $bs)
					{
						$exp     = explode("|",$bs);
						$bsdate  = $bs->bank_date;
						$bsdes   = $bs->description;
						$bsamt   = $bs->bank_amount;
						if(trim($sumtotal)==trim($bsamt))
						{
							$allbook = $selBk->where('ext_doc_no',$extdoc);

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
							$totalDSBS +=$bsamt;
							$totalDSBK +=$sumtotal;
						}
					}
				}
			}
		}

		session(['bankDep' => $selBs]);
		session(['bookDep'=>$selBk]);
		session(['extDocArray'=>$extDocArray]);
		
		session(['TotalbatchDS'=>[$totalDSBS,$totalDSBK]]);
		
		
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

		$totalCodeBS = 0;
		$totalCodeBK = 0;
		
		foreach($selBs as $key => $bs)
		{
			$bsamount = $bs->bank_amount;
			$bsDes    = $bs->description;
			$bsdate   = $bs->bank_date;
			$bsDATE   = strtotime($bsdate);
			$bsdate   = PHPExcel_Shared_Date::PHPToExcel($bsDATE);
			$bsCode   = "BRC#".$bs->bank_ref_no;
			$bsTRC    = "TRC#".$bs->bank_ref_no;
			
			$x = 1;
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
					$allbook = $selBk->where('ext_doc_no',$bk->ext_doc_no);
					if($allbook->count()>0)
					{
						if($x==1)
						{
							$branchCode[] = [$bsdate,$bsDes,$bsamount,$bsCode,' ',$entryno,$bkdate,$docno,$extDoc,$bk_amt,$bkUsers,$bkDes];
							session(['branchCode'=>$branchCode]);
							
							$totalCodeBS +=$bsamount;
							$totalCodeBK +=$bk_amt;
							
							unset($selBs[$key]);
							unset($selBk[$allbook->keys()[0]]);
						}
						
						$x++;
					}
				}
				else
				{
					$preg    = preg_match("/$bsTRC/",$bk->ext_doc_no);
					if($preg == 1 and trim($bk_amt) == trim($bsamount))
					{
						$allbook = $selBk->where('ext_doc_no',$bk->ext_doc_no);
						if($allbook->count()>0)
						{

							if($x==1)
							{
								$branchCode[] = [$bsdate,$bsDes,$bsamount,$bsTRC,' ',$entryno,$bkdate,$docno,$extDoc,$bk_amt,$bkUsers,$bkDes];
								session(['branchCode'=>$branchCode]);
								
								$totalCodeBS +=$bsamount;
								$totalCodeBK +=$bk_amt;
								
								unset($selBs[$key]);
								unset($selBk[$allbook->keys()[0]]);
							}
							
							$x++;
						}
					}
				}
			}
		}
		
		session(['bankDep' => $selBs]);
		session(['bookDep'=>$selBk]);
		session(['extDocArray'=>$extDocArray]);
		
		session(['TotalbranchCode'=>[$totalCodeBS,$totalCodeBK]]);
		
		return view('deposit.matching.branchCode',compact('branchCode'));
	}
	
	public function unMatchBS()
	{
		$unmatchBS = session()->get('bankDep');
		session(['unmatchBanK'=>$unmatchBS]);
		
		session(['TotalunmatchBanK'=>$unmatchBS->sum('bank_amount')]);
		return view('deposit.matching.unmatchBS',compact('unmatchBS'));
	}
	
	public function unMatchBK()
	{
		$unmatchBK  = session()->get('bookDep');
		session(['unmatchBooK'=>$unmatchBK]);
		session(['TotalunmatchBooK'=>$unmatchBK->sum('amount')]);
		return view('deposit.matching.unmatchBK',compact('unmatchBK'));
	}
	
	public function summary()
	{
		$totalsame  = session()->get('TotalsameDateAmt');
		$totalDup   = session()->get('TotalDupsameDateAmt');
		$totalplus  = session()->get('Totalplus5Days');
		$totalminus = session()->get('Totalminus5Days');
		$totalDS    = session()->get('TotalbatchDS');
		$totalBRC   = session()->get('TotalbranchCode');
		$totalUnBS  = session()->get('TotalunmatchBanK');
		$totalUnBK  = session()->get('TotalunmatchBooK');
		
		$sumArray   = Array();
		
		$bsSame     = $totalsame[0];
		$bkSame     = $totalsame[1];
		$varSame    = $bsSame - $bkSame;
		$sumArray[] = ["Same Date and Amount",$bsSame,$bkSame,$varSame];
		
		$bsDup      = $totalDup[0];
		$bkDup      = $totalDup[1];
		$varDup     = abs($bsDup) - abs($bkDup);
		$sumArray[] = ["Duplicate Same Date and Amount",$bsDup,$bkDup,$varDup];
		
		$bsPlus     = $totalplus[0];
		$bkPlus     = $totalplus[1];
		$varPlus    = abs($bsPlus) - abs($bkPlus);
		$sumArray[] = ["Book Date Plus 1 to 5 days",$bsPlus,$bkPlus,$varPlus];
		
		$bsMinus    = $totalminus[0];
		$bkMinus    = $totalminus[1];
		$varMinus   = abs($bsMinus) - abs($bkMinus);
		$sumArray[] = ["Book Date Minus 1 to 5 days",$bsMinus,$bkMinus,$varMinus];
		
		$bsBRC      = $totalBRC[0];
		$bkBRC      = $totalBRC[1];
		$varBRC     = abs($bsBRC) - abs($bkBRC);
		$sumArray[] = ["Branch Code or Transanction Code",$bsBRC,$bkBRC,$varBRC];
		
		$bsDS       = $totalDS[0];
		$bkDS       = $totalDS[1];
		$varDS      = abs($bsDS) - abs($bkDS);
		$sumArray[] = ["Match By DS Number",$bsDS,$bkDS,$varDS];
		
		$varUnmatch = abs($totalUnBS) - abs($totalUnBK);
		$sumArray[] = ["Unmatch Book and Bank",$totalUnBS,$totalUnBK,$varUnmatch];
		
		$allBS      = $bsSame+$bsDup+$bsPlus+$bsMinus+$bsBRC+$bsDS+$totalUnBS;
		$allBK      = $bkSame+$bkDup+$bkPlus+$bkMinus+$bkBRC+$bkDS+$totalUnBK;
		$varAll     = $allBS - abs($allBK);
		$sumArray[] = ["Summary All",$allBS,$allBK,$varAll];
		
		return $sumArray;
	}
	
	public function depExcel()
	{
		ob_implicit_flush(true);
		ob_end_flush();
		$user_ID   = Auth::user()->user_id;
		$bankAct_1 = session()->get('bankAct');
		$path = storage_path("exports/DepReports/$user_ID");
		File::makeDirectory($path, 0777, true,true);
		Excel::create("DepReports/$user_ID/Deposit ".date("F, Y",strtotime(session()->get('dateIn')))." $bankAct_1->bank - $bankAct_1->accountno", function($excel)  {
			
			// Set the title
			$excel->setTitle('DEPOSIT SUMMARY REPORTS');
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
				
				$headings = array('BANK DATE', 'DESCRIPTION','BANK AMOUNT','','ENTRY NO','BOOK DATE','DOCUMENT NO','EXTERNAL DOCUMENT NO','AMOUNT','USER ID','DESCRIPTION');
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
			echo response()->json(['percent'=>10]);
			
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
				
				$headings = array('BANK DATE', 'DESCRIPTION','BANK AMOUNT','','ENTRY NO','BOOK DATE','DOCUMENT NO','EXTERNAL DOCUMENT NO','AMOUNT','USER ID','DESCRIPTION');
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
			echo response()->json(['percent'=>20]);			
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
				
				$headings = array('BANK DATE', 'DESCRIPTION','BANK AMOUNT','','ENTRY NO','BOOK DATE','DOCUMENT NO','EXTERNAL DOCUMENT NO','AMOUNT','USER ID','DESCRIPTION');
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
			echo response()->json(['percent'=>30]);			
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
				
				$headings = array('BANK DATE', 'DESCRIPTION','BANK AMOUNT','','ENTRY NO','BOOK DATE','DOCUMENT NO','EXTERNAL DOCUMENT NO','AMOUNT','USER ID','DESCRIPTION');
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
			echo response()->json(['percent'=>40]);			
			
		/*
		 |----------------------------------------------------------------------------------------------------------------------------
		 |  Branch Code
		 |----------------------------------------------------------------------------------------------------------------------------
		*/
			$excel->sheet('Match by branch code', function ($sheet)  {
				
				$sheet->setOrientation('landscape');
				$br    = session()->get('branchCode');
				$count = $br==null?0+5:count($br) + 5;
				$sheet->setBorder('A4:L'.$count, 'thin');
				
				$headings = array('BANK DATE', 'DESCREPTION','BANK AMOUNT','BANK BRC/TRC CODE','','ENTRY NO','BOOK DATE','DOCUMENT NO','EXTERNAL DOCUMENT NO','AMOUNT','USER ID','DESCRIPTION');
				$sheet->prependRow(5, $headings);
				$sheet->fromArray(session()->get('branchCode'), NULL, 'A6',false,false);
				$sheet->mergecells('A4:L4');
				$sheet->mergecells('A1:L1');
				
				for($s=4;$s<=$count;$s++)
				{
					$sheet->getStyleByColumnAndRow(0, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyleByColumnAndRow(6, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyle('C'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
					$sheet->getStyle('J'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
				}
				
				$countall = $count + 1;
				$sheet->setCellValue("H{$countall}", "TOTAL");
				$sheet->getStyle("J6:J{$countall}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->setCellValue("J{$countall}", "=SUM(J6:J{$count})");
				
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
			
			echo response()->json(['percent'=>50]);			
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
				
				$headings = array('ENTRY NO','BOOK DATE','DOCUMENT NO','EXTERNAL DOCUMENT NO','AMOUNT','USER ID','DESCRIPTION','TOTAL','BANK DATE', 'DESCRIPTION','BANK AMOUNT');
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
			echo response()->json(['percent'=>60]);
			
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
				
				$headings = array('BANK DATE', 'DESCRIPTION','BANK AMOUNT');
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
			echo response()->json(['percent'=>70]);			
			
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
				
				$headings = array('BANK DATE', 'DESCRIPTION','BANK AMOUNT');
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
			echo response()->json(['percent'=>80]);			
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
			echo response()->json(['percent'=>90]);			
			
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
			echo response()->json(['percent'=>100]);			
			/*
			 |----------------------------------------------------------------------------------------------------------------------------
			 |  Summary Total
			 |----------------------------------------------------------------------------------------------------------------------------
			*/
			$excel->sheet('Summary Total', function ($sheet)  {
				
				$sheet->setOrientation('landscape');
				$count = count($this->summary()) + 5;
				//$count = count(session()->get('unmatchBooK')) + 5;
				$sheet->setBorder('A4:D'.$count, 'thin');
				
				$arrayData  = Array();

				
				$headings = array('DESCRIPTION','BANK TOTALS','BOOK TOTALS','VARIANCE');
				$sheet->prependRow(5, $headings);
				$sheet->fromArray($this->summary(), NULL, 'A6',false,false);
				$sheet->mergecells('A4:D4');
				$sheet->mergecells('A1:D1');
				
				for($s=4;$s<=$count;$s++)
				{
					$sheet->getStyleByColumnAndRow(1, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyle('B'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
					$sheet->getStyle('C'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
					$sheet->getStyle('D'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
				}
				
				$countall = $count + 1;
				
				$sheet->getStyle('A5:D5')->applyFromArray([
					'font' => [
						'bold' => true
					]
				]);
				
				//$sheet->setCellValue("D{$countall}", "TOTAL");
				$sheet->getStyle("B6:B{$countall}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->getStyle("C6:C{$countall}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$sheet->getStyle("D6:D{$countall}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				//$sheet->setCellValue("E{$countall}", "=SUM(E6:E{$count})");
				
				$sheet->row(4,array('Summary Total'));
				$com_1     = session()->get('com1');
				$bu_1      = session()->get('bu1');
				$bankAct_1 = session()->get('bankAct');
				
				$sheet->row(1,array("$com_1->company - $bu_1->bname : $bankAct_1->bank - $bankAct_1->accountno"));
				$sheet->row(2,array(date("F, Y",strtotime(session()->get('dateIn')))));
				$sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
			});			
			
			
			$excel->setActiveSheetIndex(0);
		})->save('xlsx');
		
		$path = url("../storage/exports/DepReports/$user_ID/Deposit ".date("F, Y",strtotime(session()->get('dateIn')))." $bankAct_1->bank - $bankAct_1->accountno.xlsx");
		echo response()->json(['url'=>$path]);
	}
}
