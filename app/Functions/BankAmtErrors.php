<?php
/**
 * Created by PhpStorm.
 * User: Erljoseph143
 * Date: 5/17/2001
 * Time: 1:44 PM
 */

namespace App\Functions;

use App\BankStatement;
use App\Functions\Bsfunction;
use PHPExcel_Shared_Date;

class BankAmtErrors
{
    protected $fileName;
    protected $filePath;
    protected $bsFunc;
    protected $naayerror;
    protected $temp_bankbalance;
	protected $temp_bankbalance_fcb;

    public function __construct()
    {
        $this->fileName="";
        $this->filePath="";
        $this->bsFunc = new Bsfunction();
        $this->naayerror='false';
        $this->temp_bankbalance;
	    $this->temp_bankbalance_fcb;		
    }

    public function negColBankAmt($temp_bankbalance,$temp_bankbalance_fcb,$temp_bankbalme,$objWorksheet2,$row,$colbankbalance,$colbankdebit,$colbankcredit,$filename,$filepath,$checke,$key2,$bank)
    {
	    
        $dif = 1;
        $bankbal   = $this->bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankbalance,$row)->getValue());
        $bankbal2  = $this->bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankbalance,$row-$dif)->getValue());
	    
	    if(is_numeric(str_replace(".","",$bankbal2)))
        {
            $temp_bankbalance       = $bankbal;
            $this->temp_bankbalance = $bankbal;
        }

		if($bank=="FCB")
	    {
		    $bankbalFCB2   = $this->bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankbalance-1,$row-$dif)->getValue());
		    if(is_numeric(str_replace(".","",$bankbalFCB2)))
		    {
		    	$temp_bankbalance_fcb       = $this->bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankbalance-1,$row)->getValue());
			    $this->temp_bankbalance_fcb = $this->bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankbalance-1,$row)->getValue());
		    }
		    
	    }
		
        $bankdebit  = $this->bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankdebit,$row)->getValue());
        $bankcredit = $this->bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankcredit,$row)->getValue());


        //------------------Bankbalance 2 -------------//
        if(is_numeric(str_replace(".","",$bankbal2)))
        {
            $bankbal2 = $bankbal2;
        }
        elseif(!is_numeric(str_replace(".","",$bankbal2)))
        {
            $bankbal2 = $temp_bankbalance;
        }
        $balme     = $bankbal2;

		$bankbalme = $bankbal;
		$creditme  = $bankcredit;
		$debitme   = $bankdebit;

        $deb_cred  = 0;
		$medata    = "";
        if(($bankdebit =="" or $bankdebit=="0.00")  and ($bankcredit!="" or $bankcredit=="0.00"))
        {
			$tag =  "credit";
			
            $deb_cred  = $creditme;
            $operators = "+";
            $bankbal_true = round(($balme + $creditme),2);
            if($checke !="" and $bankcredit!="")
            {
                $bankbal_true = number_format($bankbal_true,2,'.',',');
                $bankbal_true = str_replace(",","",$bankbal_true);
                $bankbal_true = round(($balme - $creditme),2);
                if((double)$bankbal_true == (double)$bankbalme)
                {
                    $this->bsFunc->displayerror('Error of Bank Statement Format Credit row is not Valid!',$row,'E',$bankbal_true,$filename,$key2,$filepath);
                    // echo "naayerror";
                    // die();
                    $this->naayerror='true';
                }
                else{
                    $bankbal_true = number_format($bankbal_true,2,'.',',');
                    $bankbal_true = str_replace(",","",$bankbal_true);
                    $bankbal_true = round(($balme + $creditme),2);
                    if((double)$bankbal_true == (double)$bankbalme)
                    {
                        //displayerror('Error of Bank Statement Format Credit row is not Valid!',$row,'E',$bankbal_true,$fileNames[$key2],$key2,$value2);
                        // echo "naayerror";
                        // die();
                        goto function_this;
                    }
                }
            }
        }
        else
        {
			$tag = "debit";
            $deb_cred = $debitme;
            $operators = "-";
            $bankbal_true = round(($balme - $debitme),2);
            function_this:
        }
        $bankbal_true = number_format($bankbal_true,2,'.',',');
        $bankbal_true = str_replace(",","",$bankbal_true);

        if((double)trim($bankbal_true) != (double)trim($bankbalme))
        {
           // echo $balme ." $operators ". $deb_cred ." = ".$bankbal_true . " - - - " . $bankbalme."--------".$filename."---------". $row ." $tag --------".$bankbalme ;
           // echo "</br>";
	        if($bank=='FCB')
	        {
	        	$fcbError = $this->fcbBank($temp_bankbalance,$temp_bankbalance_fcb,$temp_bankbalme,$objWorksheet2,$row,$colbankbalance,$colbankdebit,$colbankcredit,$filename,$filepath,$checke,$key2,$bank);
	            if($fcbError==1)
	            {
		            $this->bsFunc->displayerror_bankformat('Value for Bank Balance is Not Equal!',$row,'',$bankbal_true,$filename,$key2,$filepath);
		            $this->naayerror='true';
	            }
	        }
	        else
	        {
		        $this->bsFunc->displayerror_bankformat('Value for Bank Balance is Not Equal!',$row,'',$bankbal_true,$filename,$key2,$filepath);
		        $this->naayerror='true';
	        }

        }


    }

    public function posColBankAmt($temp_bankbalance,$temp_bankbalme,$objWorksheet2,$row,$colbankamount,$colbankbalance,$colvaluebankamount,$colvaluebankbalance,$filename,$filepath,$checke,$key2,$bank)
    {
        $bankamt    = $objWorksheet2->getCellByColumnAndRow($colbankamount,$row)->getValue();
        $bankamtbal = $objWorksheet2->getCellByColumnAndRow($colbankbalance,$row)->getValue();
        if($bankamt != "" and $bankamtbal!="")
        {
            //echo "naay blank";

            $xnum = 1;
            $bankbal      = $objWorksheet2->getCellByColumnAndRow($colbankbalance,$row)->getValue();
            $bankbal2new  = $objWorksheet2->getCellByColumnAndRow($colbankbalance,$row-1)->getValue();
            if($bankbal2new == "" )
            {
                $xnum++;
                $bankbal2 = $objWorksheet2->getCellByColumnAndRow($colbankbalance,$row-$xnum)->getValue();

            }
            else
            {
                $bankbal2 = $objWorksheet2->getCellByColumnAndRow($colbankbalance,$row-1)->getValue();
            }

            $bankbal21 = str_replace(".","",$bankbal2);
            $bankbal21 = str_replace(",","",$bankbal21);


            if($bankbal2 != "" and is_numeric($bankbal21))
            {
                $temp_bankbalance=$bankbal;
                $this->temp_bankbalance = $bankbal;
                //echo "</br>";
            }
			
            // else
            // {
            // $bankbal = $temp_bankbalance;
            // }


            // if($bankdebit ==""  and $bankcredit!="")
            // {

            //------------------Bankbalance 2 -------------//
            if($bankbal2!="" and is_numeric($bankbal21) )
            {
                $bankbal2 = $bankbal2;
                //echo "</br>";
                //echo "Not Blank";
            }
            elseif($bankbal2=="" or !is_numeric($bankbal21))
            {
                $bankbal2 = $temp_bankbalance;

                //echo "</br>";
                //echo "Blank"."----";
            }
			

			
            // $bankbal2;
            $balme = substr(strrchr($bankbal2,"."),1);
            if(substr_count($bankbal2,",")==2 or substr_count($bankbal2,",")==1)
            {
                $bankbal2 = str_replace(",",".",$bankbal2);
                //echo "</br>";
            }
            if(substr_count($bankbal2,".")==0)
            {
                $balme = $bankbal2."00";
            }
            elseif(strlen($balme)==1)
            {
                $balme = $bankbal2."0";
            }
            else
            {
                $balme = $bankbal2;
            }
            $balme = str_replace(".","",$balme);
            $balme = str_replace(",","",$balme);
            $string = $balme;
            $balme="";
            for($xme=strlen($string)-1;$xme>=0;$xme--)
            {
                $balme =$string[$xme].$balme;
                if($xme==strlen($string)-2)
                {
                    $balme=".".$balme;
                }
            }
            //echo $balme;
            //------------------End Bankbalance 2 -------------//
            //------------------Bank Balance -----------------//
            $bankbalme2 = substr(strrchr($bankbal,","),1);

            if(substr_count($bankbal,",")==2 or substr_count($bankbal,",")==1)
            {
                $bankbal = str_replace(",",".",$bankbal);
                //echo "</br>";
            }
            $bankbalme1 = substr(strrchr($bankbal,"."),1);

            if(substr_count($bankbal,".")==0)
            {
                $bankbalme = $bankbal."00";
            }
            elseif(strlen($bankbalme1)==1)
            {
                $bankbalme = $bankbal."0";
            }
            else
            {
                $bankbalme = $bankbal;
            }
            $bankbalme = str_replace(".","",$bankbalme);
            $bankbalme = str_replace(",","",$bankbalme);
            $string1 = $bankbalme;
            $bankbalme="";
            for($xme=strlen($string1)-1;$xme>=0;$xme--)
            {
                $bankbalme =$string1[$xme].$bankbalme;
                if($xme==strlen($string1)-2)
                {
                    $bankbalme=".".$bankbalme;

                }
            }
            $bankbalme;
            //------------------End Bank Balance------------------//

            $colvaluebankamount = str_replace(" ","",$colvaluebankamount);
            $colvaluebankamount = str_replace(",","",$colvaluebankamount);
            $bankbal2           = $this->bsFunc->manipulatenumber($bankbal2);
            $colvaluebankamount = $this->bsFunc->manipulatenumber($colvaluebankamount);

            //$minusbal =  str_replace(",","",number_format($bankbal2 - $colvaluebankamount,2));
			try
			{
				$minusbal =  str_replace(",","",$bankbal2 - $colvaluebankamount);
			}
            catch(\Exception $e)
			{
				echo $e->getMessage()." => $filename $row $bankbal2 - $colvaluebankamount";
				die();
			}

			try
			{
				 $addbal   =  $bankbal2 + $colvaluebankamount;
			}
			catch(\Exception $e)
			{
				echo $e->getMessage() . " => $filename $row $bankbal2 + $colvaluebankamount";
				die();
			}
           
            $va       = $colvaluebankbalance;
            $colvaluebankbalance = $this->bsFunc->manipulatenumber($colvaluebankbalance);

            // echo $bankbal2 . " + " . $colvaluebankamount. " = " . $addbal;
            // echo "</br>";

            // echo( manipulatenumber($bankbal2)."    ".manipulatenumber(trim($minusbal)));
            // echo "</br>";
            if($minusbal == str_replace(",","",number_format($colvaluebankbalance,2)) )
            {
                //echo "minus </br>";
            }
            elseif($addbal == str_replace(",","",$colvaluebankbalance))
            {
                //echo "add </br>";
            }
            elseif(((float)trim($minusbal) != (float)trim($colvaluebankbalance)) and ((float)trim($addbal) != (float)trim($colvaluebankbalance)))
            {
//                 echo $bankbal2 . " + " . $colvaluebankamount. " = " . $addbal . " Equal to " . $colvaluebankbalance;
//                 echo "</br>";

                //echo($minusbal."   ".$colvaluebankbalance)."   ";var_dump(((float)trim($minusbal) == (float)trim($colvaluebankbalance)));
                $this->bsFunc->displayerror_bankformat('Value for Bank Balance is Not Equal!',$row,'',$colvaluebankbalance,$filename,$key2,$filepath);
                $this->naayerror='true';
                // echo $minusbal ." != ".str_replace(",","",$va)." and ".$addbal." != ".str_replace(",","",$colvaluebankbalance);
                // // echo "error";
                echo "</br>";
                //echo "<script>alert('".$colvaluebankamount.",".$fileNames[$key2]."');</script>";
            }
        }

    }
	
	
	public function checkDateInMonth($Ar)
    {
	    $objWorksheet             = $Ar[0];
	    $colvaluebankdate         = $Ar[1];
	    $colvaluebankamount       = $Ar[2];
	    $colbankamount            = $Ar[3];
	    $colvaluebankbalance      = $Ar[4];
	    $colbankbalance           = $Ar[5];
	    $colvaluebankdebit        = $Ar[6];
	    $colvaluebankcredit       = $Ar[7];
	    $colbankdebit             = $Ar[8];
	    $colbankcredit            = $Ar[9];
	    $year                     = $Ar[10];
	    $bankdatedefaultseparator = $Ar[11];
	    $bankdateformat           = $Ar[12];
	    $coldate                  = $Ar[13];
	    $row                      = $Ar[14];
	    $stralphabet              = $Ar[15];
	    $filename                 = $Ar[16];
	    $key2                     = $Ar[17];
	    $filepath                 = $Ar[18];
	    $bank                     = $Ar[19];
	
	    $newLine     = trim($objWorksheet->getCellByColumnAndRow($coldate, $row)->getValue());
		$thisDate    = $newLine;
		
		if($thisDate !="")
		{

			if(is_numeric($newLine) and strlen($newLine) > 4 and strlen($bankdatedefaultseparator)==0)
			{
				$newLine = strtoupper(date($bankdateformat,PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow($coldate, $row)->getValue())));
			}
			if(strlen($bankdatedefaultseparator)>0)
			{
				if(is_numeric($newLine) and $bank!='LBP')
				{
					$newLine     = strtoupper(date($bankdateformat,PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow($coldate, $row)->getValue())));
				}
				$temp            = explode($bankdatedefaultseparator,$newLine);
				$bankdateformat  = $bankdateformat;
				$temp2           = explode($bankdatedefaultseparator,$bankdateformat);
			}
			else
			{
				$newLine         = substr($newLine,0,2).' '.substr($newLine,2,strlen($newLine)-2);
				$temp            = explode(' ',$newLine);
				$besttemp2       = $bankdateformat;
				$besttemp2       = substr($besttemp2,0,1).' '.substr($besttemp2,1,1);
				$temp2           = explode(' ',$besttemp2);
			}
			if(strtolower($temp2[0])=="d" or strtolower($temp2[0])=="j")
			{
				if(strtolower($temp[1])=='feb')
				{
					$bankmonth = '02';
				}
				else
				{
					$bankmonth = date('m',strtotime($temp[1]));
				}				
			}
			elseif(strtolower($temp2[0])=="m" or strtolower($temp2[0])=="n")
			{
				if(!is_numeric($temp[0]))
				{
					if(strtolower($temp[0])=='feb')
					{
						$bankmonth = '02';
					}
					else
					{
						$bankmonth = date('m',strtotime($temp[0]));
					}
				}
				else
				{
					$bankmonth = $temp[0];
				}
			}
			$mon=$bankmonth;
			if(strtolower($temp2[0])=="d" or strtolower($temp2[0])=="j")
			{
				$day = trim($temp[0]);
			}
			elseif(strtolower($temp2[0])=="m" or strtolower($temp2[0])=="n")
			{
				$day = trim($temp[1]);
			}
			$res4    = $year.'-'.$mon.'-'.$day;
		
		
			$bsDate  = date("Y-m-d",strtotime($res4));
		
			$tempalphabet=strtoupper(substr($stralphabet,$coldate,1));
			
			if(session()->get('validDatePerMonth')=="")
			{
				session(['validDatePerMonth'=>$bsDate]);
			}
			else
			{
				$monthNum        = date("m",strtotime(session()->get('validDatePerMonth')));
				$bsDateMonthNum  = date("m",strtotime($bsDate));
				$monthText       = date("F, Y",strtotime(session()->get('validDatePerMonth')));
				if($monthNum!=$bsDateMonthNum)
				{
					$dataDate = str_replace("'","",$objWorksheet->getCellByColumnAndRow($coldate, $row)->getValue());
					$this->bsFunc->displayerror("Value for Date Posted is Not Belong to this Month $monthText!",$row,$tempalphabet,$dataDate,$filename,$key2,$filepath);
					$this->naayerror = 'true';
				}
			 }
		}
		
	    
    }
	
	
	public function checkBalancePrevMonth($objWorksheet,$coldate,$colbankdebit,$colbankcredit,$colbankbalance,$colbankamount,$separator,$dateformat,$com,$bu,$year,$bank,$index,$bank_no)
    {
	    $newLine     = trim($objWorksheet->getCellByColumnAndRow($coldate, $index)->getValue());
	    $thisDate    = $newLine;
	
	    if($thisDate !="")
	    {
		
		    if(is_numeric($newLine) and strlen($newLine) > 4 and strlen($separator)==0)
		    {
			    $newLine = strtoupper(date($dateformat,PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow($coldate, $index)->getValue())));
		    }
		    if(strlen($separator)>0)
		    {
			    if(is_numeric($newLine) and $bank!='LBP')
			    {
				    $newLine     = strtoupper(date($dateformat,PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow($coldate, $index)->getValue())));
			    }
			    $temp            = explode($separator,$newLine);
			    $bankdateformat  = $dateformat;
			    $temp2           = explode($separator,$dateformat);
		    }
		    else
		    {
			    $newLine         = substr($newLine,0,2).' '.substr($newLine,2,strlen($newLine)-2);
			    $temp            = explode(' ',$newLine);
			    $besttemp2       = $dateformat;
			    $besttemp2       = substr($besttemp2,0,1).' '.substr($besttemp2,1,1);
			    $temp2           = explode(' ',$besttemp2);
		    }
		    if(strtolower($temp2[0])=="d" or strtolower($temp2[0])=="j")
		    {
			    if(strtolower($temp[1])=='feb')
			    {
				    $bankmonth = '02';
			    }
			    else
			    {
				    $bankmonth = date('m',strtotime($temp[1]));
			    }
		    }
		    elseif(strtolower($temp2[0])=="m" or strtolower($temp2[0])=="n")
		    {
			    if(!is_numeric($temp[0]))
			    {
				    if(strtolower($temp[0])=='feb')
				    {
					    $bankmonth = '02';
				    }
				    else
				    {
					    $bankmonth = date('m',strtotime($temp[0]));
				    }
			    }
			    else
			    {
				    $bankmonth = $temp[0];
			    }
		    }
		    $mon=$bankmonth;
		    if(strtolower($temp2[0])=="d" or strtolower($temp2[0])=="j")
		    {
			    $day = trim($temp[0]);
		    }
		    elseif(strtolower($temp2[0])=="m" or strtolower($temp2[0])=="n")
		    {
			    $day = trim($temp[1]);
		    }
		    $res4    = $year.'-'.$mon.'-'.$day;
		    
		    $bsDate  = date("Y-m-d",strtotime($res4));
		
            $date = date_create($bsDate);
            date_modify($date, 'last day of previous month');
            $dateNew = date_format($date, 'Y-m-d');

                $monthD = date("m",strtotime($dateNew));
                $yearD  = date("Y",strtotime($dateNew));
                
            
                $bankBal = BankStatement::whereMonth('bank_date',$monthD)
									->whereYear('bank_date',$yearD)
									->where('bank_account_no',$bank_no)
	                                ->where('company',$com)
	                                ->where('bu_unit',$bu)
	                                ->count('bank_id');
                if($bankBal > 0)
                {
	                $bankBal = BankStatement::whereMonth('bank_date',$monthD)
		                ->whereYear('bank_date',$yearD)
		                ->where('bank_account_no',$bank_no)
		                ->where('company',$com)
		                ->where('bu_unit',$bu)
	                    ->get()->last();

	             //   echo $bankBal->bank_balance;
	                $bankBalance = $bankBal->bank_balance;
	                $fileBalance = $this->bsFunc->manipulatenumber($objWorksheet->getCellByColumnAndRow($colbankbalance,$index)->getValue());
	                if($colbankamount==-1)
	                {
	                	$fileDebit  = $this->bsFunc->manipulatenumber($objWorksheet->getCellByColumnAndRow($colbankdebit,$index)->getValue());
	                	$fileCredit = $this->bsFunc->manipulatenumber($objWorksheet->getCellByColumnAndRow($colbankcredit,$index)->getValue());
						if($bank=="FCB")
						{
							if(($fileDebit!='' and $fileDebit!='0.00'))
							{
								$balFile = round($fileBalance + $fileDebit,2);
							}
							elseif(($fileCredit!='' and $fileCredit!='0.00'))
							{
								$balFile = round($fileBalance - $fileCredit,2);
							}
							
							
							if(trim($balFile)!=trim($bankBalance))
							{
				
								$fileBalance = $this->bsFunc->manipulatenumber($objWorksheet->getCellByColumnAndRow($colbankbalance-1,$index)->getValue());
								$bankBalance = $bankBal->actual_balance;
								if(($fileDebit!='' and $fileDebit!='0.00'))
								{
									$balFile = round($fileBalance + $fileDebit,2);
								}
								elseif(($fileCredit!='' and $fileCredit!='0.00'))
								{
									$balFile = round($fileBalance - $fileCredit,2);
									
								}
								if(trim($balFile)!=trim($bankBalance))
								{
									$begBal   = $balFile;
									$endBal   = $bankBalance;
									$bsDate   = date("F ,Y",strtotime($bsDate));
									$prevDate = date("F ,Y",strtotime($dateNew));
									echo view('layouts.errorFileBalance',compact('begBal','endBal','bsDate','prevDate'));
									die();
								}
							}
						}
						else
						{
							
							if(($fileDebit!='' and $fileDebit!='0.00'))
							{
								$balFile = round($fileBalance + $fileDebit,2);
							}
							elseif(($fileCredit!='' and $fileCredit!='0.00'))
							{
								$balFile = round($fileBalance - $fileCredit,2);
							}
							
							if(trim($balFile)!=trim($bankBalance))
							{
								$begBal   = $balFile;
								$endBal   = $bankBalance;
								$bsDate   = date("F ,Y",strtotime($bsDate));
								$prevDate = date("F ,Y",strtotime($dateNew));
								echo view('layouts.errorFileBalance',compact('begBal','endBal','bsDate','prevDate'));
								die();
							}
						}
						
	                }
	                else
	                {
	                	$bankamount = $this->bsFunc->manipulatenumber($objWorksheet->getCellByColumnAndRow($colbankamount,$index)->getValue());
		                $balFile    = round($fileBalance + $bankamount,2);
		                if(trim($balFile) != trim($bankBalance))
		                {
						
							$balFile = round($fileBalance - $bankamount,2);
							if(trim($balFile)!=trim($bankBalance))
							{
								$begBal   = $balFile;
								$endBal   = $bankBalance;
								$bsDate   = date("F ,Y",strtotime($bsDate));
								$prevDate = date("F ,Y",strtotime($dateNew));
								echo view('layouts.errorFileBalance',compact('begBal','endBal','bsDate','prevDate'));
								die();
							}
							
		                }
	                }

                }
           
            
	    }
	    
    }

    public function checkDataError($Ar)
    {
        $objWorksheet             = $Ar[0];
        $colvaluebankdate         = $Ar[1];
        $colvaluebankamount       = $Ar[2];
        $colbankamount            = $Ar[3];
        $colvaluebankbalance      = $Ar[4];
        $colbankbalance           = $Ar[5];
        $colvaluebankdebit        = $Ar[6];
        $colvaluebankcredit       = $Ar[7];
        $colbankdebit             = $Ar[8];
        $colbankcredit            = $Ar[9];
        $year                     = $Ar[10];
        $bankdatedefaultseparator = $Ar[11];
        $bankdateformat           = $Ar[12];
        $coldate                  = $Ar[13];
        $row                      = $Ar[14];
        $stralphabet              = $Ar[15];
        $filename                 = $Ar[16];
        $key2                     = $Ar[17];
        $filepath                 = $Ar[18];
        if(strlen($colvaluebankdate)>0)
        {
            $res4 ="";

            if($this->bsFunc->findstr($colvaluebankdate,"/")=='true' or $this->bsFunc->findstr($colvaluebankdate,"-")=='true'){
                $colvaluebankdate = str_replace(" ",'',$colvaluebankdate);
            }
            $tempalphabet=strtoupper(substr($stralphabet,$coldate,1));
            if(is_numeric($colvaluebankdate) and strlen($colvaluebankdate) > 4 and strlen($bankdatedefaultseparator)==0){
                $colvaluebankdate = strtoupper(date($bankdateformat,PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow($coldate, $row)->getValue())));
            }
            if(strlen($bankdatedefaultseparator)>0){
                if(is_numeric($colvaluebankdate)){
                    $colvaluebankdate = strtoupper(date($bankdateformat,PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow($coldate, $row)->getValue())));
                }
				
				$checkTheDate = preg_replace("/[0-9,]/", "", $colvaluebankdate);
	            $checkMonth   = ucfirst(strtolower(trim($checkTheDate)));
	            $colvaluebankdate = str_replace(trim($checkTheDate),$checkMonth,$colvaluebankdate);
				
                if(trim($this->bsFunc->dateChecking($colvaluebankdate,$bankdateformat,$year,$bankdatedefaultseparator)) == "")
                {
					$dataDate = str_replace("'","",$objWorksheet->getCellByColumnAndRow($coldate, $row)->getValue());
	                $this->bsFunc->displayerror('Value for Date Posted is Invalid!',$row,$tempalphabet,$dataDate,$filename,$key2,$filepath);
	                $this->naayerror='true';
                }
	            //echo $this->bsFunc->dateChecking($colvaluebankdate,$bankdateformat) . " " .$colvaluebankdate."</br>";
                if (strpos($colvaluebankdate, $bankdatedefaultseparator) !== false)
                {
                    $temp = explode($bankdatedefaultseparator,$colvaluebankdate);
                    $bankdateformat=$bankdateformat;
                    $temp2 = explode($bankdatedefaultseparator,$bankdateformat);

                    if(strtolower($temp2[0])=="d" or $temp2[0]=="j"){
                        $bankmonth = date('m',strtotime($temp[1]));
                    }
                    elseif(strtolower($temp2[0])=="m" or strtolower($temp2[0])=="n"){
                        if(!is_numeric($temp[0])){
                            $bankmonth = date('m',strtotime($temp[0]));
                        }
                        else{
                            $bankmonth = $temp[0];
                        }
                    }
                    $mon=$bankmonth;
                    if(strtolower($temp2[0])=="d" or strtolower($temp2[0])=="j"){
                        $day = trim($temp[0]);
                    }
                    elseif(strtolower($temp2[0])=="m" or strtolower($temp2[0])=="n"){
                        $day = trim($temp[1]);
                    }
                    $res4 = $year.'-'.$mon.'-'.$day;                    
                }
                // else
                // {
                //     $this->bsFunc->displayerror('Value for Date Posted is Invalid!',$row,$tempalphabet,$colvaluebankdate,$filename,$key2,$filepath);
                //     $this->naayerror='true';                    
                // }

            }
            else
            {
                $colvaluebankdate = substr($colvaluebankdate,0,2).' '.substr($colvaluebankdate,2,strlen($colvaluebankdate)-2);
				$bankdateformat = "m d";
				if(trim($this->bsFunc->dateChecking($colvaluebankdate,$bankdateformat,$year,$bankdatedefaultseparator)) == "")
                {
					$dataDate = str_replace("'","",$objWorksheet->getCellByColumnAndRow($coldate, $row)->getValue());
	                $this->bsFunc->displayerror('Value for Date Posted is Invalid!',$row,$tempalphabet,$dataDate,$filename,$key2,$filepath);
	                $this->naayerror='true';
                }
				
                $temp = explode(' ',$colvaluebankdate);
                $besttemp2=$bankdateformat;
                $besttemp2=substr($besttemp2,0,1).' '.substr($besttemp2,1,1);
                $temp2 = explode(' ',$besttemp2);
          
                if(strtolower($temp2[0])=="d"){
                    $bankmonth = date('m',strtotime($temp[1]));
                }
                elseif(strtolower($temp2[0])=="m"){
                    if(!is_numeric($temp[0])){
                        $bankmonth = date('m',strtotime($temp[0]));
                    }
                    else{
                        $bankmonth = $temp[0];
                    }
                }
                $mon=$bankmonth;
                if(strtolower($temp2[0])=="d"){
                    $day = trim($temp[0]);
                }
                elseif(strtolower($temp2[0])=="m"){
                    $day = trim($temp[1]);
                }
                $res4 = $year.'-'.$mon.'-'.$day;                
            }

			

//            echo " => " . $filename . " => " . $colvaluebankamount . " => ".strlen(strtotime($res4));
//            echo "</br>";
            if(strlen(strtotime($res4))==0){

                $this->bsFunc->displayerror('Value for Date Posted is Invalid!',$row,$tempalphabet,str_replace("'","",$colvaluebankdate),$filename,$key2,$filepath);
                $this->naayerror='true';
            }
            else
            {
                if(strlen(is_numeric($this->bsFunc->manipulatenumber($colvaluebankbalance)))==0)
                {

                    $tempalphabet=strtoupper(substr($stralphabet,$colbankbalance,1));

                    $this->bsFunc->displayerror('Value for Bank Balance is Invalid!',$row,$tempalphabet,$colvaluebankbalance,$filename,$key2,$filepath);
                    $this->naayerror='true';
                }
                if($colbankamount==-1)
                {
					$colvaluebankdebit  = str_replace('-', '',$colvaluebankdebit);
                    $colvaluebankcredit = str_replace('-', '', $colvaluebankcredit);
                    if((strlen(is_numeric($this->bsFunc->manipulatenumber($colvaluebankdebit)))==0 or $colvaluebankdebit==0) and (strlen(is_numeric($this->bsFunc->manipulatenumber($colvaluebankcredit)))==0 or $colvaluebankcredit==0))
                    {
                        if(strlen(is_numeric($this->bsFunc->manipulatenumber($colvaluebankdebit)))==0 and $colvaluebankcredit==0)
                        {
                            $tempalphabet=strtoupper(substr($stralphabet,$colbankdebit,1));
                            $this->bsFunc->displayerror('Value for Bank Debit Amount is Invalid!',$row,$tempalphabet,$colvaluebankdebit,$filename,$key2,$filepath);
                            $this->naayerror='true';
                        }
                        elseif(strlen(is_numeric($this->bsFunc->manipulatenumber($colvaluebankcredit)))==0 and $colvaluebankdebit==0)
                        {
                            $tempalphabet=strtoupper(substr($stralphabet,$colbankcredit,1));
                            $this->bsFunc->displayerror('Value for Bank Credit Amount is Invalid!',$row,$tempalphabet,$colvaluebankcredit,$filename,$key2,$filepath);
                            $this->naayerror='true';
                        }
                    }
                }
                else
                {

                    if(strlen(is_numeric($this->bsFunc->manipulatenumber($colvaluebankamount)))==0)
                    {

                        $tempalphabet=strtoupper(substr($stralphabet,$colbankamount,1));

                        $this->bsFunc->displayerror('Value for Bank Amount is Invalid!',$row,$tempalphabet,$colvaluebankamount,$filename,$key2,$filepath);
                        $this->naayerror='true';
                    }
                }

            }
        }

    }

    public function showMessageError($errors,$title,$route,$getRequest)
    {
        return view('layouts.message',compact('errors','title','route','getRequest'));
    }

    public function naayError()
    {
        return $this->naayerror;
    }

    public function tempBankBal()
    {
        return $this->temp_bankbalance;
    }
	
	public function tempBankBalFCB()
    {
    	return $this->temp_bankbalance_fcb;
    }
    
    public function fcbBank($temp_bankbalance,$temp_bankbalance_fcb,$temp_bankbalme,$objWorksheet2,$row,$colbankbalance,$colbankdebit,$colbankcredit,$filename,$filepath,$checke,$key2,$bank)
    {
    	$error = 0;
	    $dif = 1;
	    back:
	    $bankbal   = $this->bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankbalance-1,$row)->getValue());
	    if($error==1)
	    {
		    $bankbal2  = $this->bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankbalance-1,$row-$dif)->getValue());
	    }
	    else
	    {
		    $bankbal2  = $this->bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankbalance,$row-$dif)->getValue());
	    }
	
	    if(is_numeric(str_replace(".","",$bankbal2)))
	    {
		    $temp_bankbalance       = $bankbal;
		    $this->temp_bankbalance = $bankbal;
		    $temp_bankbalance_fcb   = $this->bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankbalance-1,$row-$dif)->getValue());			
	    }
	
	    $bankdebit  = $this->bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankdebit,$row)->getValue());
	    $bankcredit = $this->bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankcredit,$row)->getValue());
	
	
	    //------------------Bankbalance 2 -------------//
	    if(is_numeric(str_replace(".","",$bankbal2)))
	    {
		    $bankbal2 = $bankbal2;
	    }
	    elseif(!is_numeric(str_replace(".","",$bankbal2)))
	    {
		    $bankbal2 = $temp_bankbalance;
			if($error == 1)
		    {
			    $bankbal2 = $temp_bankbalance_fcb;
		    }
	    }
	    $balme     = $bankbal2;
	
	    $bankbalme = $bankbal;
	    $creditme  = $bankcredit;
	    $debitme   = $bankdebit;
	
	    $deb_cred  = 0;
	    if(($bankdebit =="" or $bankdebit=="0.00")  and ($bankcredit!="" or $bankcredit=="0.00"))
	    {
		    $deb_cred  = $creditme;
		    $operators = "+";
		    $bankbal_true = round(($balme + $creditme),2);
		    if($checke !="" and $bankcredit!="")
		    {
			    $bankbal_true = number_format($bankbal_true,2,'.',',');
			    $bankbal_true = str_replace(",","",$bankbal_true);
			    $bankbal_true = round(($balme - $creditme),2);
			    if((double)$bankbal_true == (double)$bankbalme)
			    {
				    $this->bsFunc->displayerror('Error of Bank Statement Format Credit row is not Valid!',$row,'E',$bankbal_true,$filename,$key2,$filepath);
				    // echo "naayerror";
				    // die();
				    $this->naayerror='true';
			    }
			    else{
				    $bankbal_true = number_format($bankbal_true,2,'.',',');
				    $bankbal_true = str_replace(",","",$bankbal_true);
				    $bankbal_true = round(($balme + $creditme),2);
				    if((double)$bankbal_true == (double)$bankbalme)
				    {
					    //displayerror('Error of Bank Statement Format Credit row is not Valid!',$row,'E',$bankbal_true,$fileNames[$key2],$key2,$value2);
					    // echo "naayerror";
					    // die();
					    goto function_this;
				    }
			    }
		    }
	    }
	    else
	    {
		    $deb_cred = $debitme;
		    $operators = "-";
		    $bankbal_true = round(($balme - $debitme),2);
		    function_this:
	    }
	    $bankbal_true = number_format($bankbal_true,2,'.',',');
	    $bankbal_true = str_replace(",","",$bankbal_true);
	
	    if((double)$bankbal_true != (double)$bankbalme)
	    {
	    	if($error ==1)
		    {
			    return 1;
		    }
			$error = 1;
	    	goto back;
	    }
	    return -1;
    }

}