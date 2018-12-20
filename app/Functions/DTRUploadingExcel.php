<?php
/**
 * Created by PhpStorm.
 * User: Erljoseph143
 * Date: 8/28/2018
 * Time: 4:33 PM
 */

namespace App\Functions;
use App\BankAccount;
use App\BankNo;
use App\DTR;
use App\Functions\Progress;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Shared_Date;

class DTRUploadingExcel
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
	
	public function __construct()
	{
		echo view('layouts.progressbar');
		 $this->progressBar = new Progress();
		 $this->progressBar->initprogress();
		 $this->x = 0;
	}
	
	public function excel($filepath,$filename,$extension,$request)
	{
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
			
			$this->bankdateformat="m/d/Y";
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
		$columnSet = [
			$this->coldate,
			$this->colchequeno,
			$this->sbaNo,
			$this->branch,
			$this->transCode,
			$this->coldescription,
			$this->colbankdebit,
			$this->colbankcredit,
			$this->colbankamount,
			$this->colbankbalance,
			$this->bankdateformat,
			$this->bankdatedefaultseparator
		];
		
		Excel::load($filepath,function($reader)use($filepath,$filename,$extension,$request,$columnSet,$bank,$bankno,$bank_no) {
			$com          = $request->com;
			$bu           = $request->bu;
			$objWorksheet = $reader->getActiveSheet();
			$objWorksheet->getCell('A1');
			$objWorksheet->getCellByColumnAndRow(0, 10);
			$highestRow   = $objWorksheet->getHighestRow();

			$startRow     = $this->getStartingRow($objWorksheet,$request->bpiType,$columnSet,$bank);
			$endRow       = $this->getEndingRow($objWorksheet,$highestRow,$request->bpiType,$columnSet,$bank);
			$totalRows    = $endRow - $startRow;
			$totalRows    = $endRow - $totalRows;
			$totalRows    = $endRow - $totalRows;
			$this->progressBar->settotalvalues($totalRows);
			$naayError    = false;
			$errorArray = Array();
			if($startRow!=-1)
			{
				for($row=$startRow;$row<=$endRow;$row++)
				{
					if($this->colbankamount==-1)
					{
						if($bank =='PNB')
						{
							$date        = $objWorksheet->getCellByColumnAndRow($this->coldate, $row)->getFormattedValue();
							$date        = $this->PNBDate($date);
						}
						else
						{
							$date = $objWorksheet->getCellByColumnAndRow($columnSet[0], $row)->getValue();
							if(is_numeric($date))
							{
								$date = date("m/d/Y",PHPExcel_Shared_Date::ExcelToPHP($date));
							}
						}
						$countArray  = $this->checkbalance($objWorksheet,$startRow,$row,$this->colbankamount,$filename,$filepath,$request);
						if(count($countArray)>0)
						{
							$errorArray[]= $this->checkbalance($objWorksheet,$startRow,$row,$this->colbankamount,$filename,$filepath,$request);
						}
						$dateCheck = $this->dateChecking($date,$this->bankdateformat,$row,$filename,$filepath);
						if(count($dateCheck)>0)
						{
							$errorArray[] = $this->dateChecking($date,$this->bankdateformat,$row,$filename,$filepath);
						}
					}
					else
					{
						$date = $objWorksheet->getCellByColumnAndRow($columnSet[0], $row)->getValue();
						if(is_numeric($date)):
							$date = date("m/d/Y",PHPExcel_Shared_Date::ExcelToPHP($date));
						endif;
						$dateCheck = $this->dateChecking($date,$this->bankdateformat,$row,$filename,$filepath);
						if(count($dateCheck)>0)
						{
							$errorArray[] = $this->dateChecking($date,$this->bankdateformat,$row,$filename,$filepath);
						}
						$countArray  = $this->checkbalance($objWorksheet,$startRow,$row,$this->colbankamount,$filename,$filepath,$request);
						if(count($countArray)>0)
						{
							$errorArray[]= $this->checkbalance($objWorksheet,$startRow,$row,$this->colbankamount,$filename,$filepath,$request);
						}
					}
				}
				
			}
			else
			{
				$message = "Invalid Format for $bank $request->bpiType";
				echo $this->invalidFormat($message);
				die();
			}
			
			if(count($errorArray)>0)
			{
				$naayError = true;
				echo $this->showErrors($errorArray);
				die();
			}
			
			if($startRow!=-1)
			{
				if($this->colbankamount==-1)
				{
					$bankdebit    = (float)$objWorksheet->getCellByColumnAndRow($this->colbankdebit, $startRow)->getValue();
					$bankcredit   = (float)$objWorksheet->getCellByColumnAndRow($this->colbankcredit, $startRow)->getValue();
					$bankbalance  = (float)$objWorksheet->getCellByColumnAndRow($this->colbankbalance, $startRow)->getValue();
					
					if($bankdebit==0 and $bankcredit!=0)
					{
						$bankamount  = $bankcredit;
						$recordDTR   = DTR::where('company',$com)
							->where('bu_unit',$bu)
							->where('bank_account_no',$bank_no)
							->orderBy('id','DESC');
						$begbal1     =   $recordDTR->first();
						//echo $recordDTR->count('id');
						if($recordDTR->count('id')>0)
						{
							$begbal1     =   $begbal1->bank_balance;
							$bal         =   $begbal1 + $bankcredit;
							if($bal != $bankbalance)
							{
								$message = "Bank balance is not equal! ".number_format($begbal1,2) ." + ". number_format($bankcredit,2) ." = " . number_format($bal,2)." not equal to current balance " .number_format($bankbalance,2);
								echo $this->invalidFormat($message);
								die();
							}
						}
					}
					else
					{
						$bankamount  = $bankdebit;
						$recordDTR   = DTR::where('company',$com)
							->where('bu_unit',$bu)
							->where('bank_account_no',$bank_no)
							->orderBy('id','DESC');
						$begbal1     =   $recordDTR->first();
						if($recordDTR->count('id')>0)
						{
							$begbal1     =   $begbal1->bank_balance;
							$bal         =   $begbal1 - $bankdebit;
							if($bal != $bankbalance)
							{
								$message = "Bank balance is not equal! ".number_format($begbal1,2) ." - ". number_format($bankdebit,2) ." = " . number_format($bal,2)." not equal to current balance " .number_format($bankbalance,2);
								echo $this->invalidFormat($message);
								die();
							}
						}
					}
				}
				else
				{
					$bankamount  = (float)$objWorksheet->getCellByColumnAndRow($this->colbankamount, $startRow)->getValue();
					$bankbalance = (float)$objWorksheet->getCellByColumnAndRow($this->colbankbalance, $startRow)->getValue();
					
					if($bankamount < 0)
					{
						$type_amount = "AP";
						$recordDTR   = DTR::where('company',$com)
							->where('bu_unit',$bu)
							->where('bank_account_no',$bank_no)
							->orderBy('id','DESC');
						$begbal1     =   $recordDTR->first();
						if($recordDTR->count('id')>0)
						{
							$begbal1     =   $begbal1->bank_balance;
							$bal         =   $begbal1 - $bankamount;
							if($bal != $bankbalance)
							{
								$message = "Bank balance is not equal! ".number_format($begbal1,2) ." - ". number_format($bankamount,2) ." = " . number_format($bal,2)." not equal to current balance " .number_format($bankbalance,2);
								echo $this->invalidFormat($message);
								die();
							}
						}
					}
					else
					{
						$type_amount = "AR";
						$recordDTR   = DTR::where('company',$com)
							->where('bu_unit',$bu)
							->where('bank_account_no',$bank_no)
							->orderBy('id','DESC');
						$begbal1     =   $recordDTR->first();
						if($recordDTR->count('id')>0)
						{
							$begbal1     =   $begbal1->bank_balance;
							$bal         =   $begbal1 + $bankamount;
							if($bal != $bankbalance)
							{
								$message = "Bank balance is not equal! ".number_format($begbal1,2) ." + ". number_format($bankamount,2) ." = " . number_format($bal,2)." not equal to current balance " .number_format($bankbalance,2);
								echo $this->invalidFormat($message);
								die();
							}
						}
					}
				}
				
				
				for($row=$startRow;$row<=$endRow;$row++)
				{
					if($this->colbankamount==-1)
					{
						
						if($bank =='PNB')
						{
							$date     = $objWorksheet->getCellByColumnAndRow($this->coldate, $row)->getFormattedValue();
							$date     = $this->PNBDate($date);
						}
						else
						{
							$date     = $objWorksheet->getCellByColumnAndRow($columnSet[0], $row)->getValue();
							if(is_numeric($date))
							{
								$date = date("m/d/Y",PHPExcel_Shared_Date::ExcelToPHP($date));
							}
						}
						$checkno      = $objWorksheet->getCellByColumnAndRow($this->colchequeno, $row)->getValue();
						
						$varCheck     = preg_match('/\\d/', $checkno);
						
						if($varCheck > 0)
						{
							$output   = preg_replace( '/[^0-9]/', '', $checkno );
							$checkno  = ltrim($output,'0');
						}
						
						$this->sbaNo     !=-1  ? $sbano     = $objWorksheet->getCellByColumnAndRow($this->sbaNo, $row)->getValue()     : $sbano     = '';
						$this->branch    !=-1  ? $branch    = $objWorksheet->getCellByColumnAndRow($this->branch, $row)->getValue()    : $branch    = '';
						$this->transCode !=-1  ? $transCode = $objWorksheet->getCellByColumnAndRow($this->transCode, $row)->getValue() : $transCode = '';
						
						$description  = $objWorksheet->getCellByColumnAndRow($this->coldescription, $row)->getValue();
						$bankdebit    = (float)$objWorksheet->getCellByColumnAndRow($this->colbankdebit, $row)->getValue();
						$bankcredit   = (float)$objWorksheet->getCellByColumnAndRow($this->colbankcredit, $row)->getValue();
						$bankbalance  = (float)$objWorksheet->getCellByColumnAndRow($this->colbankbalance, $row)->getValue();
						
						if($bankdebit==0 and $bankcredit!=0)
						{
							$bankamount  = $bankcredit;
							$type_amount = 'AR';
						}
						else
						{
							$bankamount  = $bankdebit;
							$type_amount = 'AP';
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
						//DTR::updateOrCreate($savedata);
					}
					else
					{
						$date        = $objWorksheet->getCellByColumnAndRow($columnSet[0], $row)->getValue();
						if(is_numeric($date)):
							$date    = date("Y-m-d",PHPExcel_Shared_Date::ExcelToPHP($date));
						endif;
						$checkno     = $objWorksheet->getCellByColumnAndRow($this->colchequeno, $row)->getValue();
						
						$varCheck     = preg_match('/\\d/', $checkno);
						
						if($varCheck > 0)
						{
							$output   = preg_replace( '/[^0-9]/', '', $checkno );
							$checkno  = ltrim($output,'0');
						}
						
						$this->sbaNo     !=-1  ? $sbano     = $objWorksheet->getCellByColumnAndRow($this->sbaNo, $row)->getValue()     : $sbano     = '';
						$this->branch    !=-1  ? $branch    = $objWorksheet->getCellByColumnAndRow($this->branch, $row)->getValue()    : $branch    = '';
						$this->transCode !=-1  ? $transCode = $objWorksheet->getCellByColumnAndRow($this->transCode, $row)->getValue() : $transCode = '';

						$description = $objWorksheet->getCellByColumnAndRow($this->coldescription, $row)->getValue();
						$bankamount  = (float)$objWorksheet->getCellByColumnAndRow($this->colbankamount, $row)->getValue();
						$bankbalance = (float)$objWorksheet->getCellByColumnAndRow($this->colbankbalance, $row)->getValue();
						
						if($bankamount < 0)
						{
							$type_amount = "AP";
						}
						else
						{
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
							'bank_amount'     => abs($bankamount),
							'bank_balance'    => $bankbalance,
							'type_amount'     => $type_amount,
							'company'         => $com,
							'bu_unit'         => $bu
						];
						//DTR::updateOrCreate($savedata);
						

					}
					
					$this->progressBar->setprogress($this->x);
					$this->progressBar->displayprogress();
					$getPercent = $this->progressBar->getpercentrounded();
					$this->x++;
					
					usleep(55565);

				}
				
			}

		});
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
			return '';
		}
	}
	
	public function getStartingRow($objWorksheet,$type = '',$columnSet,$bank)
	{
		$error = Array();
		if($columnSet[8]!=-1):
			for($row=1;$row<=20;$row++)
			{
				$date      = $objWorksheet->getCellByColumnAndRow($columnSet[0], $row)->getValue();
				$amount    = (float)str_replace(",","",$objWorksheet->getCellByColumnAndRow($columnSet[8],$row)->getValue());
				$balance   = (float)str_replace(",","",$objWorksheet->getCellByColumnAndRow($columnSet[9],$row)->getValue());
				$validDate = $this->validateDate($date,'m/d/Y');
				
				if($validDate!=1 and is_float($amount) and is_float($balance) and $amount!=0 and $balance!=0)
				{
					$error[] = $this->dateChecking($date,$this->bankdateformat,$row,$this->filename,$this->filepath);
					echo $this->showErrors($error);
					die();
				}
				
				if($validDate==1 and is_float($amount) and is_float($balance) and $amount!=0 and $balance!=0)
				{
					return $row;
				}
			}
			return -1;
		else:
			for($row=1;$row<=20;$row++)
			{
				if($bank == 'PNB'):
					$date      = $objWorksheet->getCellByColumnAndRow($columnSet[0], $row)->getFormattedValue();
					$date      = $this->PNBDate($date);
				else:
					$date      = $objWorksheet->getCellByColumnAndRow($columnSet[0], $row)->getValue();
				endif;
					$debAmt    = (float)str_replace(",","",$objWorksheet->getCellByColumnAndRow($columnSet[6],$row)->getValue());
					$credAmt   = (float)str_replace(",","",$objWorksheet->getCellByColumnAndRow($columnSet[7],$row)->getValue());
					$balance   = (float)str_replace(",","",$objWorksheet->getCellByColumnAndRow($columnSet[9],$row)->getValue());
					$validDate = $this->validateDate($date,'m/d/Y');
				if($validDate!=1 and ((is_float($debAmt) and $debAmt!=0 and is_float($balance) and $balance!=0) or (is_float($credAmt) and $credAmt!=0 and is_float($balance) and $balance!=0) ))
				{
					 $error[] = $this->dateChecking($date,$this->bankdateformat,$row,$this->filename,$this->filepath);
					 echo $this->showErrors($error);
					 die();
				}
				if($validDate==1 and ((is_float($debAmt) and $debAmt!=0 and is_float($balance) and $balance!=0) or (is_float($credAmt) and $credAmt!=0 and is_float($balance) and $balance!=0) ))
				{
					return $row;
				}
			}
			return -1;
		endif;
	}
	
	public function getEndingRow($objWorksheet,$highestRow,$type = '',$columnSet,$bank)
	{
			if($columnSet[8]!=-1):
				for($row=$highestRow;$row>=0;$row--)
				{
					
					$date      = $objWorksheet->getCellByColumnAndRow($columnSet[0], $row)->getValue();
					$amount    = (float)str_replace(",","",$objWorksheet->getCellByColumnAndRow($columnSet[8],$row)->getValue());
					$balance   = (float)str_replace(",","",$objWorksheet->getCellByColumnAndRow($columnSet[9],$row)->getValue());
					$validDate = $this->validateDate($date,'m/d/Y');
					if($validDate==1 and is_float($amount) and is_float($balance) and $amount!=0 and $balance!=0)
					{
						return $row;
					}
				}
				return -1;
			else:
				for($row=$highestRow;$row>=0;$row--)
				{
					if($bank == 'PNB'):
					    $date      = $objWorksheet->getCellByColumnAndRow($columnSet[0], $row)->getFormattedValue();
					    $date      = $this->PNBDate($date);
					else:
					    $date      = $objWorksheet->getCellByColumnAndRow($columnSet[0], $row)->getValue();
					endif;
						$debAmt    = (float)str_replace(",","",$objWorksheet->getCellByColumnAndRow($columnSet[6],$row)->getValue());
						$credAmt   = (float)str_replace(",","",$objWorksheet->getCellByColumnAndRow($columnSet[7],$row)->getValue());
						$balance   = (float)str_replace(",","",$objWorksheet->getCellByColumnAndRow($columnSet[9],$row)->getValue());
						$validDate = $this->validateDate($date,'m/d/Y');
					if($validDate==1 and ((is_float($debAmt) and $debAmt!=0 and is_float($balance) and $balance!=0) or (is_float($credAmt) and $credAmt!=0 and is_float($balance) and $balance!=0) ))
					{
						return $row;
					}
				}
				return -1;
			endif;
		
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
	
	public function checkbalance($objWorksheet,$startingRow,$row,$colbankamount,$filename,$filepath,$request)
	{
		$errorArray = Array();
		if($colbankamount==-1)
		{
			$begbal = 0;
			if($request->bpiType=='BIZLINK')
			{
				if($row==$startingRow)
				{
					$bankdebit   = (float)$objWorksheet->getCellByColumnAndRow($this->colbankdebit, $row)->getValue();
					$bankcredit  = (float)$objWorksheet->getCellByColumnAndRow($this->colbankcredit, $row)->getValue();
					$bankbalance = (float)$objWorksheet->getCellByColumnAndRow($this->colbankbalance, $row)->getValue();
					if($bankdebit==0 and $bankcredit!=0)
					{
						$begbal = $bankbalance - $bankcredit;
					}
					else
					{
						$begbal = $bankbalance + $bankdebit;
					}
				}
				else
				{
					$bankdebit       = (float)$objWorksheet->getCellByColumnAndRow($this->colbankdebit, $row-1)->getValue();
					$bankcredit      = (float)$objWorksheet->getCellByColumnAndRow($this->colbankcredit, $row-1)->getValue();
					$bankbalance     = (float)$objWorksheet->getCellByColumnAndRow($this->colbankbalance, $row)->getValue();
					$prevbankbalance = (float)$objWorksheet->getCellByColumnAndRow($this->colbankbalance, $row-1)->getValue();
					if($bankdebit==0 and $bankcredit!=0)
					{
						$balance = $prevbankbalance - $bankcredit;
					}
					else
					{
						$balance = $prevbankbalance + $bankdebit;
					}
					
					if((float)trim($balance)!=(float)trim($bankbalance))
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
						$errorArray = [$filename,'Balance is not equal!',$this->colbankbalance,$row,$balance];
					}
				}
			}
			else
			{
				if($row==$startingRow)
				{
					$bankdebit   = (float)$objWorksheet->getCellByColumnAndRow($this->colbankdebit, $row)->getValue();
					$bankcredit  = (float)$objWorksheet->getCellByColumnAndRow($this->colbankcredit, $row)->getValue();
					$bankbalance = (float)$objWorksheet->getCellByColumnAndRow($this->colbankbalance, $row)->getValue();
					if($bankdebit==0 and $bankcredit!=0)
					{
						$begbal = $bankbalance - $bankcredit;
					}
					else
					{
						$begbal = $bankbalance + $bankdebit;
					}
				}
				else
				{
					$bankdebit       = (float)$objWorksheet->getCellByColumnAndRow($this->colbankdebit, $row)->getValue();
					$bankcredit      = (float)$objWorksheet->getCellByColumnAndRow($this->colbankcredit, $row)->getValue();
					$bankbalance     = (float)$objWorksheet->getCellByColumnAndRow($this->colbankbalance, $row)->getValue();
					$prevbankbalance = (float)$objWorksheet->getCellByColumnAndRow($this->colbankbalance, $row-1)->getValue();
					if($bankdebit==0 and $bankcredit!=0)
					{
						$balance = $prevbankbalance + $bankcredit;
					}
					else
					{
						$balance = $prevbankbalance - $bankdebit;
					}
					
					if((float)trim($balance)!=(float)trim($bankbalance))
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
						$errorArray = [$filename,'Balance is not equal!',$this->colbankbalance,$row,$balance];
					}
				}
			}

		}
		else
		{
			$begbal = 0;
			if($row==$startingRow)
			{
				$bankamount  = (float)$objWorksheet->getCellByColumnAndRow($this->colbankamount, $row)->getValue();
				$bankbalance = (float)$objWorksheet->getCellByColumnAndRow($this->colbankbalance, $row)->getValue();
				if($bankamount<0)
				{
					$begbal = $bankbalance + abs($bankamount);
				}
				else
				{
					$begbal = $bankbalance - abs($bankamount);
				}
			}
			else
			{
				$bankamount      = (float)$objWorksheet->getCellByColumnAndRow($this->colbankamount, $row)->getValue();
				$bankbalance     = (float)$objWorksheet->getCellByColumnAndRow($this->colbankbalance, $row)->getValue();
				$prevbankbalance = (float)$objWorksheet->getCellByColumnAndRow($this->colbankbalance, $row-1)->getValue();
				if($bankamount<0)
				{
					$balance = $prevbankbalance - abs($bankamount);
				}
				else
				{
					$balance = $prevbankbalance + abs($bankamount);
				}
				
				if((float)trim($balance)!=(float)trim($bankbalance))
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
					$errorArray = [$filename,'Balance is not equal!',$this->colbankbalance,$row,$balance];
				}
			}
		}
		
		return $errorArray;
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
	
	public function invalidFormat($message)
	{
		return view('DTR.finance.errors.invalidFormat',compact('message'));
	}
	
	public function showErrors($errorArray)
	{
		return view('DTR.finance.errors.errorlist',compact('errorArray'));
	}
	
	public function showBegbalError()
	{
	
	}
	
}