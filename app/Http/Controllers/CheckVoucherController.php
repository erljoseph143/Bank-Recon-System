<?php

namespace App\Http\Controllers;

use App\BankStatement;
use App\Functions\dis_matching;
use App\Functions\Progress;
use App\PdcLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Shared_Date;

class CheckVoucherController extends Controller
{
    //
    protected $com;
    protected $bu;
    protected $totalRow;
    protected $bank_number;
    protected $cv_date_me;
    protected $progressBar;
    protected $x;
    protected $bank_no;

    protected $dismatching;

    public function __construct()
    {
        $this->totalRow = 0;
        $this->bank_number ="";
        $this->cv_date_me="";
        $this->progressBar = new Progress();
        echo view('layouts.progressbar');
        echo view('layouts.bsRadar');
        $this->progressBar->initprogress();
        $this->x =0;
        $this->dismatching = new dis_matching();
        $this->bank_no ="";

    }

    public function store(Request $request)
    {

        $this->com = Auth::user()->company_id;
        $this->bu  = Auth::user()->bunitid;

      $file =  $request->file('mainfiles1excel');
    //  dd($file);
        /*-------------------------------------------------------------------------------------------------------------------------
         * Check the file if it is an excel format or not
         *-------------------------------------------------------------------------------------------------------------------------
         */
        foreach ($file as $filecheck)
        {
            $fileName = $filecheck->getClientOriginalName();
            $extensions = array('.xls','.XLS','.xlsx','.XLSX'); // mao rani ang allowed nga file extension .xls,.XLS,.xlsx or XLSX
            $extension = strrchr($fileName, '.');
            if (!in_array($extension, $extensions))
            {
                $this->showMessageError("One of the files has invalid file extension!Only .xls or xlsx files are accepted to be uploaded!","Error!",'home');
                die();
            }
        }
        /*-------------------------------------------------------------------------------------------------------------------------
         * Check the excel file header format is correct, Set also the Total Rows for progress bar
         *-------------------------------------------------------------------------------------------------------------------------
         */
        foreach($file as $files)
        {
            $path = $files->getPathName();

            Excel::load($path,function($reader){
                $objWorksheet  = $reader->getActiveSheet();
                $highestRow    = $objWorksheet->getHighestRow();
                $highestColumn = $objWorksheet->getHighestColumn();


                $formatcv           = strtolower($objWorksheet->getCellByColumnAndRow(0,1));
                $formatcvstatus     = strtolower($objWorksheet->getCellByColumnAndRow(2,1));
                $formatcvdate       = strtolower($objWorksheet->getCellByColumnAndRow(3,1));
                $formatcheckno      = strtolower($objWorksheet->getCellByColumnAndRow(4,1));
                $formatcheckamount  = strtolower($objWorksheet->getCellByColumnAndRow(5,1));
                $formatbankno       = strtolower($objWorksheet->getCellByColumnAndRow(6,1));
                $formatcheckdate    = strtolower($objWorksheet->getCellByColumnAndRow(8,1));
                $formatclearingdate = strtolower($objWorksheet->getCellByColumnAndRow(9,1));
                $formatpayee        = strtolower($objWorksheet->getCellByColumnAndRow(11,1));

                if( $formatcv              !="cv no."
                    or $formatcvstatus     !="cv status"
                    or $formatcvdate       !="cv date"
                    or $formatcheckno      !="check number"
                    or $formatcheckamount  !="check amount"
                    or $formatbankno       !="bank account no."
                    or $formatcheckdate    !="check date"
                    or $formatclearingdate !="clearing date"
                    or $formatpayee        !="payee"
                )
                {
                    $this->showMessageError("Invalid Check Voucher!","Error!",'home');
                    die();
                }

                $index=1;
                $num_row=$highestRow;
                for($i=1;$i<20;$i++)
                {
                    $ColumnCv=$objWorksheet->getCellByColumnAndRow(0,$index)->getValue();
                    $dateCk = $objWorksheet->getCellByColumnAndRow(8, $index)->getValue();
                    if(is_numeric(trim($dateCk))):
                        $ColumnADate = date('d/m/Y',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(8, $index)->getValue()));
                    else:
                        $ColumnADate = date('d/m/Y',strtotime($objWorksheet->getCellByColumnAndRow(8, $index)->getValue()));
                    endif;                   $Columnnew_Adate = strtotime($ColumnADate);
                    if(($ColumnADate == '' and $ColumnCv=='CV No.') or $ColumnCv=='CV No.')
                    {
                        $index++;
                    }
                }
                for($a=1;$a<=50;$a++)
                {
                    $RowADate = $objWorksheet->getCellByColumnAndRow(2, $num_row)->getValue();
                    $dateCk = $objWorksheet->getCellByColumnAndRow(8, $num_row)->getValue();
                    if(is_numeric(trim($dateCk))):
                        $RowNew_ADate = date('d/m/Y',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(8, $num_row)->getValue()));
                    else:
                        $RowNew_ADate = date('d/m/Y',strtotime($objWorksheet->getCellByColumnAndRow(8, $num_row)->getValue()));
                    endif;
                    if(($RowNew_ADate == '' and $ColumnCv=='') or $ColumnCv=='')
                    {
                        $num_row = $num_row - 1;
                    }
                }
                $this->totalRow = $this->totalRow + ($num_row - $index);

            });
        }
        /*-------------------------------------------------------------------------------------------------------------------------
         * Start Saving Data from excel to database
         *-------------------------------------------------------------------------------------------------------------------------
         */

        $this->progressBar->settotalvalues($this->totalRow);

        DB::transaction(function ()use($file){
            foreach ($file as $files)
            {
                $path = $files->getPathName();

                Excel::load($path,function($reader){

                    $objWorksheet  = $reader->getActiveSheet();
                    $highestRow    = $objWorksheet->getHighestRow();
                    $highestColumn = $objWorksheet->getHighestColumn();

                    $index   = 1;
                    $num_row = $highestRow;
                    for($i=1;$i<20;$i++)
                    {
                        $ColumnCv=$objWorksheet->getCellByColumnAndRow(0,$index)->getValue();
                        $dateCk = $objWorksheet->getCellByColumnAndRow(8, $index)->getValue();
                        if(is_numeric(trim($dateCk))):
                            $ColumnADate = date('d/m/Y',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(8, $index)->getValue()));
                        else:
                            $ColumnADate = date('d/m/Y',strtotime($objWorksheet->getCellByColumnAndRow(8, $index)->getValue()));
                        endif;                    $Columnnew_Adate = strtotime($ColumnADate);
                        if(($ColumnADate == '' and $ColumnCv=='CV No.') or $ColumnCv=='CV No.')
                        {
                            $index++;
                        }
                    }
                    for($a=1;$a<=50;$a++)
                    {
                        $RowADate = $objWorksheet->getCellByColumnAndRow(3, $num_row)->getValue();
                        $ColumnCv=$objWorksheet->getCellByColumnAndRow(0,$num_row)->getValue();
                        $dateCk = $objWorksheet->getCellByColumnAndRow(8, $num_row)->getValue();
                        if(is_numeric(trim($dateCk))):
                            $RowNew_ADate = date('d/m/Y',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(8, $num_row)->getValue()));
                        else:
                            $RowNew_ADate = date('d/m/Y',strtotime($objWorksheet->getCellByColumnAndRow(8, $num_row)->getValue()));
                        endif;
                        if($RowNew_ADate == '' or $ColumnCv=='')
                        {
                            $num_row = $num_row - 1;
                        }
                    }

                    $this->bank_number = $objWorksheet->getCellByColumnAndRow(6,$index);
                    $this->cv_date_me  = $objWorksheet->getCellByColumnAndRow(3,$index);

                    while($index<=$num_row)
                    {
                        $this->progressBar->setprogress($this->x);
                        $this->progressBar->displayprogress('true');
                        $getPercent = $this->progressBar->getpercentrounded();
                        if($index<$num_row)
                        {
                            $this->x++;
                        }

                        $bank_no         = $objWorksheet->getCellByColumnAndRow(6, $index)->getValue();
                        $cv_no           = $objWorksheet->getCellByColumnAndRow(0, $index)->getValue();
                        $cv_status       = $objWorksheet->getCellByColumnAndRow(2, $index)->getValue();
                        $ck_no           = $objWorksheet->getCellByColumnAndRow(4, $index)->getValue();
                        $bank_no         = $objWorksheet->getCellByColumnAndRow(6, $index)->getValue();
                        $this->bank_no   = $bank_no;
                        $ck_amount12     = $objWorksheet->getCellByColumnAndRow(5, $index)->getValue();

                        $cvDate         = $objWorksheet->getCellByColumnAndRow(3, $index)->getValue();
                        if(is_numeric($cvDate)):
                            $cv_date         = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(3, $index)->getValue()));
                        else:
                            $expDate = explode("/",$cvDate);
                            $yearDate = $expDate[2];
                            $monthDate = $expDate[1];
                            $dayDate   = $expDate[0];
                            $newDataDate = $yearDate."-".$monthDate."-".$dayDate;
                            $cv_date         = date('Y-m-d',strtotime($newDataDate));
                        endif;
                        $checkDate       = $objWorksheet->getCellByColumnAndRow(8, $index)->getValue();
                        if(is_numeric($checkDate)):
                            $check_date      = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(8, $index)->getValue()));
                        else:
                            $expDate = explode("/",$checkDate);
                            $yearDate = $expDate[2];
                            $monthDate = $expDate[1];
                            $dayDate   = $expDate[0];
                            $newDataDate = $yearDate."-".$monthDate."-".$dayDate;
                            $check_date      = date('Y-m-d',strtotime($newDataDate));
                        endif;

//                        $cancelledDate  = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(12, $index)->getValue()));
//                        if(is_numeric($cancelledDate)):
//                            $cancelled_date  = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(12, $index)->getValue()));
//                        else:
//                            $expDate = explode("/",$cancelledDate);
//                            $yearDate = $expDate[2];
//                            $monthDate = $expDate[1];
//                            $dayDate   = $expDate[0];
//                            $newDataDate = $yearDate."-".$monthDate."-".$yearDate;
//                            $cancelled_date  = date('Y-m-d',strtotime($newDataDate));
//                        endif;
                        $bank_date_exist = $objWorksheet->getCellByColumnAndRow(9, $index)->getValue();
                        $payee           = $objWorksheet->getCellByColumnAndRow(11, $index)->getValue();
                        $xs              = substr($ck_amount12,0,1);

                        if($xs=="-")
                        {
                            $ck_amount = str_replace("-","",$ck_amount12);
                        }
                        else
                        {
                            $ck_amount = $ck_amount12;
                        }

                        if($bank_date_exist !='')
                        {
                            $bank_date = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(9, $index)->getValue()));
                        }
                        else
                        {
                            $bank_date ="0000-00-00";

                        }
						$countPDC = PdcLine::where('cv_no',$cv_no)
									->where('cv_date',$cv_date)
									->where('check_date',$check_date)
									->where('check_no',$ck_no)
									->where('check_amount',$ck_amount)
									->where('baccount_no',$bank_no)
									->where('bu_unit',$this->bu)
									->where('company',$this->com)
									->where('company_code',$this->com)
									->count('id');
							if($countPDC <=0)
							{								
								PdcLine::updateOrCreate([
									'cv_no'=>$cv_no,
									'cv_status'=>$cv_status,
									'cv_date'=>$cv_date,
									'check_no'=>$ck_no,
									'check_amount'=>$ck_amount,
									'baccount_no'=>$bank_no,
									'check_date'=>$check_date,
									'payee'=>$payee,
									'bu_unit'=>$this->bu,
									'company'=>$this->com,
									'company_code'=>$this->com
							
									]);
							}

                    echo "<script>   
                        $('#mid-radar-process').css({
                            'transform': 'rotate(' + $index + 'deg)',
                            '-moz-transform': 'rotate(' + $index+ 'deg)',
                            '-o-transform': 'rotate(' + $index + 'deg)',
                            '-webkit-transform': 'rotate(' + $index + 'deg)'                  
                        });
                    </script>";

                        $index++;
                    }
                });
            }
        });
        echo '<script>
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
        $pdc_line = PdcLine::select('id','cv_date','check_date','check_amount',DB::raw('CAST(check_no as UNSIGNED) as check_no'))
            ->where('label_match','!=','match check')
            ->where('cv_status','Posted')
            ->where('baccount_no',$this->bank_no)
            ->where('bu_unit',$this->bu)
            ->where('company_code',$this->com)
            //->whereYear('cv_date',$request->year)
            ->orderBy('check_no','ASC')
            ->get();

    //    dd($pdc_line->all());
        foreach ($pdc_line as $row)
        {
            $bookcheckno[] = $row->id."|".$row->check_no."|".$row->cv_date."|".$row->check_date."|".$row->check_amount;
        }

        $bankcheckno = Array();
        $bank_dis = BankStatement::select('bank_id','bank_date','bank_amount',DB::raw('CAST(bank_check_no as UNSIGNED) as bank_check_no'))
            ->where('label_match','!=','match check')
            ->where('bank_account_no',$this->bank_no)
            ->where('company',$this->com)
            ->where('bu_unit',$this->bu)
            ->orderBy('bank_check_no','ASC')
            ->get();

        foreach ($bank_dis as $row1)
        {
            $bankcheckno[] = $row1->bank_id."|".$row1->bank_check_no."|".$row1->bank_date."|".$row1->bank_amount;
        }

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
        $message = "Check Voucher Data Uploaded Successfully";
        echo view('layouts.doneUploading',compact('title','message'));
    }


/*-------------------------------------------------------------------------------------------------------------------------
 * Show Modal Message if file Uploaded has an Error
 *-------------------------------------------------------------------------------------------------------------------------
 */
    public function showMessageError($message,$title,$pagelocationafterclose='',$jscriptfunctionafterclose='')
    {
        echo view('layouts.bootstrapDialogScript');
        echo"<script>
            BootstrapDialog.show({
                title: '".$title."',
                type: BootstrapDialog.TYPE_DANGER,";
                if(strlen($message)<100)
                {
                    echo "size: BootstrapDialog.SIZE_WIDE,";
                }
                else
                {
                    echo "size: BootstrapDialog.SIZE_WIDE,";
                }
                echo "
                message: \"".$message."\",
                draggable: true,
                closable: false,
                buttons: [
                    {id: 'btn-1',
                    label: 'Okay',
                    icon: 'glyphicon glyphicon-ok',
                    cssClass: 'border-button btn-sm btn-success',
                    action: function(dialogRef)
                    {
                        dialogRef.close();
                        //window.location = 'functions/removedir.php';
                        ";
                    echo $jscriptfunctionafterclose;
                    if(strlen($pagelocationafterclose)>0)
                        {
                             echo "
                            setTimeout(function(){
                                window.location='".$pagelocationafterclose."';
                            },300);
                            ";
                        }
                    echo "
                    }
                }],
            });
    
        </script>";
    }
}
