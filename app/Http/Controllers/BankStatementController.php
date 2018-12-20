<?php
namespace App\Http\Controllers;


use App\BankAccount;
use App\BankNo;
use App\BankStatement;
use App\PdcLine;

use App\Functions\BankAmtErrors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

use App\Functions\Bsfunction;
use App\Functions\Checking;
use App\Functions\Progress;
use App\Functions\dis_matching;
use PHPExcel_Shared_Date;

class BankStatementController extends Controller
{
    protected $coldate;
    protected $coldescription;
    protected $colchequeno;
    protected $colbankdebit;
    protected $colbankcredit;
    protected $colbankamount;
    protected $colbankbalance;
    protected $colbankref;
	
    protected $actualbalance;

    protected $bankdateformat;
    protected $bankdatedefaultseparator;
    protected $bankmonth;
    protected $prevbankbalance;
    protected $managcheck;
    protected $column;
    protected $progressBar;
    protected $x;
    protected $index;
    protected $num_row;
    protected $indexeof;

    protected $com;
    protected $bu;

    protected  $dismatching;
	public    $bank;
	
	protected $bankAmtError;

    public function __construct()
    {
        $this->middleware('auth');
		$this->bankAmtError = new BankAmtErrors();
		ini_set('max_file_uploads', 300);
        $this->coldate = 0;
        $this->coldescription =0;
        $this->colchequeno =0;
        $this->colbankdebit = 0;
        $this->colbankcredit = 0;
        $this->colbankamount = 0;
        $this->colbankbalance = 0;

        $this->bankdateformat = "";
        $this->bankdatedefaultseparator ="";
        $this->bankmonth;
        $this->prevbankbalance;
        $this->managcheck='false';
        $this->column;
        $this->progressBar = new Progress();
        $this->x =0;
        $this->index = 1;
        $this->num_row = 0;
        $this->indexeof;
        $this->dismatching = new dis_matching();

      //  echo view('layouts.usersProgress');
        echo view('layouts.progressbar');
        echo view('layouts.bsRadar');
        $this->progressBar->initprogress();
        session()->forget('mgaerrors');



    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Bsfunction $bsFunc,Checking $bCheck)
    {

        $this->com = $request->company;
        $this->bu  = $request->bu_unit;

        $bankName = BankAccount::find($request->bankact);
        $bank     = $bankName->bank;
        $banknoid = $bankName->bankno;
        $bankno   = BankNo::find($banknoid);
        $bank_no  = $bankno->bankno;
        $this->bank = $bank;
        if($bank == "BPI")
        {
            $this->coldate = 0;
            $this->coldescription =1 ;
            $this->colchequeno =3;
            $this->colbankdebit =4;
            $this->colbankcredit=5;
            $this->colbankamount=-1;
            $this->colbankbalance=6;
            
            $this->actualbalance=-1;
	
	        $this->colbankref = 2;
            
            $this->bankdateformat="M d";
            $this->bankdatedefaultseparator=" ";

        }
        elseif($bank == "UCPB")
        {
            $this->coldate = 0;
            $this->coldescription =3 ;
            $this->colchequeno =2;
            $this->colbankdebit =4;
            $this->colbankcredit=5;
            $this->colbankamount=-1;
            $this->colbankbalance=6;
            
	        $this->actualbalance=-1;
	
	        $this->colbankref = -1;

            $this->bankdateformat="n/j/y";
            $this->bankdatedefaultseparator="/";
        }
        elseif($bank == "BDO" or $bank == "Banco de Oro")
        {
            $this->coldate = 0;
            $this->coldescription =3 ;
            $this->colchequeno =2;
            $this->colbankdebit =-1;
            $this->colbankcredit=-1;
            $this->colbankamount=4;
            $this->colbankbalance=5;
	
	        $this->colbankref = -1;
	        
            $this->bankdateformat="d M";
            $this->bankdatedefaultseparator=" ";
        }
        elseif($bank == "LBP")
        {
            $this->coldate = 0;
            $this->coldescription =1 ;
            $this->colchequeno =3;
            $this->colbankdebit =4;
            $this->colbankcredit=5;
            $this->colbankamount=-1;
            $this->colbankbalance=6;
	
	        $this->actualbalance=-1;
	        $this->colbankref = -1;

            $this->bankdateformat="md";
            $this->bankdatedefaultseparator="";
        }
        elseif($bank == "PNB")
        {
            $this->coldate = 0;
            $this->coldescription =2 ;
            $this->colchequeno =3;
            $this->colbankdebit =4;
            $this->colbankcredit=5;
            $this->colbankamount=-1;
            $this->colbankbalance=6;
	
	        $this->actualbalance=-1;
	        $this->colbankref = -1;

            $this->bankdateformat="m/d/y";
            $this->bankdatedefaultseparator="/";
        }
        elseif($bank == "MBTC" or $bank == "Metro Bank" or $bank == "METRO BANK" or $bank == "MB")
        {
            $this->coldate = 0;
            $this->coldescription =1 ;
            $this->colchequeno =2;
            $this->colbankdebit =3;
            $this->colbankcredit=4;
            $this->colbankamount=-1;
            $this->colbankbalance=5;
	
	        $this->actualbalance=-1;
	        $this->colbankref = -1;

            $this->bankdateformat="m/d";
            $this->bankdatedefaultseparator="/";
        }
        elseif($bank == "FCB")
        {
            $this->coldate = 0;
            $this->coldescription =3 ;
            $this->colchequeno =2;
            $this->colbankdebit =4;
            $this->colbankcredit=5;
            $this->colbankamount=-1;
            $this->colbankbalance=7;
	
	        $this->actualbalance=6;
	        $this->colbankref = 1;

            $this->bankdateformat="m/d/y";
            $this->bankdatedefaultseparator="/";
        }
		
		session()->forget('validDatePerMonth');
        session(['validDatePerMonth'=>'']);

        $bCheck->checkError($request,$this->coldate,$this->coldescription,$this->colchequeno,$this->colbankdebit,$this->colbankcredit,$this->colbankamount, $this->colbankbalance,$this->bankdateformat,$this->bankdatedefaultseparator,$this->managcheck,$this->column,$this->bank);
        $this->progressBar->settotalvalues($bCheck->totalRows());

        DB::transaction(function()use($request,$bCheck,$bsFunc,$bank,$bank_no)
        {

        $file = $request->file('mainfiles');
        foreach($file as $key => $files)
        {

            $filepath = $files->getPathName();
            $filename = $files->getClientOriginalName();
            $extensions = array('.xls','.XLS','.xlsx','.XLSX'); // mao rani ang allowed nga file extension .xls or .XLS
            $extension = strrchr($filename, '.'); //gkuha ang file extension nga g-upload nga file.

            // mao ni ang pag-check
            if (!in_array($extension, $extensions))
            {
                 $message = "One of the files has invalid file extension! Only .xls files are accepted to be uploaded!";
                 return view('layouts.message',compact('message'));
                 die();
            }



            $result   = Excel::load($filepath,function($reader)use($bsFunc,$filepath,$filename,$file,$request,$bCheck,$key,$bank,$bank_no)
            {
                $objWorksheet = $reader->getActiveSheet();
                $objWorksheet->getCell('A1');
                $objWorksheet->getCellByColumnAndRow(0,10);
                $highestRow = $objWorksheet->getHighestRow();


                    $this->num_row = $highestRow;

/*
     * Get the min row as index
     *
     * @param int
 */

                $this->indexeof = $bCheck->minRow();

/*
     * Get the Max Row of the file
     *
     * @param int
 */

                for($a=1;$a<=$highestRow;$a++){
                    $RowADate = $objWorksheet->getCellByColumnAndRow($this->coldate, $this->num_row)->getValue();
                    if($bsFunc->findstr($RowADate,"/")=='true' or $bsFunc->findstr($RowADate,"-")=='true'){
                        $RowADate = str_replace(" ",'',$RowADate);
                    }
                    $columnBankbalance = $bsFunc->manipulatenumber($objWorksheet->getCellByColumnAndRow($this->colbankbalance, $this->num_row)->getValue());
                    if($this->colbankamount==-1){
                        $columnBankdebit = $bsFunc->manipulatenumber($objWorksheet->getCellByColumnAndRow($this->colbankdebit, $this->num_row)->getValue());
                        $columnBankcredit = $bsFunc->manipulatenumber($objWorksheet->getCellByColumnAndRow($this->colbankcredit, $this->num_row)->getValue());
                    }
                    else{
                        $columnBankamount = $bsFunc->manipulatenumber($objWorksheet->getCellByColumnAndRow($this->colbankamount, $this->num_row)->getValue());
                    }
                    if(is_numeric($RowADate) and strlen($RowADate)>2  and $bank!="LBP"){
                        $RowADate = date($this->bankdateformat,PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow($this->coldate, $this->num_row)->getValue()));
                    }
					
                    if($this->bankdatedefaultseparator=="-" and strlen($RowADate)!=0)
                    {
                        $expDate = explode("-",$RowADate);
                        $dDate   = $expDate[1];
                        $mDate   = $expDate[0];
                        $yDate   = $request->year;
                        $RowNew_ADate = strtotime("$mDate/$dDate/$yDate");
                    }
                    else
                    {
                        $RowNew_ADate = strtotime($RowADate);
                    }  
					
                    if($this->colbankamount==-1){
                        if($RowNew_ADate == '' or strlen(is_numeric($columnBankbalance)) == 0 or (strlen(is_numeric($columnBankdebit))==0 and strlen(is_numeric($columnBankcredit))==0)){
                            $this->num_row = $this->num_row - 1;

                        }
                        // else{
                        // break;
                        // }
                    }
                    else{
                        if($RowNew_ADate == '' and strlen(is_numeric($columnBankbalance)) == 0 or strlen(is_numeric($columnBankamount))==0){
                            $this->num_row = $this->num_row - 1;
                            //echo $  ;
                        }
                        // else{
                        // break;
                        // }
                    }
                }



                    $index     = $this->indexeof[$key];
                    $status    = "";
					session(['reversal'=>0]);
					$startData = 0;
					
                    while($index<=$this->num_row)
                    {

                    	/*
                    	 * Balance checking file vs database per month
                    	 * */
						if($key==0 and $startData==0)
						{
							echo $this->bankAmtError->checkBalancePrevMonth($objWorksheet,$this->coldate,$this->colbankdebit,$this->colbankcredit,$this->colbankbalance,$this->colbankamount,$this->bankdatedefaultseparator,$this->bankdateformat,$this->com,$this->bu,$request->year,$bank,$index,$bank_no);
							$startData++;
						}				
				
                        /*
                             * Getting Data from Excel To be Save
                             *
                             *
                         */

                        $dateme = $objWorksheet->getCellByColumnAndRow($this->coldate,$index)->getFormattedValue();

//echo $dateme ." => ".$request->year."-" .date("m-d",strtotime($dateme)) ."</br>";
                        $trimdateme = trim($dateme);
                        $pos        = strpos($trimdateme,"-");
                        if($pos==true and $bank=="BDO")
                        {
                            $bankdatedefaultseparator = "-";
                            //echo $bank;
                        }
                        elseif($pos==false and $bank=="BDO")
                        {
                            $bankdatedefaultseparator = " ";
                            //echo $bank;
                        }
                        $columnA = $objWorksheet->getCellByColumnAndRow($this->coldate, $index)->getValue();

                        //  echo $columnA." => " .date($this->bankdateformat,strtotime($columnA)) ."</br>";
                        if($columnA != '')
                        {
                            //$res4 = $bsDate;
                            //$columnD = $checkNo;
                            //$newColumnE = $bankAmt;
                            //$newColumnG = $bankBalance;


                            $newLine = $objWorksheet->getCellByColumnAndRow($this->coldate, $index)->getValue();
                            if(is_numeric($newLine) and strlen($newLine) > 4 and strlen($this->bankdatedefaultseparator)==0){
                                $newLine = strtoupper(date($this->bankdateformat,PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow($this->coldate, $index)->getValue())));
                            }
                            if(strlen($this->bankdatedefaultseparator)>0){
                                if(is_numeric($newLine)){
                                    $newLine = strtoupper(date($this->bankdateformat,PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow($this->coldate, $index)->getValue())));
                                }
                                $temp = explode($this->bankdatedefaultseparator,$newLine);
                                $this->bankdateformat=$this->bankdateformat;
                                $temp2 = explode($this->bankdatedefaultseparator,$this->bankdateformat);
                            }
                            else{
                                $newLine = substr($newLine,0,2).' '.substr($newLine,2,strlen($newLine)-2);
                                $temp = explode(' ',$newLine);
                                $besttemp2=$this->bankdateformat;
                                $besttemp2=substr($besttemp2,0,1).' '.substr($besttemp2,1,1);
                                $temp2 = explode(' ',$besttemp2);
                            }
                            if(strtolower($temp2[0])=="d" or strtolower($temp2[0])=="j"){
                            	if(strtolower($temp[1])=='feb')
	                            {
	                            	$bankmonth = '02';
	                            }
	                            else
	                            {
		                            $bankmonth = date('m',strtotime($temp[1]));
	                            }
	                            
                            }
                            elseif(strtolower($temp2[0])=="m" or strtolower($temp2[0])=="n"){
                                if(!is_numeric($temp[0])){
	                                if(strtolower($temp[0])=='feb')
	                                {
		                                $bankmonth = '02';
	                                }
	                                else
	                                {
		                                $bankmonth = date('m',strtotime($temp[0]));
	                                }
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
                            $res4 = $request->year.'-'.$mon.'-'.$day;


                                $bsDate      = date("Y-m-d",strtotime($res4));


                            $description = $objWorksheet->getCellByColumnAndRow($this->coldescription, $index)->getValue();
                            
                            $checkNo     = $objWorksheet->getCellByColumnAndRow($this->colchequeno, $index)->getValue();
	
	                        $varCheck    = preg_match('/\\d/', $checkNo);
	
	                        if($varCheck > 0)
	                        {
		                        $output = preg_replace( '/[^0-9]/', '', $checkNo );
		
		                        $checkNo = ltrim($output,'0');
	                        }
                         
//                            if(strlen($checkNo)>3){
//                                $cn = explode(" ",$checkNo);
//                                $cn2 = trim($cn[count($cn)-1]);
//                                $x_num1=0;
//                                for($x_num=0;$x_num<=10;$x_num++){
//                                    $colums = substr($cn2,$x_num,1);
//                                    if($colums=="0"){
//                                        $x_num1++;
//                                    }
//                                    else{
//                                        $x_final=$x_num1;
//                                        $colums=substr($cn2,$x_final);
//                                        goto jump_here;
//                                    }
//                                }
//                                jump_here:
//                                $checkNo = $colums;
//                            }
                            if($this->colbankamount==-1){
                                $EntryType  = 'AP';
                                $transtype  = 'Withdrawal';
                                $oldColumnE = $objWorksheet->getCellByColumnAndRow($this->colbankdebit, $index)->getValue();
                                $oldColumnE = $bsFunc->manipulatenumber($oldColumnE);
                                $bankAmt    = $oldColumnE;
                                $oldColumnG = $objWorksheet->getCellByColumnAndRow($this->colbankbalance, $index)->getValue();
                                $oldColumnG = $bsFunc->manipulatenumber($oldColumnG);
                                $bankBalance = $oldColumnG;
                                if($bankAmt == 'false' or $bankAmt == 0){
                                    $EntryType  = 'AR';
                                    $transtype  = 'Deposit';
                                    $oldColumnE = $objWorksheet->getCellByColumnAndRow($this->colbankcredit, $index)->getValue();
                                    $oldColumnE = $bsFunc->manipulatenumber($oldColumnE);
                                    $bankAmt = $oldColumnE;
                                }
                            }
                            else{
                                $EntryType  = 'AR';
                                $transtype  = 'Deposit';
                                $oldColumnE = $objWorksheet->getCellByColumnAndRow($this->colbankamount, $index)->getValue();
                                $oldColumnE = $bsFunc->manipulatenumber($oldColumnE);
                                $bankAmt    = $oldColumnE;
                                $oldColumnG = $objWorksheet->getCellByColumnAndRow($this->colbankbalance, $index)->getValue();
                                $oldColumnG = $bsFunc->manipulatenumber($oldColumnG);
                                $bankBalance = $oldColumnG;
                                if($this->prevbankbalance>$bankBalance)
                                {
                                    $EntryType  = 'AP';
                                    $transtype  = 'Withdrawal';
                                    $oldColumnE = $objWorksheet->getCellByColumnAndRow($this->colbankamount, $index)->getValue();
                                    $oldColumnE = $bsFunc->manipulatenumber($oldColumnE);
                                    $bankAmt    = $oldColumnE;
                                }
                                $this->prevbankbalance=$bankBalance;
                            }
                            // echo "Bank Amount => " . $bankAmt . " Bank Balance => " . $bankBalance ."</br>";
                            $debit_memos = "";
                            if(trim($checkNo)=='' and $EntryType=='AP')
                            {
                                if(trim($checkNo)=="" and $bankAmt !="" )
                                {
                                    $status ="SC";
                                    $debit_memos="debit memos";
                                }
                                else
                                {
                                    $debit_memos="blank";
                                }
                            }
                        //    echo $bsDate;
                            $bsDate = $request->year."-".date("m-d",strtotime($bsDate));
                            if($this->colbankref!=-1)
                            {
	                            $bankref = $objWorksheet->getCellByColumnAndRow($this->colbankref, $index)->getValue();
                            }
                            else
                            {
                            	$bankref = "";
                            }
                          
                            if($this->actualbalance!=-1)
                            {
	                            $actualbal = $bsFunc->manipulatenumber($objWorksheet->getCellByColumnAndRow($this->actualbalance, $index)->getValue());
                            }
                            else
                            {
                            	$actualbal = 0;
                            }						  
                            
                            // $maxbatch     = removecodechars($maxbatch);
                            $bsDate       = $bsFunc->removecodechars($bsDate);
                            $description  = $bsFunc->removecodechars($description);
                            $bank_no      = $bsFunc->removecodechars($bank_no);
                            $checkNo      = $bsFunc->removecodechars($checkNo);
                            $bankAmt      = $bsFunc->removecodechars($bankAmt);
                            $bankBalance  = $bsFunc->removecodechars($bankBalance);
                            $status       = $bsFunc->removecodechars($status);
                            $EntryType    = $bsFunc->removecodechars($EntryType);
                            $transtype    = $bsFunc->removecodechars($transtype);

							$countBSData  = BankStatement::where('bank_check_no',$checkNo)
											->where('bank_account_no',$bank_no)
											->where('bank_date',$bsDate)
											->where('bank_amount',$bankAmt)
											->where('bank_balance',$bankBalance)
											->where('company',$this->com)
											->where('bu_unit',$this->bu)
											->where('type',$EntryType)
											->count('bank_id');
											
							if($bankAmt < 0)
	                        {
		                        session()->forget('reversal');
		                        session(['reversal'=>$bankAmt]);
	                        }				
											
											
							if($countBSData <=0)
							{
								BankStatement::create([
									'bank_account_no'=>$bank_no,
									'bank_check_no'=>$checkNo,
									'bank_date'=>$bsDate,
									'bank_amount'=>$bankAmt,
									'bank_balance'=>$bankBalance,
									'status'=>$status,
									'type'=>$EntryType,
									'description'=>$description,
									'transaction_type'=>$transtype,
									'company'=>$this->com,
									'bu_unit'=>$this->bu,
									'debit_memos'=>$debit_memos,
									'bank_ref_no'=>$bankref,
		                            'actual_balance'=>$actualbal									

								]);
							}
							else
							{
								if($this->colbankref!=-1)
								{							
									BankStatement::where('bank_check_no',$checkNo)
										->where('bank_account_no',$bank_no)
										->where('bank_date',$bsDate)
										->where('bank_amount',$bankAmt)
										->where('bank_balance',$bankBalance)
										->where('company',$this->com)
										->where('bu_unit',$this->bu)
										->where('type',$EntryType)
										->update(['bank_ref_no'=>$bankref]);
								}
								
								if($this->actualbalance!=-1)
								{
									BankStatement::where('bank_check_no',$checkNo)
										->where('bank_account_no',$bank_no)
										->where('bank_date',$bsDate)
										->where('bank_amount',$bankAmt)
										->where('bank_balance',$bankBalance)
										->where('company',$this->com)
										->where('bu_unit',$this->bu)
										->where('type',$EntryType)
										->update(['actual_balance'=>$actualbal]);
								}

								if(session()->get('reversal') !=0 )
								{
									if(abs(session()->get('reversal')) == $bankAmt)
									{
										$countBSData  = BankStatement::where('bank_check_no',$checkNo)
											->where('bank_account_no',$bank_no)
											->where('bank_date',$bsDate)
											->where('bank_amount',$bankAmt)
											->where('bank_balance',$bankBalance)
											->where('company',$this->com)
											->where('bu_unit',$this->bu)
											->where('type',$EntryType)
											->count('bank_id');
										
										if($countBSData ==1 )
										{
											BankStatement::create([
												'bank_account_no'=>$bank_no,
												'bank_check_no'=>$checkNo,
												'bank_date'=>$bsDate,
												'bank_amount'=>$bankAmt,
												'bank_balance'=>$bankBalance,
												'status'=>$status,
												'type'=>$EntryType,
												'description'=>$description,
												'transaction_type'=>$transtype,
												'company'=>$this->com,
												'bu_unit'=>$this->bu,
												'debit_memos'=>$debit_memos,
												'bank_ref_no'=>$bankref,
												'actual_balance'=>$actualbal
											
											]);
										}
										elseif($countBSData > 1)
										{
											//echo "$countBSData => $bankAmt => $bankBalance </br>";
										}
									}
									
								}								
								
							}
							//echo $bankref."</br>";
                        }

                        //  echo $checkNo ."</br>";

                        /*
                             * Set Progress Bar Value
                             *
                             *
                         */

echo "<script>





$('#mid-radar-process').css({
							'transform': 'rotate(' + $index + 'deg)',
'-moz-transform': 'rotate(' + $index + 'deg)',
'-o-transform': 'rotate(' + $index + 'deg)',
'-webkit-transform': 'rotate(' + $index + 'deg)'

});



</script>";

                        $this->progressBar->setprogress($this->x);
                        $this->progressBar->displayprogress();
                        $getPercent = $this->progressBar->getpercentrounded();

                        if($index<$this->num_row){
                            $this->x++;
                        }
                       // usleep(55050);
                        $index++;

                    }


            });
        }
        });

echo'<script>
    $(".absolute-left").removeClass("hidden");
        $(".margin-process-left").fadeOut("slow");
        $(".margin-process-left").fadeIn("slow");
        $(".margin-process-left").addClass("margin-process-right");
        $(".margin-process-left").removeClass("margin-process-left");
        $(".margin-process-left").removeClass("margin-process-left");
        $(".absolute-right").addClass("hidden");
        $("#loadnow").animate({width:"100%"});
		$(".mid-radar-grid-left").fadeOut("slow");
		$(".mid-radar-grid-left").fadeIn("slow");
		$(".mid-radar-grid-left").css("background-image","url(\'css/check.png\')");
				$("#tag-process").html("Disbursement Matching");
		
	</script>';

        $bookcheckno = Array();
$pdc_line = PdcLine::select('id',DB::raw('CAST(check_no as UNSIGNED) as check_no'),'cv_date','check_date','check_amount')
    ->where('label_match','!=','match check')
    ->where('cv_status','Posted')
    ->where('baccount_no',$bank_no)
    ->where('bu_unit',$this->bu)
    ->where('company_code',$this->com)
    //->whereYear('cv_date',$request->year)
    ->orderBy('check_no','ASC')
    ->get();

        foreach ($pdc_line as $row)
        {
            $bookcheckno[] = $row->id."|".$row->check_no."|".$row->cv_date."|".$row->check_date."|".$row->check_amount;
        }

        $bankcheckno = Array();
$bank_dis = BankStatement::select('bank_id',DB::raw('CAST(bank_check_no as UNSIGNED) as bank_check_no'),'bank_date','bank_amount')
       ->where('label_match','!=','match check')
        ->where('bank_account_no',$bank_no)
        ->where('company',$this->com)
        ->where('bu_unit',$this->bu)
        ->orderBy('bank_check_no','ASC')
        ->get();

        foreach ($bank_dis as $row1)
        {
            $bankcheckno[] = $row1->bank_id."|".$row1->bank_check_no."|".$row1->bank_date."|".$row1->bank_amount;
        }

//dd($pdc_line->all());
        $arraycheckno = array();
        foreach($bankcheckno as $key => $value1)
        {
            $exp  = explode("|",$value1);
            $checkno_only = $exp[1];
            $arraycheckno[] = $checkno_only;
        }

        DB::transaction(function()use($bookcheckno,$arraycheckno,$bankcheckno){
            $this->progressBar->settotalvalues(count($bookcheckno));
            $rowme = 1;
            foreach($bookcheckno as $key => $arraybook)
            {
                $this->progressBar->setprogress($rowme);
                $this->progressBar->displayprogress();
                $getPercent = $this->progressBar->getpercentrounded();

                $expl  = explode("|",$arraybook);
                $checkno = $expl[1];

                $this->dismatching->matchingdis($arraycheckno,$checkno,$arraybook,$bankcheckno);

                echo  "<script>

            $('#mid-radar-process').css({
                                        'transform': 'rotate(' + $rowme + 'deg)',
            '-moz-transform': 'rotate(' + $rowme + 'deg)',
            '-o-transform': 'rotate(' + $rowme + 'deg)',
            '-webkit-transform': 'rotate(' + $rowme + 'deg)'
            
            });
            
            </script>";
                $rowme++;
            }
        });


        echo 	'<script>
    $(".absolute-right").removeClass("hidden");
		$(".margin-process-right").fadeOut("slow");
		$(".margin-process-right").fadeIn("slow");

		$(".margin-process-right").addClass("hidden");
	
		$(".mid-radar-grid-right").fadeOut("slow");
		$(".mid-radar-grid-right").fadeIn("slow");
		$(".mid-radar-grid-right").css("background-image","url(\'css/check.png\')");
		

		$("#tag-right").html("Done Disbursement </br>Matching");
	</script>';

        $title = "BRS Uploading";
        $message = "Bank Statement Data Uploaded Successfully";
        echo view('layouts.doneUploading',compact('title','message'));

    }





    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    /**
     *
     * All Function in manipulating data from excel
     *
     *
     */



}
