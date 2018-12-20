<?php
/**
 * Created by PhpStorm.
 * User: Erljoseph143
 * Date: 5/17/2001
 * Time: 10:41 AM
 */

namespace App\Functions;
use Maatwebsite\Excel\Facades\Excel;
use App\Functions\Bsfunction;
use App\Functions\BankAmtErrors;
use PHPExcel_Shared_Date;

class Checking
{
    protected $index1;
    protected $num_row1;

    protected $stralphabet;
    protected $tempcolvalue;
    protected $tempcolbankbalance;
    protected $tempcolbankdebit;
    protected $tempcolbankcredit;
    protected $tempcolbankdebitcredit;
    protected $tempalphabet;
    protected $naayerror;
    protected $filename;
    protected $filepath;
    protected $column;
    protected $temp_bankbalance;
	protected $temp_bankbalance_fcb;
    protected $temp_bankbalme;
    protected $bsfunction;
    protected $year;
    protected $totalrows;

    protected $bankAmtErrors;

    protected $ArrayRows;

    public function __construct()
    {
        $this->index1   = 0;
        $this->num_row1 = 0;
        $this->stralphabet  = "abcdefghijklmnopqrstuvwxyz";
        $this->tempcolvalue = 0;
        $this->tempcolbankbalance = 0;
        $this->tempcolbankdebit = 0;
        $this->tempcolbankcredit = 0;
        $this->tempcolbankdebitcredit = 0;
        $this->tempalphabet = "";
        $this->naayerror='false';
        $this->filename = "";
        $this->column=0;
        $this->temp_bankbalance="";
        $this->temp_bankbalance_fcb = "";		
        $this->temp_bankbalme="";
        $this->filepath="";
        $this->year;
        $this->bankAmtErrors = new BankAmtErrors();
        $this->totalrows;
        $this->ArrayRows = Array();
        $this->bsFunc = new Bsfunction();

        session()->forget('mgaerrors');
    }

    public function checkError($request,$coldate,$coldescription,$colchequeno,$colbankdebit,$colbankcredit,$colbankamount,$colbankbalance,$bankdateformat,$bankdatedefaultseparator,$managcheck,$column,$bank)
    {
        $bsFunc = new Bsfunction();
        $file = $request->file('mainfiles');
        $this->year = $request->year;

        $this->column = $column;

        if($managcheck=='false')
        {

            $bsFunc->addtoerror('<div id=\"errors\">');
            $bsFunc->addtoerror('<table class=\"table table-bordered table-striped\" style=\"width:100%\" border=\"1\">');
            $bsFunc->addtoerror('<tr><td>Description</td><td>Row</td><td>Column</td><td>Value</td><td>Filename</td></tr>');
            foreach ($file as $key2 => $value0)
            {

                $value2 = $value0->getPathName();
                $this->filepath = $value0->getPathName();
                $this->filename = $value0->getClientOriginalName();

                $resultVal = Excel::load($value2,function($reader2)use($bsFunc,$coldate,$coldescription,$colchequeno,$colbankdebit,$colbankcredit,$colbankamount,$colbankbalance,$bankdateformat,$bankdatedefaultseparator,$key2,$value2,$bank){


                    $objWorksheet2 = $reader2->getActiveSheet();
                    $highestRow2 = $objWorksheet2->getHighestRow();
                    $num_row1=$highestRow2;
                    $highestColumn2 = $objWorksheet2->getHighestColumn();

	                
                    for($i=0;$i<=$highestRow2;$i++){
	                    $tempalphabet= strtoupper(substr($this->stralphabet,$coldate,1));
                        $ColumnADate = $objWorksheet2->getCellByColumnAndRow($coldate, $i)->getValue();
                        
                        if($colbankamount==-1)
                        {
	                        $columnBankbalance = $bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankbalance, $i)->getValue());
	                        $columnBankdebit = $bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankdebit, $i)->getValue());
	                        $columnBankcredit = $bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankcredit, $i)->getValue());
							if(($ColumnADate!='' &&  is_numeric(str_replace(".","",$columnBankdebit)) && is_numeric(str_replace(",","",$columnBankbalance)) ) or ($ColumnADate!='' &&  is_numeric(str_replace(".","",$columnBankcredit)) && is_numeric(str_replace(",","",$columnBankbalance))))
							{
								if(is_numeric($ColumnADate) and $bank!="LBP")
								{
									$ColumnADate =date($bankdateformat,PHPExcel_Shared_Date::ExcelToPHP($objWorksheet2->getCellByColumnAndRow($coldate, $i)->getValue()));
								}
								else
								{
									$ColumnADate = $objWorksheet2->getCellByColumnAndRow($coldate, $i)->getValue();
									    $checkTheDate = preg_replace("/[0-9,]/", "", $ColumnADate);
										$checkMonth   = ucfirst(strtolower(trim($checkTheDate)));
										$ColumnADate = str_replace(trim($checkTheDate),$checkMonth,$ColumnADate);
								}
								
								if(trim($this->bsFunc->dateChecking($ColumnADate,$bankdateformat,$this->year,$bankdatedefaultseparator,$this->year,$bankdatedefaultseparator)) == "")
								{
									//echo "$ColumnADate => ".$objWorksheet2->getCellByColumnAndRow($coldate, $i)->getValue(). " $bankdateformat";
									$this->bsFunc->displayerror('Value for Date Posted is Invalid!',$i,$tempalphabet,$ColumnADate,$this->filename,$key2,$value2);
									$this->naayerror='true';
										echo   $this->showMessageError(session()->get('mgaerrors'),'Error in uploading!','home','$.get("removedir",function(result){});');
										die();
								}
							}
                        }
                        
                        if($bsFunc->findstr($ColumnADate,"/")=='true' or $bsFunc->findstr($ColumnADate,"-")=='true'){
                            $ColumnADate = str_replace(" ",'',$ColumnADate);
                            $column=$coldate;
                        }
                        $columnBankbalance = $bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankbalance, $i)->getValue());
                        $column=$colbankbalance;
                        if($colbankamount==-1){
                            $columnBankdebit = $bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankdebit, $i)->getValue());
                            $columnBankcredit = $bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankcredit, $i)->getValue());
                            $column=$colbankdebit;
                            $column=$colbankcredit;
                        }
                        else{
                            $columnBankamount = $bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankamount, $i)->getValue());
                            $column=$colbankamount;
                        }
                        // if(is_numeric($ColumnADate) and strlen($ColumnADate)>3){
                            // $ColumnADate = date($bankdateformat,$objWorksheet2->getCellByColumnAndRow($coldate, $i)->getValue());
                        // }
                        $Columnnew_Adate = strtotime($ColumnADate);

                        if($colbankamount==-1){
                            if($Columnnew_Adate =='' or strlen(is_numeric($columnBankbalance))==0 or (strlen(is_numeric($columnBankdebit))==0 and strlen(is_numeric($columnBankcredit))==0)){
                            }
                            else{
                                $this->index1=$i;
                                goto anhi_diri;
                            }
                        }
                        else{
                            if($Columnnew_Adate =='' or strlen(is_numeric($columnBankbalance))==0 or strlen(is_numeric($columnBankamount))==0){
                            }
                            else{
                                $this->index1=$i;
                                goto anhi_diri;
                            }
                        }

                    }
                    anhi_diri:

                    $this->ArrayRows[] = $this->index1;

                    for($a=$highestRow2;$a>=0;$a--)
                    {
						if($colbankamount==-1)
	                    {
		                    $columnBankbalance = $bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankbalance, $a)->getValue());
		                    $columnBankdebit   = $bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankdebit, $a)->getValue());
		                    $columnBankcredit  = $bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankcredit, $a)->getValue());
							
							$ColumnADate     = $RowADate = $objWorksheet2->getCellByColumnAndRow($coldate, $a)->getValue();
							$ColumnADateTRy = $ColumnADate; 
		                    if(($ColumnADate   !='' &&  is_numeric(str_replace(".","",$columnBankdebit)) && is_numeric(str_replace(",","",$columnBankbalance)) ) or ($ColumnADate!='' &&  is_numeric(str_replace(".","",$columnBankcredit)) && is_numeric(str_replace(",","",$columnBankbalance))))
		                    {
			                    if(is_numeric(trim($ColumnADate)) and $bank!="LBP")
			                    {
				                    //echo $ColumnADate . " $bankdateformat  and $bank !='LBP'</br>";
				                    $ColumnADate =date($bankdateformat,PHPExcel_Shared_Date::ExcelToPHP($objWorksheet2->getCellByColumnAndRow($coldate, $a)->getValue()));
			                    }
			                    else
			                    {
									//echo "dfsdf";
				                    $ColumnADate = $objWorksheet2->getCellByColumnAndRow($coldate, $a)->getValue();
				                    $checkTheDate = preg_replace("/[0-9,]/", "", $ColumnADate);
				                    $checkMonth   = ucfirst(strtolower(trim($checkTheDate)));
				                    $ColumnADate = str_replace(trim($checkTheDate),$checkMonth,$ColumnADate);
			                    }
			
			                    if(trim($this->bsFunc->dateChecking($ColumnADate,$bankdateformat,$this->year,$bankdatedefaultseparator)) == "")
			                    {
									//echo "$ColumnADate => $ColumnADateTRy";
				                    $this->bsFunc->displayerror('Value for Date Posted is Invalid!',$a,$tempalphabet,$ColumnADate,$this->filename,$key2,$value2);
				                    $this->naayerror='true';
				                    echo   $this->showMessageError(session()->get('mgaerrors'),'Error in uploading!','home','$.get("removedir",function(result){});');
				                    die();
			                    }
		                    }
	                    }
						
                        $RowADate = $objWorksheet2->getCellByColumnAndRow($coldate, $a)->getValue();
                        $column=$coldate;
                        if($bsFunc->findstr($RowADate,"/")=='true' or $bsFunc->findstr($RowADate,"-")=='true'){
                            $RowADate = str_replace(" ",'',$RowADate);
                        }
                        $columnBankbalance = $bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankbalance, $a)->getValue());
                        $column=$colbankbalance;
                        if($colbankamount==-1){
                            $columnBankdebit = $bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankdebit, $a)->getValue());
                            $column=$colbankdebit;
                            $columnBankcredit = $bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankcredit, $a)->getValue());
                            $column=$colbankcredit;
                        }
                        else{
                            $columnBankamount = $bsFunc->manipulatenumber($objWorksheet2->getCellByColumnAndRow($colbankamount, $a)->getValue());
                            $column=$colbankamount ;
                        }
                        if(is_numeric($RowADate) and strlen($RowADate)>3  and $bank!="LBP"){
                            //$RowADate = date($bankdateformat,$objWorksheet2->getCellByColumnAndRow($coldate, $a)->getValue());
	                        $RowADate = date($bankdateformat,PHPExcel_Shared_Date::ExcelToPHP($objWorksheet2->getCellByColumnAndRow($coldate, $a)->getValue()));
                        }
						
						if($bankdatedefaultseparator=="-" and strlen($RowADate)!=0)
							{
								$expDate = explode("-",$RowADate);
								$dDate   = $expDate[1];
								$mDate   = $expDate[0];
								$yDate   = $this->year;
								$RowNew_ADate = strtotime("$mDate/$dDate/$yDate");
							}
							else
							{
								$RowNew_ADate = strtotime($RowADate);
							}
                       
                        if($colbankamount==-1){
                            if($RowNew_ADate == '' or strlen(is_numeric($columnBankbalance)) == 0 or (strlen(is_numeric($columnBankdebit))==0 and strlen(is_numeric($columnBankcredit))==0)){
                            }
                            else{
                                $this->num_row1 = $a;
                                goto anhi_diri2;
                            }
                        }
                        else{
                            if($RowNew_ADate == '' or strlen(is_numeric($columnBankbalance)) == 0 or strlen(is_numeric($columnBankamount))==0){
                            }
                            else{
                                $this->num_row1 = $a;
                                goto anhi_diri2;
                            }
                        }
                    }
                    anhi_diri2:

                    if($this->index1==0 and $this->num_row1==0)
                    {
                        $this->tempalphabet=strtoupper(substr($this->stralphabet,$this->column,1));
                        $bsFunc->displayerror('Invalid Bank Statement Format!','','','',$this->filename,$key2,$value2);
                        $this->naayerror='true';
                    }

                    $this->totalrows = $this->totalrows + ($this->num_row1 - $this->index1);

                    //echo $this->totalrows ."+ (".$this->num_row1." - ".$this->index1.") </br>";
                    if($this->index1<=$this->num_row1)
                    {
                        /*---------------------------------------------------------------------------------------
                         * Start Checking Errors of Data from Bank Statement
                         *
                         *---------------------------------------------------------------------------------------
                         */
                        for($row=$this->index1;$row<=$this->num_row1;$row++)
                        {
                            $colvaluebankbalance = $objWorksheet2->getCellByColumnAndRow($colbankbalance,$row)->getValue();
                            if($colbankdebit == -1 and $colbankcredit == -1)
                            {
                                $colvaluebankdebit = 0;
                                $colvaluebankcredit= 0;
                            }
                            else
                            {
                                $colvaluebankdebit   = $objWorksheet2->getCellByColumnAndRow($colbankdebit,$row)->getValue();
                                $colvaluebankcredit  = $objWorksheet2->getCellByColumnAndRow($colbankcredit,$row)->getValue();
                            }
                            if($colbankamount == -1)
                            {
                                $colvaluebankamount  = 0;
                            }
                            else
                            {
                                $colvaluebankamount  = $objWorksheet2->getCellByColumnAndRow($colbankamount,$row)->getValue();
                            }

                            $colvaluebankdate    = $objWorksheet2->getCellByColumnAndRow($coldate, $row)->getValue();
                            $checke              = $objWorksheet2->getCellByColumnAndRow($colchequeno, $row)->getValue();
                            $bankbal_me          = $objWorksheet2->getCellByColumnAndRow($colbankbalance,$row)->getValue();

/*
     * Checking if Bank Balance is Correct
     *
     *
 */
                            if($colbankamount == -1)
                            {
                                $this->bankAmtErrors->negColBankAmt($this->temp_bankbalance,$this->temp_bankbalance_fcb,$this->temp_bankbalme,$objWorksheet2,$row,$colbankbalance,$colbankdebit,$colbankcredit,$this->filename,$this->filepath,$checke,$key2,$bank);
                                $this->temp_bankbalance = $this->bankAmtErrors->tempBankBal();
								$this->temp_bankbalance_fcb = $this->bankAmtErrors->tempBankBalFCB();
                            }
                            else
                            {
                                $this->bankAmtErrors->posColBankAmt($this->temp_bankbalance,$this->temp_bankbalme,$objWorksheet2,$row,$colbankamount,$colbankbalance,$colvaluebankamount,$colvaluebankbalance,$this->filename,$this->filepath,$checke,$key2,$bank);
                                $this->temp_bankbalance = $this->bankAmtErrors->tempBankBal();
                            }
/*
     * Checking if Data Per Column is Correct
     *
     *
 */

                                $Ar = [
                                    $objWorksheet2,
                                    $colvaluebankdate,
                                    $colvaluebankamount,
                                    $colbankamount,
                                    $colvaluebankbalance,
                                    $colbankbalance,
                                    $colvaluebankdebit,
                                    $colvaluebankcredit,
                                    $colbankdebit,
                                    $colbankcredit,
                                    $this->year,
                                    $bankdatedefaultseparator,
                                    $bankdateformat,
                                    $coldate,
                                    $row,
                                    $this->stralphabet,
                                    $this->filename,
                                    $key2,
                                    $this->filepath,
									$bank
                                ];
                                 $this->bankAmtErrors->checkDataError($Ar);
								 
								 $this->bankAmtErrors->checkDateInMonth($Ar);

                        }
                    }

                });
 

            }
            $this->managcheck='true';
            $bsFunc->addtoerror( '</table>');
            $bsFunc->addtoerror( '</div>');

        }

       // return $this->index1 ." => ". $this->num_row1 . "</br>";
         //session()->flush();

        if($this->bankAmtErrors->naayError()=='true'){
            echo   $this->showMessageError(session()->get('mgaerrors'),'Error in uploading!','home','$.get("removedir",function(result){});');
            die();
        }

        //echo session()->get('mgaerrors');
    }

    public function totalRows()
    {
        return $this->totalrows;
    }

    public function minRow()
    {
        return $this->ArrayRows;
    }

    public function maxRow()
    {
        return $this->num_row1;
    }

    public function showMessageError($errors,$title,$route,$getRequest)
    {
        return view('layouts.message',compact('errors','title','route','getRequest'));
    }
}