<?php
/**
 * Created by PhpStorm.
 * User: Erljoseph143
 * Date: 9/11/2018
 * Time: 9:34 AM
 */

namespace App\Functions;


use App\BankAccount;
use App\BankNo;
use App\DTR;

class DTRUploadingCSV
{
	
	public function __construct()
	{
		echo view('layouts.progressbar');
		$this->progressBar = new Progress();
		$this->progressBar->initprogress();
		$this->x = 0;
	}
	
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

	public function CSV($filepath,$filename,$extension,$request)
	{
		$com      = $request->com;
		$bu       = $request->bu;
		$bankName = BankAccount::find($request->bankAcct);
		$bank     = $bankName->bank;
		$banknoid = $bankName->bankno;
		$bankno   = BankNo::find($banknoid);
		$bank_no  = $bankno->bankno;
		
		$this->filename = $filename;
		$this->filepath = $filepath;
		
		if($bank == "BPI" and $request->bpiType=='BIZLINK')
		{
			$this->coldate = 0;
			$this->colchequeno =1;
			$this->sbaNo = 2;
			$this->branch = 3;
			$this->transCode = 4;
			$this->coldescription =5;
			
			$this->colbankdebit =6;
			$this->colbankcredit=7;
			$this->colbankamount=-1;
			$this->colbankbalance=8;
			
			$this->bankdateformat="n/j/Y";
			$this->bankdatedefaultseparator="/";
			
		}
		elseif($bank == "BPI" and $request->bpiType=='EXPLINK')
		{
			$this->coldate = 0;
			$this->colchequeno =1;
			$this->sbaNo = 2;
			$this->branch = 3;
			$this->transCode = 4;
			$this->coldescription =5;
			
			$this->colbankdebit =-1;
			$this->colbankcredit=-1;
			$this->colbankamount=6;
			$this->colbankbalance=7;
			
			$this->bankdateformat="m/d/Y";
			$this->bankdatedefaultseparator="/";
		}
		else
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
		$data       = $this->csvToArray($filepath);
		$startRow   = $this->startRow($data);
		$endRow     = $this->endRow($data);
		
		$this->progressBar->settotalvalues(($endRow-$startRow));
		
		$errorArray = Array();
		$dateError  = Array();

		for($x = $startRow;$x<=$endRow;$x++)
		{
			$balCheck  = $this->checkBalance($data,$x,$startRow,$filepath,$filename);
				if(count($balCheck)>0)
				{
					$errorArray[] = $this->checkBalance($data,$x,$startRow,$filepath,$filename);
				}
			$dateCheck = $this->checkDate($data,$x,$filepath,$filename);
				if(count($dateCheck)>0)
				{
					$errorArray[] = $this->checkDate($data,$x,$filepath,$filename);
				}
			
		}
		
		if(count($errorArray)>0)
		{
			echo $this->showErrors($errorArray);
			die();
		}
		
		for($x = $startRow;$x<=$endRow;$x++)
		{
			$date         = date("Y-m-d",strtotime($data[$x][$this->coldate]));
			$checkno      = $data[$x][$this->colchequeno];
			$varCheck     = preg_match('/\\d/', $checkno);
			
			if($varCheck > 0)
			{
				$output   = preg_replace( '/[^0-9]/', '', $checkno );
				$checkno  = ltrim($output,'0');
			}
			
			$this->sbaNo     !=-1  ? $sbano     = $data[$x][$this->sbaNo]     : $sbano     = '';
			$this->branch    !=-1  ? $branch    = $data[$x][$this->branch]    : $branch    = '';
			$this->transCode !=-1  ? $transCode = $data[$x][$this->transCode] : $transCode = '';
			
			$description  = $data[$x][$this->coldescription];
			$bankdebit    = (float)str_replace(",","",$data[$x][$this->colbankdebit]);
			$bankcredit   = (float)str_replace(",","",$data[$x][$this->colbankcredit]);
			$bankbalance  = (float)str_replace(",","",$data[$x][$this->colbankbalance]);
			
			if($bankcredit==0 and $bankdebit!=0)
			{
				$bankamount  = $bankdebit;
				$type_amount = "AP";
			}
			else
			{
				$bankamount  = $bankcredit;
				$type_amount = "AR";
			}
			
			$save = [
						"bank_account_no" => $bank_no,
						"bank_date"       => $date,
						"check_no"        => $checkno,
						"sba_ref_no"      => $sbano,
						"branch"          => $branch,
						"trans_code"      => $transCode,
						"trans_des"       => $description,
						"bank_amount"     => $bankamount,
						"bank_balance"    => $bankbalance,
						"type_amount"     => $type_amount,
						"company"         => $com,
						"bu_unit"         => $bu
					];
			
			DTR::updateOrCreate($save);
			
			$this->progressBar->setprogress($this->x);
			$this->progressBar->displayprogress();
			$getPercent = $this->progressBar->getpercentrounded();
			$this->x++;
			
			usleep(55565);
		}
		
		
	}
	
	public function startRow($data)
	{
		if($this->colbankamount==-1)
		{
			foreach($data as $key => $d)
			{
				$date       = $d[$this->coldate];
				$bankdebit  = (float)trim(str_replace(",","",$d[$this->colbankdebit]));
				$balance    = (float)trim(str_replace(",","",$d[$this->colbankbalance]));
				
				$bankcredit = (float)trim(str_replace(",","",$d[$this->colbankcredit]));
				$validDate  = $this->validateDate($date,$this->bankdateformat);
				if($validDate!=1 and $bankcredit!=0 and $bankdebit==0 and $balance!=0)
				{
					$error[] = $this->dateChecking($date,$this->bankdateformat,$key+1,$this->filename,$this->filepath);
					echo $this->showErrors($error);
					die();
				}
				elseif($validDate==1 and $bankcredit!=0 and $bankdebit==0 and $balance!=0)
				{
					return $key;
				}
				
			}
		}
	}
	
	public function endRow($data)
	{
		if($this->colbankamount==-1)
		{
			for($x=count($data)-1;$x>=0;$x--)
			{
				$date       = $data[$x][$this->coldate];
				$bankdebit  = (float)trim(str_replace(",","",$data[$x][$this->colbankdebit]));
				$balance    = (float)trim(str_replace(",","",$data[$x][$this->colbankbalance]));
				
				$bankcredit = (float)trim(str_replace(",","",$data[$x][$this->colbankcredit]));
				$validDate  = $this->validateDate($date,$this->bankdateformat);
				if($validDate!=1 and $bankcredit!=0 and $bankdebit==0 and $balance!=0)
				{
					$error[] = $this->dateChecking($date,$this->bankdateformat,$x+1,$this->filename,$this->filepath);
					echo $this->showErrors($error);
					die();
				}
				elseif($validDate==1 and $bankcredit!=0 and $bankdebit==0 and $balance!=0)
				{
					return $x;
				}
				
			}
		}
	}
	
	public function csvToArray($filename = '', $delimiter = ',')
	{
		if (!file_exists($filename) || !is_readable($filename))
			return false;
		
		$header = null;
		$data = array();
		if (($handle = fopen($filename, 'r')) !== false)
		{
			while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
			{
					$data[] = $row;
						//array_combine($header, $row);
			}
			fclose($handle);
		}
		
		return $data;
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
				$dateNew = "";
			}
			$d = \DateTime::createFromFormat($format, $dateNew);
			// The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
			return $d && $d->format($format) === $dateNew;
		}
		else
		{
			return '';
		}
	}
	
	public function dateChecking($date,$format,$row,$filename,$filepath)
	{
		$date = trim($date);
		$errorArray = Array();
		$d = \DateTime::createFromFormat($format, $date);
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
			
			$errorArray = [$filename,'Invalid Date!',$this->coldate,$row,$date];
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
			
			$errorArray = [$filename,'Invalid Date!',$this->coldate,$row,$date];
		}
		return $errorArray;
	}
	
	public function checkBalance($data,$x,$startRow,$filepath,$filename)
	{
		$errorArray = Array();
		if($this->colbankamount==-1)
		{
			if($x!=$startRow)
			{
				$credit      = (float)str_replace(",","",$data[$x-1][$this->colbankcredit]);
				$debit       = (float)str_replace(",","",$data[$x-1][$this->colbankdebit]);
				$balance     = (float)str_replace(",","",$data[$x][$this->colbankbalance]);
				$prevbalance = (float)str_replace(",","",$data[$x-1][$this->colbankbalance]);
				$bankamount  = 0;
				if($credit==0 and $debit!=0)
				{
					$bal     = $prevbalance + $debit;
				}
				else
				{
					$bal     = $prevbalance - $credit;
				}
				
				if((float)trim($balance) != (float)trim($bal))
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
					$errorArray = [$filename,'Balance is not equal!',$this->colbankbalance,$x+1,$balance];
				}
			}
		}
		return $errorArray;
	}
	
	public function checkDate($data,$x,$filepath,$filename)
	{
		$errorArray = Array();
		$date = $data[$x][$this->coldate];
		$date = trim($date);
		$d    = \DateTime::createFromFormat($this->bankdateformat, $date);
		if(($d && $d->format($this->bankdateformat) === $date)==true and trim($date)=='01/01/1970')
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
			
			$errorArray = [$filename,'Invalid Date!',$this->coldate,$x+1,$date];
		}
		elseif(($d && $d->format($this->bankdateformat) === $date)==false)
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
			
			$errorArray = [$filename,'Invalid Date!',$this->coldate,$x+1,$date];
		}
		return $errorArray;
	}
	
	public function invalidFormat($message)
	{
		return view('DTR.finance.errors.invalidFormat',compact('message'));
	}
	
	public function showErrors($errorArray)
	{
		return view('DTR.finance.errors.errorlist',compact('errorArray'));
	}
	
}