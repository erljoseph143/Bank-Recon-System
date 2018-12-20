<?php
/**
 * Created by PhpStorm.
 * User: Erljoseph143
 * Date: 10/4/2018
 * Time: 11:38 AM
 */

namespace App\Functions;


use App\BankAccount;
use App\DTR;
use App\Functions\Bsfunction;
use App\BankNo;
use Illuminate\Support\Facades\Auth;
use PHPExcel_IOFactory;
use PHPExcel_Shared_Date;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PHPUnit\Exception;


class DTRUploadingExcel2005
{
	protected $coldate;
	protected $colchequeno;
	protected $sbaNo;
	protected $branch;
	protected $transCode;
	protected $coldescription ;
	
	protected $colbankdebit;
	protected $colbankcredit;
	protected $colbankamount;
	protected $colbankbalance;
	
	protected $bankdateformat;
	protected $bankdatedefaultseparator;
	
	protected $filename;
	protected $filepath;
	
	protected $progressBar;
	protected $x;
	
	protected $request;
	protected $bsFunc;
	protected $bank;
	
	
	public function excel($filepath,$filename,$extension,$request)
	{
		ob_implicit_flush(true);
		ob_end_flush();
		$this->request = $request;
		$bankName = BankAccount::find($request->bankAcct);
		$bank     = $bankName->bank;
		$acctno   = $bankName->accountno;
		$acctname = $bankName->accountname;
		$banknoid = $bankName->bankno;
		$bankno   = BankNo::find($banknoid);
		$bank_no  = $bankno->bankno;
		$com      = $request->com;
		$bu       = $request->bu;
		
		$this->bank = $bank;
		
		$this->filename = $filename;
		$this->filepath = $filepath;
		
		$this->bsFunc = new Bsfunction();
		
		if($bank == "BPI" and $request->bpiType=='BIZLINK')
		{
			$this->coldate = 1;
			$this->colchequeno =2;
			$this->sbaNo = 4;
			$this->branch = 5;
			$this->transCode = 6;
			$this->coldescription =7;
			
			$this->colbankdebit =8;
			$this->colbankcredit=9;
			$this->colbankamount=-1;
			$this->colbankbalance=10;
			
			$this->bankdateformat="m/d/Y";
			$this->bankdatedefaultseparator="/";
			
		}
		elseif($bank == "BPI" and $request->bpiType=='EXPLINK')
		{
			$this->coldate = 0;
			$this->colchequeno =3;
			$this->sbaNo = -1;
			$this->branch = 2;
			$this->transCode = -1;
			$this->coldescription =1;
			
			$this->colbankdebit =4;
			$this->colbankcredit=5;
			$this->colbankamount=-1;
			$this->colbankbalance=6;
			
			$this->bankdateformat="m d Y";
			$this->bankdatedefaultseparator=" ";
		}
		elseif($bank =="PNB")
		{
			$this->coldate = 0;
			$this->colchequeno =4;
			$this->sbaNo = -1;
			$this->branch = -1;
			$this->transCode = 2;
			$this->coldescription =3;
			
			$this->colbankdebit =5;
			$this->colbankcredit=6;
			$this->colbankamount=-1;
			$this->colbankbalance=7;
			
			$this->bankdateformat="m/d/Y";
			$this->bankdatedefaultseparator="/";
		}
		elseif($bank =="LBP")
		{
			$this->coldate = 0;
			$this->colchequeno =6;
			$this->sbaNo = -1;
			$this->branch = 5;
			$this->transCode = -1;
			$this->coldescription =1;
			
			$this->colbankdebit =2;
			$this->colbankcredit=3;
			$this->colbankamount=-1;
			$this->colbankbalance=4;
			
			$this->bankdateformat="m/d/Y";
			$this->bankdatedefaultseparator="/";
		}
		elseif($bank=="MB" or $bank=="MBTC")
		{
			//Date,Check No.,Description,Debit,Credit,Balance,Branch
			$this->coldate = 0;
			$this->colchequeno =1;
			$this->sbaNo = -1;
			$this->branch = 6;
			$this->transCode = -1;
			$this->coldescription =2;
			
			$this->colbankdebit =3;
			$this->colbankcredit=4;
			$this->colbankamount=-1;
			$this->colbankbalance=5;
			
			$this->bankdateformat="d M Y";
			$this->bankdatedefaultseparator=" ";
		}
		elseif($bank=="BDO")
		{
			$this->coldate = 0;
			$this->colchequeno =6;
			$this->sbaNo = -1;
			$this->branch = 1;
			$this->transCode = -1;
			$this->coldescription =2;
			
			$this->colbankdebit =3;
			$this->colbankcredit=4;
			$this->colbankamount=-1;
			$this->colbankbalance=5;
			
			$this->bankdateformat="m/d/Y";
			$this->bankdatedefaultseparator="/";
		}

		// Tell PHPExcel that you will be loading a file.
		$objReader = PHPExcel_IOFactory::createReaderForFile($filepath);
		// Set your options.
		$objReader->setReadDataOnly(true);
		// Tell PHPExcel to load this file and make its best guess as to its type.
		$objPHPExcel = $objReader->load($filepath);
		$worksheets  = Array();
		/*
		 *--------------------------------------------------------------------------------------------------------------
		 * Converting Worksheet Data to Array
		 *--------------------------------------------------------------------------------------------------------------
		 *
		*/
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
		{
			$worksheets[] = $worksheet->toArray();
		}
		$work        = $worksheets[0];
		//dd($work);
		/*
		 *--------------------------------------------------------------------------------------------------------------
		 * Get Starting Row and Ending Row of File
		 *--------------------------------------------------------------------------------------------------------------
		 *
		*/
		$startingRow = 0;
		if($request->bpiType=='BIZLINK' or $this->bank=='LBP')
		{
			$startingRow = $this->startRow($work);
			$data        = Array();
			krsort($work);
				foreach($work as $key => $value)
				{
					$data[] = $value;
				}
			$work = $data;
		}
		
		$startRow = $this->startRow($work);
		$endRow   = $this->endRow($work);
		/*
		 *--------------------------------------------------------------------------------------------------------------
		 * File Validating
		 *--------------------------------------------------------------------------------------------------------------
		 *
		*/
		if($startRow==-1)
		{
			$message    = "Invalid File($filename) for $bank - $acctno - $acctname $request->bpiType";
			$errorExcel = ["error"=>"Invalid format","messageError"=>$message];
			echo json_encode($errorExcel);
			die();
		}
		else
		{
			/*
			 * ---------------------------------------------------------------------------------------------------------
			 * Checking Error of Data Balance and Date
			 * ---------------------------------------------------------------------------------------------------------
			 *
			*/
				$errorArray = Array();
				for($x=$startRow;$x<=$endRow;$x++)
				{
					$dataError = $this->checkBalance($work,$startRow,$x,$filename,$filepath,$startingRow);
					if(count($dataError)>0)
					{
						$errorArray[] = $this->checkBalance($work,$startRow,$x,$filename,$filepath,$startingRow);
					}
					$date       = $work[$x][$this->coldate];
						if($this->bank=="BPI" and $this->request->bpiType=='EXPLINK')
						{
							$date   = $work[$x][$this->coldate] ." ". $this->request->year;
						}
						if($this->bank=="PNB")
						{
							$date   = $this->PNBDate($date);
						}
						if(substr_count($date,$this->bankdatedefaultseparator)==2 and substr_count(trim($date)," ")==1)
						{
							$exp     = explode(" ",$date);
							$date    = trim($exp[0]);
						}
					$dateErrors = $this->dateChecking($date,$this->bankdateformat,$x,$filename,$filepath,$startingRow);
					if(count($dateErrors)>0)
					{
						$errorArray[] = $this->dateChecking($date,$this->bankdateformat,$x,$filename,$filepath,$startingRow);
					}
				}
				
				if(count($errorArray)>0)
				{
					$errorExcel  = ["error"=>"Data Error","messageError"=>json_encode($errorArray)];
					echo json_encode($errorExcel);
					die();
				}
			/*
			 *----------------------------------------------------------------------------------------------------------
			 * Check Bank balance Both Database and File Submitted
			 *----------------------------------------------------------------------------------------------------------
			 *
			*/
				$debit       = (float)$this->bsFunc->manipulatenumber($work[$startRow][$this->colbankdebit]);
				$credit      = (float)$this->bsFunc->manipulatenumber($work[$startRow][$this->colbankcredit]);
				$bankbalance = (float)$this->bsFunc->manipulatenumber($work[$startRow][$this->colbankbalance]);
				if($debit==0 and $credit!=0)
				{
					$recordDTR   = DTR::where('company',$com)
									  ->where('bu_unit',$bu)
									  ->where('bank_account_no',$bank_no)
									  ->orderBy('id','DESC');
					$begbal1     =   $recordDTR->first();
					if($recordDTR->count('id')>0)
					{
						$begbal1      =   $begbal1->bank_balance;
						$bal          =   round($begbal1 + $credit,2);
						if(trim($bal) != trim($bankbalance))
						{
							$dataPass = base64_encode("/$credit/$bankbalance/$begbal1");
							$error    = ["error"=>"Not balance","messageError"=>$dataPass];
							echo json_encode($error);
							die();
						}
					}
				}
				elseif($debit!=0 and $credit==0)
				{
					$recordDTR   = DTR::where('company',$com)
									  ->where('bu_unit',$bu)
									  ->where('bank_account_no',$bank_no)
									  ->orderBy('id','DESC');
					$begbal1     =   $recordDTR->first();
					if($recordDTR->count('id')>0)
					{
						$begbal1      =   $begbal1->bank_balance;
						$bal          =   round($begbal1 + $debit,2);
						if(trim($bal) != trim($bankbalance))
						{
							$dataPass = base64_encode("$debit//$bankbalance/$begbal1");
							$error    = ["error"=>"Not balance","messageError"=>$dataPass];
							echo json_encode($error);
							die();
						}
					}
				}

			/*
			 * ---------------------------------------------------------------------------------------------------------
			 * Saving Data To Database
			 *----------------------------------------------------------------------------------------------------------
			 *
			*/
				for($x=$startRow;$x<=$endRow;$x++)
				{
					$date        = $work[$x][$this->coldate];
					if($this->bank=="BPI" and $this->request->bpiType=='EXPLINK')
					{
						$date    = $work[$x][$this->coldate] ." ". $this->request->year;
						$date    = str_replace(" ","/",trim($date));
					}
					
					if($this->bank=="PNB")
					{
						$date   = $this->PNBDate($date);
					}
					if(substr_count($date,$this->bankdatedefaultseparator)==2 and substr_count(trim($date)," ")==1)
					{
						$exp     = explode(" ",$date);
						$date    = trim($exp[0]);
					}

					$checkno     = $work[$x][$this->colchequeno];
					$sbano       = $this->sbaNo!=-1     ? $work[$x][$this->sbaNo]    : '';
					$branch      = $this->branch!=-1    ? $work[$x][$this->branch]   : '';
					$transCode   = $this->transCode!=-1 ? $work[$x][$this->transCode]: '';
					$description = $work[$x][$this->coldescription];
					$debit       = (float)$this->bsFunc->manipulatenumber($work[$x][$this->colbankdebit]);
					$credit      = (float)$this->bsFunc->manipulatenumber($work[$x][$this->colbankcredit]);
					$bankbalance = (float)$this->bsFunc->manipulatenumber($work[$x][$this->colbankbalance]);
					
					if($debit!=0 and $credit==0 and $bankbalance!=0)
					{
						$bankamount  = $debit;
						$type_amount = "AP";
					}
					else
					{
						$bankamount  = $credit;
						$type_amount = "AR";
					}
					$savedata = [
						'bank_date'       => date("Y-m-d",strtotime($date)),
						'bank_account_no' => $bank_no,
						'check_no'        => $checkno,
						'sba_ref_no'      => $sbano,
						'branch'          => $branch,
						'trans_code'      => $transCode,
						'trans_des'       => $description,
						'bank_amount'     => $bankamount,
						'bank_balance'    => $bankbalance,
						'type_amount'     => $type_amount,
						'company'         => $com,
						'bu_unit'         => $bu
					];
					DTR::updateOrCreate($savedata);
				}
		}

	}
	
	public function PNBDate($date)
	{
		if(substr_count($date,"-")==2):
			$date      = explode("-",trim($date));
			$date      = date("m/d/Y",strtotime($date[1]."/".$date[0]."/".$date[2]));
		elseif(substr_count($date,"/")==2):
			$date      = explode("/",trim($date));
			$date      = date("m/d/Y",strtotime($date[1]."/".$date[0]."/".$date[2]));
		endif;
		return $date;
	}
	
	public function startRow($worksheets)
	{

		foreach($worksheets as $key => $data)
		{
			try{
				
				if(substr_count($data[$this->coldate],$this->bankdatedefaultseparator)==2 and substr_count(trim($data[$this->coldate])," ")==1)
				{
					$exp  = explode(" ",$data[$this->coldate]);
					$date = trim($exp[0]);
				}
				else
				{
					if($this->bank=="BPI" and $this->request->bpiType=='EXPLINK')
					{
						$date =$data[$this->coldate] ." ". $this->request->year;
					}
					else
					{
						$date =$data[$this->coldate];
					}
				}
				
				if($this->bank=="PNB")
				{
					$date   = $this->PNBDate($date);
				}
				$debit   = (float)$data[$this->colbankdebit];
				$credit  = (float)$data[$this->colbankcredit];
				$balance = (float)$data[$this->colbankbalance];
				if(($this->validateDate($date,$this->bankdateformat)==1 and $debit!=0 and $credit==0 and $balance!=0) or ($this->validateDate($date,$this->bankdateformat)==1 and $debit==0 and $credit!=0 and $balance!=0))
				{
					return $key;
				}
			}
			catch (\Exception $e)
			{
				return -1;
			}
		}
		return -1;
	}
	
	public function endRow($worksheets)
	{
		$rows  = count($worksheets)-1;
		for($x = count($worksheets)-1;$x>=0;$x--)
		{
			try
			{
				if(substr_count($worksheets[$x][$this->coldate],$this->bankdatedefaultseparator)==2 and substr_count(trim($worksheets[$x][$this->coldate])," ")==1)
				{
					$exp  = explode(" ",$worksheets[$x][$this->coldate]);
					$date = trim($exp[0]);
				}
				else
				{
					if($this->bank=="BPI" and $this->request->bpiType=='EXPLINK')
					{
						$date =$worksheets[$x][$this->coldate] ." ". $this->request->year;
					}
					else
					{
						$date =$worksheets[$x][$this->coldate];
					}
				}
				
				if($this->bank=="PNB")
				{
					$date   = $this->PNBDate($date);
				}
				$debit   = (float)trim($worksheets[$x][$this->colbankdebit]);
				$credit  = (float)trim($worksheets[$x][$this->colbankcredit]);
				$balance = (float)trim($worksheets[$x][$this->colbankbalance]);
				if(($this->validateDate($date,$this->bankdateformat)==1 and $debit!=0 and $credit==0 and $balance!=0) or ($this->validateDate($date,$this->bankdateformat)==1 and $debit==0 and $credit!=0 and $balance!=0))
				{
					return $rows;
				}
				else
				{
					$rows--;
				}
				
			}
			catch (\Exception $e)
			{
				return -1;
			}

		}
		return -1;
	}
	
	public function validateDate($date, $format)
	{
		if($date!='01/01/1970')
		{
			if(!is_numeric($date))
			{
				$dateNew = $date;
			}
			else
			{
				$dateNew = date("m/d/Y",PHPExcel_Shared_Date::ExcelToPHP($date));
			}
			$d = \DateTime::createFromFormat($format, $dateNew);
			// The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
			return $d && $d->format($format) === $dateNew;
		}
		else
		{
			return -1;
		}
	}
	
	public function checkBalance($worksheet,$startRow,$rowx,$filename,$filepath,$startingRow)
	{
		$errorArray  = Array();

			$balance = (float)$this->bsFunc->manipulatenumber($worksheet[$rowx][$this->colbankbalance]);
			$credit  = (float)$this->bsFunc->manipulatenumber($worksheet[$rowx][$this->colbankcredit]);
			$debit   = (float)$this->bsFunc->manipulatenumber($worksheet[$rowx][$this->colbankdebit]);
			if($rowx!=$startRow)
			{
				$balprev = (float)$this->bsFunc->manipulatenumber($worksheet[$rowx-1][$this->colbankbalance]);
				if($credit==0 and $debit!=0)
				{
					$balnow = $balprev - $debit;
				}
				elseif($credit!=0 and $debit==0)
				{
					$balnow = $balprev + $credit;
				}
				
				if((double)trim($balnow) != (double)trim($balance))
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
					if(!file_exists("functions/tempuploads/".$localIP))
					{
						mkdir("functions/tempuploads/".$localIP);
					}
					copy($filepath,"functions/tempuploads/".$localIP."/".$filename);
					if($startingRow!=0)
					{
						$row = $startingRow + $rowx;
					}
					else
					{
						$row = $rowx;
					}
					$errorArray = [$filename,'Balance is not equal!',$this->colbankbalance,$row,$balance];
				}
			}
		
		return $errorArray;
	}
	
	public function dateChecking($date,$format,$row,$filename,$filepath,$startingRow)
	{
		$date       = trim($date);
		$errorArray = Array();
		$d          = \DateTime::createFromFormat($format, $date);
		if(($d && $d->format($format) === $date)==true and trim($date)=='01/01/1970')
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
			if(!file_exists("functions/tempuploads/".$localIP))
			{
				mkdir("functions/tempuploads/".$localIP);
			}
			copy($filepath,"functions/tempuploads/".$localIP."/".$filename);
			
			if($startingRow!=0)
			{
				$rows = ($startingRow + $row)-1;
			}
			else
			{
				$rows = $row+1;
			}
			
			$errorArray = [$filename,'Invalid Date!',$this->coldate,$rows,$date];
		}
		elseif(($d && $d->format($format) === $date)==false)
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
			if(!file_exists("functions/tempuploads/".$localIP))
			{
				mkdir("functions/tempuploads/".$localIP);
			}
			copy($filepath,"functions/tempuploads/".$localIP."/".$filename);
			
			if($startingRow!=0)
			{
				$rows = ($startingRow + $row)-1;
			}
			else
			{
				$rows = $row+1;
			}
			
			$errorArray = [$filename,'Invalid Date!',$this->coldate,$rows,$date];
		}
		return $errorArray;
	}
	
	public function invalidFile()
	{
	
	}
}