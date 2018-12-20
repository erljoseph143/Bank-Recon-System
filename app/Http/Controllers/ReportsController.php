<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\BankStatement;
use App\Businessunit;
use App\Company;
use App\PdcLine;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use PHPExcel_Shared_Date;
use PHPExcel_Style_NumberFormat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ReportsController extends Controller
{
	public $com;
	public $bu;
	public $bankno;
	public $cvNextMonth;
	public $bsPrevMonth;
	public $progress;
	
    public function __construct()
    {
        $this->middleware('auth');
        $this->cvNextMonth = Array();
	    $this->bsPrevMonth = Array();
		$this->progress = 0;
    }

    public function ExcelFile()
    {

        Excel::create('Report2016', function($excel) {

            // Set the title
            $excel->setTitle('My awesome report 2016');

            // Chain the setters
            $excel->setCreator('Me')->setCompany('Our Code World');

            $excel->setDescription('A demonstration to change the file properties');

//            $data[] = Array(12,"Hey",123,4234,5632435,"Nope",345,345,345,345);
//            $data[] = Array(12,"Hey",123,4234,5632435,"Nope",345,345,345,345);
            $bs = BankStatement::where('company',7)
                ->where('bu_unit',10)
                ->where('bank_account_no','B-014')
                ->whereMonth('bank_date','04')->get();

            $data = Array();
            foreach ($bs as $b)
            {
                $data[] = [$b->bank_date,$b->bank_check_no];
            }

            $excel->sheet('Sheet 1', function ($sheet) use ($data) {
                $headings = array('Bank Date', 'Bank Check No');

// Set border for range

                // $sheet->setBorder('A1', 'thin');
                $count = count($data) + 1;
                $sheet->
                $sheet->prependRow(2, $headings);
                $sheet->setBorder('A1:F'.$count, 'thin');
                $sheet->setOrientation('landscape');
                $sheet->fromArray($data, NULL, 'A3',false,false);

            });

        })->download('xlsx');
    }

    public function loadDis($bDate,$bankno,$com,$bu,$bankname,$accountno)
    {
    	ob_implicit_flush(true);
		ob_end_flush();
		
		$time_start = microtime(true);
		$user_ID   = Auth::user()->user_id;
		$path = storage_path("exports/reports/$user_ID");
		File::makeDirectory($path, 0777, true,true);
		
		echo response()->json(['message'=> "Match Check Number and Amount"]);		
			$datetitle = date('F, Y',strtotime($bDate));
			$type     = "match check and amount";
			$data     = $this->BSdata($type,$bDate,$bankno,$com,$bu);
	    
		echo response()->json(['message'=> "CV reflected in next month"]);
			$cvNext   = $this->cvNextMonth($com,$bu,$bankno);
		
		echo response()->json(['message'=> "BS reflected in previous Month"]);
			$bsPrev   = $this->bsPrevMonth($com,$bu,$bankno);
	    
		echo response()->json(['message'=> "Match Check Number Only"]);
			$type     = "match check no only";
			$data1    = $this->BSdata($type,$bDate,$bankno,$com,$bu);

		echo response()->json(['message'=> "Match Check But Duplicate Entry"]);
			$data3    =  $this->dupCheckEntry($bDate,$bankno,$com,$bu);
		
		echo response()->json(['message'=> "Unmatch With Check Num."]);		
			$type     = "unmatch with checkno";
		$unmatch1 = $this->unMatch($type,$bDate,$bankno,$com,$bu);
		
		echo response()->json(['message'=> "Unmatch Without Check Num."]);
			$type     = "unmatch without checkno";
			$unmatch2 = $this->unMatch($type,$bDate,$bankno,$com,$bu);


        $com_name = Company::find($com)->company;
        $bu_name  = Businessunit::find($bu)->bname;
        
        echo response()->json(['message'=> "Compiling to Excel"]);
// $dataExcel = 
        Excel::create("reports/$user_ID/DISBURSEMENT - $datetitle - $bankname - $accountno", function($excel) use($data,$cvNext,$bsPrev,$data1,$data3,$com_name,$bu_name,$datetitle,$bankname,$accountno,$unmatch1,$unmatch2) {

            // Set the title
            $excel->setTitle('DISBURSEMENT SUMMARY REPORTS');

            // Chain the setters
            $excel->setCreator('BRS PROGRAMMER REY JOSEPH T. BAAY, JELLARY CADUTDUT,GLENN MICHAEL MEJEIAS')->setCompany('Coporate AGC');

            $excel->setDescription('Summary Reports of disbursement of book and bank');


/*
 |----------------------------------------------------------------------------------------------------------------------------
 |  Match Check No and Amount
 |----------------------------------------------------------------------------------------------------------------------------
*/

            $excel->sheet('Match Check Num. and Amount', function ($sheet) use ($data,$com_name,$bu_name,$datetitle,$bankname,$accountno) {

                $sheet->setOrientation('landscape');

                $count = count($data) + 5;

                $sheet->setBorder('A4:D'.$count, 'thin');
                $sheet->setBorder('F4:K'.$count, 'thin');



                $headings = array('Trans Description', 'Check No.','Bank Statement Date','Bank Amount','','CV No','Check No','Posting Date','Check Date','Check Amount','CV Status');

                $sheet->prependRow(5, $headings);
                $sheet->fromArray($data, NULL, 'A6',false,false);
                $sheet->mergecells('A4:K4');
                $sheet->mergecells('A1:K1');
                $sheet->mergecells('A2:B2');

                for($s=4;$s<=$count;$s++)
                {
//                    $sheet->setColumnFormat(array(
//                        'C'.$s => 'mm/dd/yyyy',
//                        'D'.$s => '0.00',
//                        'H'.$s => 'mm/dd/yyyy',
//                        'I'.$s => 'mm/dd/yyyy',
//                        'G'.$s => '0.00'
//                    ));
	                $sheet->getStyleByColumnAndRow(2, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
	                $sheet->getStyleByColumnAndRow(7, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
	                $sheet->getStyleByColumnAndRow(8, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
	
	                $sheet->getStyle('D'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                    $sheet->getStyle('J'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                }

                $sheet->row(4,array('DISBURSEMENT WITH MATCH CHECK NO AND AMOUNT'));
                $sheet->row(1,array("$com_name - $bu_name : $bankname - $accountno"));
                $sheet->row(2,array("$datetitle"));
                $sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
            });
/*
 |----------------------------------------------------------------------------------------------------------------------------
 |  Match Check No and Amount CV Reflected in Other Month in Bank Statement
 |----------------------------------------------------------------------------------------------------------------------------
*/
            $excel->sheet('CV reflected in next Month', function ($sheet) use ($cvNext,$com_name,$bu_name,$datetitle,$bankname,$accountno) {

                $sheet->setOrientation('landscape');

                $count = count($cvNext) + 5;

                $sheet->setBorder('A4:D'.$count, 'thin');
                $sheet->setBorder('F4:L'.$count, 'thin');



                $headings = array('Trans Description', 'Check No.','Bank Statement Date','Bank Amount','','CV No','Check No','Posting Date','Check Date','Check Amount','CV Status','Check Status');

                $sheet->prependRow(5, $headings);
                $sheet->fromArray($cvNext, NULL, 'A6',false,false);
                $sheet->mergecells('A4:L4');
                $sheet->mergecells('A1:L1');
                $sheet->mergecells('A2:B2');

                for($s=4;$s<=$count;$s++)
                {
                    $sheet->setColumnFormat(array(
                        'C'.$s => 'mm/dd/yyyy',
                        'D'.$s => '0.00',
                        'H'.$s => 'mm/dd/yyyy',
                        'I'.$s => 'mm/dd/yyyy',
                        'G'.$s => '0.00'
                    ));
	                $sheet->getStyleByColumnAndRow(2, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
	                $sheet->getStyleByColumnAndRow(7, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
	                $sheet->getStyleByColumnAndRow(8, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);

                    $sheet->getStyle('D'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                    $sheet->getStyle('J'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                }

                $sheet->row(4,array('DISBURSEMENT WITH MATCH CHECK NO AND AMOUNT - CV REFLECTED IN NEXT MONTH'));
                $sheet->row(1,array("$com_name - $bu_name : $bankname - $accountno"));
                $sheet->row(2,array("$datetitle"));
                $sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
            });
/*
 |----------------------------------------------------------------------------------------------------------------------------
 |  Match Check No and Amount BS Reflected in Other Month in Book Record (Pdc_line Table)
 |----------------------------------------------------------------------------------------------------------------------------
*/

            $excel->sheet('BS reflected in prev Month', function ($sheet) use ($bsPrev,$com_name,$bu_name,$datetitle,$bankname,$accountno) {

                $sheet->setOrientation('landscape');

                $count = count($bsPrev) + 5;

                $sheet->setBorder('A4:D'.$count, 'thin');
                $sheet->setBorder('F4:K'.$count, 'thin');



                $headings = array('Trans Description', 'Check No.','Bank Statement Date','Bank Amount','','CV No','Check No','Posting Date','Check Date','Check Amount','CV Status');

                $sheet->prependRow(5, $headings);
                $sheet->fromArray($bsPrev, NULL, 'A6',false,false);
                $sheet->mergecells('A4:K4');
                $sheet->mergecells('A1:K1');
                $sheet->mergecells('A2:B2');

                for($s=4;$s<=$count;$s++)
                {
                    $sheet->setColumnFormat(array(
                        'C'.$s => 'mm/dd/yyyy',
                        'D'.$s => '0.00',
                        'H'.$s => 'mm/dd/yyyy',
                        'I'.$s => 'mm/dd/yyyy',
                        'G'.$s => '0.00'
                    ));
	                $sheet->getStyleByColumnAndRow(2, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
	                $sheet->getStyleByColumnAndRow(7, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
	                $sheet->getStyleByColumnAndRow(8, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);

                    $sheet->getStyle('D'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                    $sheet->getStyle('J'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                }

                $sheet->row(4,array('DISBURSEMENT WITH MATCH CHECK NO AND AMOUNT - BANK CHECK NO REFLECTED IN PREVIOUS MONTH'));
                $sheet->row(1,array("$com_name - $bu_name : $bankname - $accountno"));
                $sheet->row(2,array("$datetitle"));
                $sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
            });
/*
 |----------------------------------------------------------------------------------------------------------------------------
 |  Match Check No Only
 |----------------------------------------------------------------------------------------------------------------------------
*/
            $excel->sheet('Match Check Num. Only', function ($sheet) use ($data1,$com_name,$bu_name,$datetitle,$bankname,$accountno) {
                $data    = Array();
                $data2   = Array();
                $countds = 0;
                $sheet->setOrientation('landscape');
                $count = count($data1) + 5;

                $sheet->setBorder('A4:D'.$count, 'thin');
                $sheet->setBorder('F4:K'.$count, 'thin');



                $headings = array('Trans Description', 'Check No.','Bank Statement Date','Bank Amount','','CV No','Check No','Posting Date','Check Date','Check Amount','CV Status');

                $sheet->prependRow(5, $headings);

                $sheet->fromArray($data1, NULL, 'A6',false,false);
                $sheet->mergecells('A4:K4');
                $sheet->mergecells('A1:K1');
                $sheet->mergecells('A2:B2');

                for($s=4;$s<=$count;$s++)
                {
                    $sheet->setColumnFormat(array(
                        'C'.$s => 'mm/dd/yyyy',
                        'D'.$s => '0.00',
                        'H'.$s => 'mm/dd/yyyy',
                        'I'.$s => 'mm/dd/yyyy',
                        'G'.$s => '0.00'
                    ));
	                $sheet->getStyleByColumnAndRow(2, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
	                $sheet->getStyleByColumnAndRow(7, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
	                $sheet->getStyleByColumnAndRow(8, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);

                    $sheet->getStyle('D'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                    $sheet->getStyle('J'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                }

                $sheet->row(4,array('DISBURSEMENT WITH MATCH CHECK NO ONLY'));
                $sheet->row(1,array("$com_name - $bu_name : $bankname - $accountno"));
                $sheet->row(2,array("$datetitle"));
                $sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
            });

/*
 |----------------------------------------------------------------------------------------------------------------------------
 |  Match Check But Duplicate in Entry
 |----------------------------------------------------------------------------------------------------------------------------
*/
            //$data3 =  $this->dupCheckEntry($bDate,$bankno,$com,$bu);
            $excel->sheet('Match Check But Duplicate Entry', function ($sheet) use ($data3,$com_name,$bu_name,$datetitle,$bankname,$accountno) {

//                $datanew = Array();
//                foreach($data3 as $d)
//                {
//                    $exp = explode("|",$d);
//                    $datanew [] = [$exp[0],$exp[1],$exp[2],$exp[3],$exp[4],$exp[5],$exp[6],$exp[7],$exp[8],$exp[9],$exp[10]];
//                }
                $sheet->setOrientation('landscape');
                $count = count($data3) + 5;

                $sheet->setBorder('A4:K'.$count, 'thin');
                //$sheet->setBorder('F4:J'.$count, 'thin');



                $headings = array('Trans Description', 'Check No.','Bank Statement Date','Bank Amount','Total in Bank','CV No','Check No','Posting Date','Check Date','Check Amount','Total in Book');

                $sheet->prependRow(5, $headings);

                $sheet->fromArray($data3, NULL, 'A6',false,false);
                $sheet->mergecells('A4:K4');
                $sheet->mergecells('A1:K1');
                $sheet->mergecells('A2:B2');

                for($s=5;$s<=$count;$s++)
                {
                    $sheet->setColumnFormat(array(
                        'C'.$s => 'mm/dd/yyyy',
                        'D'.$s => '0.00',
                        'H'.$s => 'mm/dd/yyyy',
                        'I'.$s => 'mm/dd/yyyy',
                        'G'.$s => '0.00'
                    ));
	                $sheet->getStyleByColumnAndRow(2, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
	                $sheet->getStyleByColumnAndRow(7, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
	                $sheet->getStyleByColumnAndRow(8, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);

                    
                    $sheet->getStyle('D'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                    $sheet->getStyle('E'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                    $sheet->getStyle('J'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                    $sheet->getStyle('K'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                }
                $sheet->row(4,array('DISBURSEMENT WITH MATCH CHECK BUT DUPLICATE IN ENTRY'));
                $sheet->row(1,array("$com_name - $bu_name : $bankname - $accountno"));
                $sheet->row(2,array("$datetitle"));
                $sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
            });


/*
 |----------------------------------------------------------------------------------------------------------------------------
 |  Unmatch with Check No
 |----------------------------------------------------------------------------------------------------------------------------
*/
            $excel->sheet('Unmatch With Check Num.', function ($sheet) use ($unmatch1,$com_name,$bu_name,$datetitle,$bankname,$accountno) {
                $data    = Array();
                $data2   = Array();
                $countds = 0;
                $sheet->setOrientation('landscape');
                $count = count($unmatch1) + 5;

                $sheet->setBorder('A4:D'.$count, 'thin');
                $sheet->setBorder('F4:M'.$count, 'thin');



                $headings = array('Trans Description', 'Check No.','Bank Statement Date','Bank Amount','','CV No','Check No','Posting Date','Check Date','Check Amount','CV Status','Status','Payee');

                $sheet->prependRow(5, $headings);

                $sheet->fromArray($unmatch1, NULL, 'A6',false,false);
                $sheet->mergecells('A4:M4');
                $sheet->mergecells('A1:M1');
                $sheet->mergecells('A2:B2');

                for($s=4;$s<=$count;$s++)
                {
                    $sheet->setColumnFormat(array(
                        'C'.$s => 'mm/dd/yyyy',
                        'D'.$s => '0.00',
                        'H'.$s => 'mm/dd/yyyy',
                        'I'.$s => 'mm/dd/yyyy',
                        'G'.$s => '0.00'
                    ));
	                $sheet->getStyleByColumnAndRow(2, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
	                $sheet->getStyleByColumnAndRow(7, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
	                $sheet->getStyleByColumnAndRow(8, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);

                    $sheet->getStyle('D'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                    $sheet->getStyle('J'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                }

                $sheet->row(4,array('DISBURSEMENT WITH UNMATCH CHECK NO'));
                $sheet->row(1,array("$com_name - $bu_name : $bankname - $accountno"));
                $sheet->row(2,array("$datetitle"));
                $sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
            });
/*
 |----------------------------------------------------------------------------------------------------------------------------
 |  Unmatch withouth Check No
 |----------------------------------------------------------------------------------------------------------------------------
*/
			
            $excel->sheet('Unmatch Without Check Num.', function ($sheet) use ($unmatch2,$com_name,$bu_name,$datetitle,$bankname,$accountno) {
                $data    = Array();
                $data2   = Array();
                $countds = 0;
                $sheet->setOrientation('landscape');
                $count = count($unmatch2) + 5;

                $sheet->setBorder('A4:D'.$count, 'thin');
                $sheet->setBorder('F4:M'.$count, 'thin');



                $headings = array('Trans Description', 'Check No.','Bank Statement Date','Bank Amount','','CV No','Check No','Posting Date','Check Date','Check Amount','CV Status','Status','Payee');

                $sheet->prependRow(5, $headings);

                $sheet->fromArray($unmatch2, NULL, 'A6',false,false);
                $sheet->mergecells('A4:M4');
                $sheet->mergecells('A1:M1');
                $sheet->mergecells('A2:B2');

                for($s=4;$s<=$count;$s++)
                {
                    $sheet->setColumnFormat(array(
                        'C'.$s => 'mm/dd/yyyy',
                        'D'.$s => '0.00',
                        'H'.$s => 'mm/dd/yyyy',
                        'I'.$s => 'mm/dd/yyyy',
                        'G'.$s => '0.00'
                    ));
	                $sheet->getStyleByColumnAndRow(2, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
	                $sheet->getStyleByColumnAndRow(7, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
	                $sheet->getStyleByColumnAndRow(8, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);

                    $sheet->getStyle('D'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                    $sheet->getStyle('J'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                }

                $sheet->row(4,array('DISBURSEMENT UNMATCH WITHOUT CHECK NO'));
                $sheet->row(1,array("$com_name - $bu_name : $bankname - $accountno"));
                $sheet->row(2,array("$datetitle"));
                $sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
            });

/*------------------------------------------------------------------------------------------------------------------------------------------------
 *  END OF SHEETS REPORTS
 *------------------------------------------------------------------------------------------------------------------------------------------------
 */
			
            $excel->setActiveSheetIndex(0);
			
        })->save('xlsx');
		
		//$dataExcel;
		
			// Display Script End time
			$time_end = microtime(true);

			//dividing with 60 will give the execution time in minutes other wise seconds
			//$execution_time = ($time_end - $time_start)/60;
			$execution_time = date("i:s",$time_end - $time_start);
			
			$timeExp = explode(':',$execution_time);
			$min = $timeExp[0];
			$sec = $timeExp[1];
			//execution time of the script
			echo response()->json(['time_elapse'=> '<b>Your Report successfully generated </br> Thank You for waiting </br>Total Execution Time:</b> '.$min.' Mins'.' '.$sec.' secs']);
		
			
		$path = url("../storage/exports/reports/$user_ID/DISBURSEMENT - $datetitle - $bankname - $accountno.xlsx");
		echo response()->json(['url'=>$path]);
		
		
    }


/*-----------------------------------------------------------------
|  Manipulating Data from database
| ----------------------------------------------------------------
*/
    function BSdata($type,$bDate,$bankno,$com,$bu)
    {

        $month  = date('m',strtotime($bDate));
        $year   = date('Y',strtotime($bDate));

        $array  = Array();
        $BSar   = Array();
        $BKar   = Array();
        $value1 = Array();

        $bankRecord = Array();
        $bookRecord = Array();

		$bankCheckArray = Array();
		$bookCheckArray = Array();
$bsdata = BankStatement::select('bank_date', 'bank_amount', 'description', DB::raw('CAST(bank_check_no AS UNSIGNED) as bank_check_no'))
            ->whereMonth('bank_date', $month)
            ->whereYear('bank_date', $year)
            ->where('company', $com)
            ->where('type', 'AP')
            ->where('bu_unit', $bu)
            ->where('bank_account_no',$bankno)
            ->where('label_match','match check')
            ->orderBy('bank_check_no','ASC')
            ->get();

        foreach($bsdata as $bs_1)
        {
            $bankRecord[] = $bs_1->bank_date."|".$bs_1->bank_check_no."|".$bs_1->description."|".$bs_1->bank_amount;
			$bankCheckArray[] = $bs_1->bank_check_no;
        }
$bkdata = PdcLine::select('cv_date','check_date', 'check_amount', 'cv_no', DB::raw('CAST(check_no AS UNSIGNED) as check_no'),'cv_status','status')
            ->whereMonth('cv_date', $month)
            ->whereYear('cv_date', $year)
            ->where('company', $com)
            ->where('bu_unit', $bu)
            ->where('baccount_no',$bankno)
            ->where('label_match','match check')
            ->orderBy('check_no','ASC')
            ->get();

         foreach($bkdata as $bk_1)
         {
			$yearCV       = date("Y",strtotime($bk_1->cv_date));
			$monthCV      = date("n",strtotime($bk_1->cv_date));
			$yearCheck    = date("Y",strtotime($bk_1->check_date));
			$monthCheck   = date("n",strtotime($bk_1->check_date));
			$status       = "";
			if(($monthCV < $monthCheck and $yearCV == $yearCheck) or ($monthCV > $monthCheck and $yearCV < $year)):
				$status   = "PDC";
			else:
				$status   = $bk_1->status;
			endif;
            $bookRecord[] = $bk_1->cv_no."|".$bk_1->cv_date."|".$bk_1->check_date."|".$bk_1->check_no."|".$bk_1->check_amount."|".$bk_1->cv_status."|".$status;
			$bookCheckArray[] = $bk_1->check_no;
         }
		 
		 $countBookCheck = count($bookCheckArray);

         if(count($bsdata)>0)
         {
			$countRow = count($bankRecord); 
			//dd($bankRecord);
			$x = 1;
	         $totalBS = 0;
	         $totalBK = 0;
            foreach($bankRecord as $key => $bsRec)
            {
                $exp     = explode("|",$bsRec);
                $bsDate  = $exp[0];
                $bsCheck = $exp[1];
                $bsDes   = $exp[2];
                $bsAmt   = $exp[3];
                $tagCk   = $bsCheck;
                $tagamt  = $bsAmt;
                $tagdate = $bsDate;
                $tagdes  = $bsDes;
				
				$indexProg = (($x)/$countRow)*14.29;
				
				if($x > 1):
					$prevProg = (($x-1)/$countRow)*14.29;
				endif;
				
					if($type == "match check and amount"):
						$this->progress = $indexProg;
						echo response()->json(['progress1'=>$indexProg,'percent'=>$this->progress]);
					elseif($type == "match check no only"):
						echo response()->json(['progress4'=>$indexProg]);
						if($x > 1)
						{
							$min = $indexProg - $prevProg;
							$this->progress += $min;
							echo response()->json(['percent'=>$this->progress]);
						}
						
					endif;	
				
				$indexCheck = $this->binarysearch($bookCheckArray,$bsCheck);
				echo "$indexCheck </br>";
				if($indexCheck!=-1 and count($bookCheckArray)!=0 and $bsCheck!=0)
				{
					
					$minIndex = $this->realindex($bookCheckArray,$bsCheck,$indexCheck);
					$maxIndex = $this->maxindex($bookCheckArray,$bsCheck,$indexCheck);
					
					//echo $indexCheck ."</br>";
					// foreach($bookRecord as $key2 => $bkRec)
					//echo "$minIndex => $maxIndex $bookRecord[$minIndex] </br>";

					for($key2=$minIndex;$key2<=$maxIndex;$key2++)
					 {
//						 echo "This key => ". array_key_exists($key2,$bookRecord) ."</br>";
						 if(array_key_exists($key2,$bookRecord))
						 {
							 
							$bkRec   = $bookRecord[$key2];
							$exp2    = explode("|",$bkRec);
							$cvNo    = $exp2[0];
							$cvDate  = $exp2[1];
							$ckDate  = $exp2[2];
							$ckNo    = $exp2[3];
							$ckAmt   = $exp2[4];
							$cvStatus= $exp2[5];
							$status  = $exp2[6];
							 $PHPDateValue = strtotime($tagdate);
							 $bsDate = PHPExcel_Shared_Date::PHPToExcel($PHPDateValue);
							 $cvDate1 = strtotime($cvDate);
							 $cvDateNew = PHPExcel_Shared_Date::PHPToExcel($cvDate1);
							 $ckDate1 = strtotime($ckDate);
							 $ckDateNew = PHPExcel_Shared_Date::PHPToExcel($ckDate1);
								if($type == "match check and amount")
								   {					
									if(trim($bsCheck) == trim($ckNo) and trim($bsAmt) == trim($ckAmt))
										{
											
											// $pdcCount = PdcLine::where('check_no',$bsCheck)
											// ->where('check_amount',$bsAmt)
											// ->where('company',$com)
											// ->where('bu_unit',$bu)
											// ->count('id');
											// if($pdcCount == 1):

												unset($bankRecord[$key]);
												unset($bookRecord[$key2]);

												$array[] = [
												$tagdes,
												$tagCk,
												$bsDate,
												number_format($tagamt,2),
												'     ',
												$cvNo,
												$ckNo,
												$cvDateNew,
												$ckDateNew,
												number_format($ckAmt,2),
												$cvStatus
												];
											//endif;
											$totalBS +=$tagamt;
											$totalBK +=$ckAmt;
										}
								   }
								else if($type == "match check no only")
								   {
									if(trim($bsCheck) == trim($ckNo) and trim($bsAmt) != trim($ckAmt))
										{							   
											 $pdcCount = PdcLine::where('check_no',$bsCheck)
											 ->where('check_amount','!=',$bsAmt)
											 ->where('company',$com)
											 ->where('bu_unit',$bu)
											 ->count('id');
											 if($pdcCount == 1):

												// unset($bankRecord[$key]);
												// unset($bookRecord[$key2]);

												$array[] = [
												$tagdes,
												$tagCk,
												$bsDate,
												number_format($tagamt,2),
												'     ',
												$cvNo,
												$ckNo,
												$cvDateNew,
												$ckDateNew,
												number_format($ckAmt,2),
												$cvStatus
												];
												 $totalBS +=$tagamt;
												 $totalBK +=$ckAmt;
											 endif;
										}								
								   }
						 }
						   
					 }
				}	
				$x++;
            }
	         $array[] = [
		         '',
		         '',
		         'Total: ',
		         number_format($totalBS,2),
		         '     ',
		         '',
		         '',
		         '',
		         'Total: ',
		         number_format($totalBK,2),
		         ''
	         ];
	         if($type == "match check and amount"):
		         // dd($this->cvNextMonth);
		         $this->cvNextMonth = $bookRecord;
                 $this->bsPrevMonth = $bankRecord;
	         endif;
			 
			 //echo response()->json(['progress1'=>14.29,'percent'=>14.29]);
          
         }

        // dd($array);
        if($type =="match check and amount" or $type =="match check no only")
        {
            return $array;
        }
    }

    function  dupCheckEntry($bDate,$bankno,$com,$bu)
    {
		
		
	    $month      = date('m',strtotime($bDate));
	    $year       = date('Y',strtotime($bDate));
	    $bankArray  = Array();
	    $dup        = "";
	    $bookarray  = Array();
	    $bookamtsum = 0;
	    $allentry   = Array();
	
	    $bookarray1 = Array();
	    $bankarray1 = Array();
	
	    $arrayChecknoBK = Array();
	    $arrayChecknoBS = Array();
	    $x   			= Array();
	    $data           = Array();
		
		$bankCheckArray = Array();
		$bookCheckArray = Array();		

	    $bs = BankStatement::select('bank_date',DB::raw('CAST(bank_check_no as UNSIGNED) as bank_check_no'),'bank_amount','description')
		    ->whereMonth('bank_date', $month)
		    ->whereYear('bank_date', $year)
		    ->where('company', $com)
		    ->where('type', 'AP')
		    ->where('bu_unit', $bu)
		    ->where('bank_account_no',$bankno)
		    ->where('label_match','match check')
		    ->orderBy('bank_check_no','ASC');
	
	    foreach($bs->get() as $key => $bsdata)
	    {
		    $bankArray[] = $bsdata->bank_date."|".$bsdata->bank_check_no."|".$bsdata->description."|".$bsdata->bank_amount."| ";
		    $arrayChecknoBS[] = $bsdata->bank_check_no;
			$bankCheckArray[] = $bsdata->bank_check_no;
	    }
	
	    $bk = PdcLine::select('cv_no',DB::raw('CAST(check_no as UNSIGNED) as check_no'),'cv_date','check_date','check_amount')
		    ->where('baccount_no',$bankno)
		    ->where('company',$com)
		    ->where('bu_unit',$bu)
		    ->where('label_match','match check')
		    ->orderBy('check_no','ASC');
	
	    foreach($bk->get() as $key2 => $bkData)
	    {
		    $bookarray[] = $bkData->cv_no."|".$bkData->check_no."|".$bkData->cv_date."|".$bkData->check_date."|".$bkData->check_amount;
		    $arrayChecknoBK[] = $bkData->check_no;
			$bookCheckArray[] = $bkData->check_no;
	    }
		
		$countBookCheck = count($bookCheckArray);
		
		$countRow = count($bankArray);
			$xnum = 1;

	    foreach($bankArray as $key => $dataBS)
	    {
		    $exp = explode("|",$dataBS);
		    $bsDate = $exp[0];
		    $bsCK	= $exp[1];
		    $bsDes  = $exp[2];
		    $bsAmt  = $exp[3];
		    $bsTotal= 0;
		
		    $tagamt      = number_format($bsAmt,2);
		    $tagdate     = date("m/d/Y",strtotime($bsDate));
		    $tagdes      = $bsDes;
		    $tagcheck    = $bsCK;
		    $tagtotal    = $bsTotal;
			

		
		   // foreach($bookarray as $key2 => $dataBK)
				$indexCheck = $this->binarysearch($bookCheckArray,$bsCK);
				
				if($indexCheck!=-1 and count($bookCheckArray)!=0 and $bsCK!=0)
				{
					
						$minIndex = $this->realindex($bookCheckArray,$bsCK,$indexCheck);
						$maxIndex = $this->maxindex($bookCheckArray,$bsCK,$indexCheck);
						if($minIndex=="")
						{
							echo $indexCheck ." => ". $minIndex." => ". $maxIndex . "</br>";
							//dd([$bookCheckArray,$bsCK]);
						}
						
					for($key2=$minIndex;$key2<=$maxIndex;$key2++)
					{
						$dataBK = $bookarray[$key2];
						$exp1 = explode("|",$dataBK);
						$cvNo   = $exp1[0];
						$ckNo   = $exp1[1];
						$cvDate = $exp1[2];
						$ckDate = $exp1[3];
						$bkAmt  = $exp1[4];
					
						if(trim($bsCK)==trim($ckNo) and trim($bsCK)!=0 and trim($ckNo)!=0):
							$occurences = array_count_values($x);
							if(in_array($bsCK, $x))
							{
								$var = $occurences[$bsCK];
							}
							else
							{
								$var = 0;
							}
						
							if($var<=0)
							{
								$x[] = $bsCK;
							
							}
							else
							{
								$tagamt    = "";
								$tagdate   = "";
								$tagdes    = "";
								$tagcheck  = "";
							}
							$countCheck = array_count_values($arrayChecknoBK);
							
							if($countCheck[$ckNo]>1):
								$data[] = [$tagdes,$tagcheck,$tagdate,$tagamt,$cvNo,$ckNo,$cvDate,$ckDate,$bkAmt];
							endif;
					
						endif;
					}
				}
			
						
			$indexProg   = (($xnum)/$countRow)*8.14;
			
				if($xnum > 1)
					{
						$prevProg = (($xnum-1)/$countRow)*8.14;
						$min   = $indexProg - $prevProg;
						$this->progress += $min;
					}
			echo response()->json(['progress5'=>$indexProg,'percent'=>$this->progress]);
			
			$xnum++;
	    }

	    $dataSet = Array();
	    $totalAmt = 0;
		$countRow = count($data);
		$xnum =1;
	    foreach($data  as $key2 => $d):
		    $newKey   = $key2+1;
	        $checkNum = "";
	        
						$indexProg   = (($xnum)/$countRow)*8.14;
			
				if($xnum > 1)
					{
						$prevProg = (($xnum-1)/$countRow)*8.14;
						$min   = $indexProg - $prevProg;
						$this->progress += $min;
					}
			echo response()->json(['progress5'=>$indexProg,'percent'=>$this->progress]);
			
			
	        if(trim($d[2])==""):
		       $bsDate = "";
		    else:
			    $bsDate1 = strtotime($d[2]);
		        $bsDate  = PHPExcel_Shared_Date::PHPToExcel($bsDate1);
	        endif;
	        
	        if(trim($d[6])==""):
		       $cvDate = "";
		    else:
			    $cvDate1 = strtotime($d[6]);
		        $cvDate  = PHPExcel_Shared_Date::PHPToExcel($cvDate1);
	        endif;
	        
	        if(trim($d[7])==""):
		       $ckDate = "";
		    else:
			    $ckDate1 = strtotime($d[7]);
		        $ckDate  = PHPExcel_Shared_Date::PHPToExcel($ckDate1);
	        endif;
	        
	        
		    $dataSet[] = [$d[0],$d[1],$bsDate,$d[3],'',$d[4],$d[5],$cvDate,$ckDate,number_format($d[8],2),''];
	        if($newKey<=count($data)-1)
	        {
		        $checkNum = $data[$newKey][2];
		        $totalAmt +=$d[8];
	        	if($checkNum !=""):
			        $dataSet[] =['','Total Amount','','','','','','','','',number_format($totalAmt,2)];
	        	    $totalAmt = 0;
			    endif;
	        }
	        elseif(max(array_keys($data))==$key2)
	        {
	        	$totalAmt +=$d[8];
		        $dataSet[] =['','Total Amount','','','','','','','','',number_format($totalAmt,2)];
	        }
			$xnum++;
		endforeach;
	return $dataSet;

    }

    public function unMatch($type,$bDate,$bankno,$com,$bu)
    {
        $month = date('m',strtotime($bDate));
        $year  = date('Y',strtotime($bDate));

        $bank = BankStatement::select('bank_date', 'bank_amount', 'description', 'bank_check_no')
            ->whereMonth('bank_date', $month)
            ->whereYear('bank_date', $year)
            ->where('company', $com)
            ->where('type', 'AP')
            ->where('bu_unit', $bu)
            ->where('bank_account_no',$bankno);
        $book = PdcLine::select('cv_date', 'check_date', 'check_no', 'check_amount','cv_no','cv_status','status','payee')
            ->whereMonth('cv_date', $month)
            ->whereYear("cv_date",$year)
            ->where('company', $com)
            ->where('bu_unit', $bu)
            ->where('baccount_no',$bankno);
        $array   = Array();
        $bsArray = Array();
        $bkArray = Array();
	    $totalBS = 0;
	    $totalBK = 0;
        
        if($type == "unmatch with checkno")
        {
			$countRowBK = count($book->where('check_no','!=','')->where('label_match','')->get());
			$countRowBS = count($bank->where('bank_check_no','!=','')->where('label_match','')->get());
			if($countRowBK > $countRowBS)
			{
				$countRow = $countRowBK;
			}
			elseif($countRowBK == $countRowBS)
			{
				$countRow = $countRowBS;
			}
			else
			{
				$countRow = $countRowBS;
			}
			$xbs = 1;
            foreach($bank->where('bank_check_no','!=','')->where('label_match','')->get() as $bS)
            {
	            $bsArray[] = $bS->description."|".$bS->bank_check_no."|".date("m/d/Y",strtotime($bS->bank_date))."|".number_format($bS->bank_amount,2)."|".' ';
				$totalBS += $bS->bank_amount;
						if($countRowBK < $countRowBS)
					{
						$indexProg = ($xbs/$countRow)*14.30;
							if($xbs > 1)
							{
								$prevProg = (($xbs-1)/$countRow)*14.30;
								$min   = $indexProg - $prevProg;
								$this->progress += $min;
							}
						echo response()->json(['progress6'=>$indexProg,'percent'=>$this->progress]);
					}
					elseif($countRowBK == $countRowBS)
					{
						$indexProg = ($xbs/$countRow)*14.30;
							if($xbs > 1)
							{
								$prevProg = (($xbs-1)/$countRow)*14.30;
								$min   = $indexProg - $prevProg;
								$this->progress += $min;
							}
						echo response()->json(['progress6'=>$indexProg,'percent'=>$this->progress]);	
					}	
					
				$xbs++;	
            }
			$xbk =1;
            foreach($book->where('check_no','!=','')->where('label_match','')->get() as $bK)
            {
						$yearCV         = date("Y",strtotime($bK->cv_date));
						$monthCV       = date("n",strtotime($bK->cv_date));
						$yearCheck    = date("Y",strtotime($bK->check_date));
						$monthCheck  = date("n",strtotime($bK->check_date));
						$status            = "";
						$cvStatus        = "";
						if(($monthCV < $monthCheck and $yearCV == $yearCheck) or ($monthCV > $monthCheck and $yearCV < $year)):
							$status = "PDC";
							$cvStatus = $bK->cv_status;
						else:
							$status = $bK->status;
							$cvStatus = $bK->cv_status;
						endif;
	            $bkArray[]  = $bK->cv_no."|".$bK->check_no."|".date("m/d/Y",strtotime($bK->cv_date))."|".date("m/d/Y",strtotime($bK->check_date))."|".number_format($bK->check_amount,2)."|".$cvStatus."|".$status."|".$bK->payee;
				$totalBK+=$bK->check_amount;
	            if($countRowBK > $countRowBS)
					{
						$indexProg = ($xbk/$countRow)*14.30;
							if($xbk > 1)
							{
								$prevProg = (($xbk-1)/$countRow)*14.30;
								$min   = $indexProg - $prevProg;
								$this->progress += $min;
							}
						echo response()->json(['progress6'=>$indexProg,'percent'=>$this->progress]);
					}
			  $xbk++;
		   }
		   
		   		if($countRowBK==0 and $countRowBS ==0)
					{
						
						echo response()->json(['progress6'=>14.30,'percent'=>14.30]);
					}
	       //  dd($this->normalizeArray($bsArray,$bkArray));
	        
            
//	        array:12 [
//	        0 => "0431 DEBIT MEMO"
//    1 => "MAIN"
//    2 => 42754.0
//    3 => "492,565.67"
//    4 => " "
//    5 => "CV00089838"
//    6 => "1257805"
//    7 => 42749.0
//    8 => 42749.0
//    9 => "15,998.00"
//    10 => "Posted"
//    11 => "OC"
//  ] 
		   $data = $this->normalizeArray($bsArray,$bkArray);
	       $data[] =['','','Total: ',number_format($totalBS,2),'','','','','Total: ',number_format($totalBK,2),'','',''];
         // dd($data);
            return $data;
        }
        else
        {
			
			$countRowBK = count($book->where('check_no','')->where('label_match','')->get());
			$countRowBS = count($bank->where('bank_check_no','')->where('label_match','')->get());
			if($countRowBK > $countRowBS)
			{
				$countRow = $countRowBK;
			}
			elseif($countRowBK == $countRowBS)
			{
				$countRow = $countRowBS;
			}
			else
			{
				$countRow = $countRowBS;
			}
			$xbs = 1;
            foreach($bank->where('bank_check_no','')->where('label_match','')->get() as $bS)
            {
				
                $bsArray[] = $bS->description."|".$bS->bank_check_no."|".date("m/d/Y",strtotime($bS->bank_date))."|".number_format($bS->bank_amount,2)."|".' ';
				$totalBS+=$bS->bank_amount;
                if($countRowBK < $countRowBS)
					{
						
						$indexProg = ($xbs/$countRow)*14.30;
						if($xbs > 1)
							{
								$prevProg = (($xbs-1)/$countRow)*14.30;
								$min   = $indexProg - $prevProg;
								$this->progress += $min;
							}
						echo response()->json(['progress7'=>$indexProg,'percent'=>$this->progress]);
					}
				elseif($countRowBK == $countRowBS)
					{
						$indexProg = ($xbs/$countRow)*14.30;
							if($xbs > 1)
							{
								$prevProg = (($xbs-1)/$countRow)*14.30;
								$min   = $indexProg - $prevProg;
								$this->progress += $min;
							}
						echo response()->json(['progress7'=>$indexProg,'percent'=>$this->progress]);	
					}				
				$xbs++;	
            }
			$xbk =1;
            foreach($book->where('check_no','')->where('label_match','')->get() as $bK)
            {
						$yearCV         = date("Y",strtotime($bK->cv_date));
						$monthCV       = date("n",strtotime($bK->cv_date));
						$yearCheck    = date("Y",strtotime($bK->check_date));
						$monthCheck  = date("n",strtotime($bK->check_date));
						$status            = "";
						$cvStatus        = "";
						if(($monthCV < $monthCheck and $yearCV == $yearCheck) or ($monthCV > $monthCheck and $yearCV < $year)):
							$status = "PDC";
							$cvStatus = $bK->cv_status;
						else:
							$status = $bK->status;
							$cvStatus = $bK->cv_status;
						endif;
                $bkArray[] = $bK->cv_no."|".$bK->check_no."|".date("m/d/Y",strtotime($bK->cv_date))."|".date("m/d/Y",strtotime($bK->check_date))."|".number_format($bK->check_amount,2)."|".$cvStatus."|".$status."|".$bK->payee;
				$totalBK +=$bK->check_amount;
			  if($countRowBK > $countRowBS)
					{
						$indexProg = ($xbk/$countRow)*14.30;
						if($xbk > 1)
							{
								$prevProg = (($xbk-1)/$countRow)*14.30;
								$min   = $indexProg - $prevProg;
								$this->progress += $min;
							}
						echo response()->json(['progress7'=>$indexProg,'percent'=>$this->progress]);
					}
					
			  $xbk++;
			}
			
				if($countRowBK==0 and $countRowBS ==0)
					{
						
						echo response()->json(['progress7'=>14.30,'percent'=>$this->progress]);
					}

			// dd($this->normalizeArray($bsArray,$bkArray));
            $data = $this->normalizeArray($bsArray,$bkArray);
	        $data[] =['','','Total: ',number_format($totalBS,2),'','','','','Total: ',number_format($totalBK,2),'','',''];
			
	        return $data;

        }
    }

    function normalizeArray($ar1,$ar2)
    {
        $count1 = count($ar1);
        $count2 = count($ar2);

        if($count1 > $count2)
        {
            $diff = $count1 - $count2;
            for($x=1;$x<=$diff;$x++)
            {

                $ar2[] = '  |  |  |  |  | | | ';
            }
            $array = Array();
            foreach($ar1 as $key => $a)
            {
                $exp     = explode("|",$a);
                $exp1    = explode("|",$ar2[$key]);
                
	            if(trim($exp[2])==""):
		            $bsDate  = " ";
	            else:
		            $bsDate1 = strtotime($exp[2]);
		            $bsDate  = PHPExcel_Shared_Date::PHPToExcel($bsDate1);
	            endif;
	
	            if(trim($exp1[2])==""):
		            $cvDate  = "";
	            else:
		            $cvDate1 = strtotime($exp1[2]);
		            $cvDate  = PHPExcel_Shared_Date::PHPToExcel($cvDate1);
	            endif;
	
	            if(trim($exp1[3])==""):
		            $ckDate  = "";
	            else:
		            $ckDate1 = strtotime($exp1[3]);
		            $ckDate  = PHPExcel_Shared_Date::PHPToExcel($ckDate1);
	            endif;
	            
                $array[] = [$exp[0],$exp[1],$bsDate,$exp[3],$exp[4],$exp1[0],$exp1[1],$cvDate,$ckDate,$exp1[4],$exp1[5],$exp1[6],$exp1[7]];
            }
            // dd($array);
            return $array;
        }
        elseif($count1 < $count2)
        {
            $diff = $count2 - $count1;
            for($x=1;$x<=$diff;$x++)
            {
                $ar1[] = '  |  |  |  |  | | ';
            }

            $array = Array();
            foreach($ar2 as $key => $a)
            {
                $exp1   = explode("|",$a);
                $exp    = explode("|",$ar1[$key]);
                
                if(trim($exp[2])==""):
	                $bsDate  = " ";
	            else:
		            $bsDate1 = strtotime($exp[2]);
		            $bsDate  = PHPExcel_Shared_Date::PHPToExcel($bsDate1);
	            endif;
	            
	            if(trim($exp1[2])==""):
		            $cvDate  = "";
		        else:
			        $cvDate1 = strtotime($exp1[2]);
			        $cvDate  = PHPExcel_Shared_Date::PHPToExcel($cvDate1);
		        endif;
		        
		        if(trim($exp1[3])==""):
		            $ckDate  = "";
		        else:
			        $ckDate1 = strtotime($exp1[3]);
			        $ckDate  = PHPExcel_Shared_Date::PHPToExcel($ckDate1);
		        endif;
	
	            $array[] = [$exp[0],$exp[1],$bsDate,$exp[3],$exp[4],$exp1[0],$exp1[1],$cvDate,$ckDate,$exp1[4],$exp1[5],$exp1[6],$exp1[7]];
            }
            // dd($array);
            return $array;

        }
        else
        {
			
            $array = Array();
			//var_dump($ar1);
            foreach($ar2 as $key => $a)
            {
				// echo "EQUAL SILA " . is_array($ar2[$key]) ? 'Array' : 'not an Array';;
                // $array[] = array_merge($a, $ar2[$key]);
				$exp1   = explode("|",$a);
                $exp    = explode("|",$ar1[$key]);
                
                if(trim($exp[2])==""):
	                $bsDate  = " ";
	            else:
		            $bsDate1 = strtotime($exp[2]);
		            $bsDate  = PHPExcel_Shared_Date::PHPToExcel($bsDate1);
	            endif;
	            
	            if(trim($exp1[2])==""):
		            $cvDate  = "";
		        else:
			        $cvDate1 = strtotime($exp1[2]);
			        $cvDate  = PHPExcel_Shared_Date::PHPToExcel($cvDate1);
		        endif;
		        
		        if(trim($exp1[3])==""):
		            $ckDate  = "";
		        else:
			        $ckDate1 = strtotime($exp1[3]);
			        $ckDate  = PHPExcel_Shared_Date::PHPToExcel($ckDate1);
		        endif;
	
	            $array[] = [$exp[0],$exp[1],$bsDate,$exp[3],$exp[4],$exp1[0],$exp1[1],$cvDate,$ckDate,$exp1[4],$exp1[5],$exp1[6],$exp1[7]];
            }
            return $array;
        }


    }
    
    public function cvNextMonth($com,$bu,$bankno)
    {
    	$nextMonthData = Array();
		$countRow1 = count($this->cvNextMonth);
		$x = 1;
	    $totalBS = 0;
	    $totalBK = 0;
        foreach($this->cvNextMonth as $keys => $cvData)
		{
			
					$indexProg1   = ($x/$countRow1)*14.28;
					if($x > 1)
					{
						$prevProg1 = (($x-1)/$countRow1)*14.28;
						$min   = $indexProg1 - $prevProg1;
						$this->progress += $min;
					}
					
			echo response()->json(['progress2'=>$indexProg1,'percent'=>$this->progress]);
			if($countRow1<=100)
			{		
				usleep(55500);
			}
	        $exp2       = explode("|",$cvData);
	        $cvNo       = $exp2[0];
			
	
			
	            $PHPDateValue = strtotime($exp2[1]);
	        $cvDate     = PHPExcel_Shared_Date::PHPToExcel($PHPDateValue);
	            $PHPDateValue1 = strtotime($exp2[2]);
	        $ckDate     = PHPExcel_Shared_Date::PHPToExcel($PHPDateValue1);
	        $ckNo       = $exp2[3];
	        $ckAmt      = $exp2[4];
	        $cvStatus   = $exp2[5];
	        $status     = $exp2[6];
            $bsData     = BankStatement::where('bank_check_no',$ckNo)
	                      ->where('bank_amount',$ckAmt)
	                      ->where('bank_account_no',$bankno)
                          ->where('company',$com)
                          ->where('bu_unit',$bu)
						  ->where('type','AP');
						  
		  
                if($bsData->count('bank_id')>0)
                {
                	foreach ($bsData->get() as $key => $bs):
		                $bsStr      = strtotime($bs->bank_date);
		                $bsDate     = PHPExcel_Shared_Date::PHPToExcel($bsStr);
		                $cvMonth    = date("n",strtotime($exp2[1]));
		                $cvYear     = date("Y",strtotime($exp2[1]));
		                $ckMonth    = date("n",strtotime($exp2[2]));
		                $ckYear     = date("Y",strtotime($exp2[2]));
		                if(($cvMonth < $ckMonth and $cvYear == $ckYear) or ($cvMonth > $ckMonth and $cvYear < $ckYear))
		                {
		                	$ckStatus = "PDC";
		                }
		                else
		                {
		                	$ckStatus = "OC";
		                }
		                
		               $nextMonthData[] = [$bs->description,$bs->bank_check_no,$bsDate,number_format($bs->bank_amount,2),'  ',$cvNo,$ckNo,$cvDate,$ckDate,number_format($ckAmt,2),$cvStatus,$ckStatus];
		                $totalBS +=$bs->bank_amount;
		                $totalBK +=$ckAmt;
		             endforeach;
                }
			
                $x++;
	    }
	    $nextMonthData[] = ['','','Total: ',number_format($totalBS,2),'  ','','','','Total: ',number_format($totalBK,2),''];
      //echo response()->json(['progress1'=>28.58,'percent'=>28.58]);
	    return $nextMonthData;
    }
    
    public function bsPrevMonth($com,$bu,$bankno)
    {
        $prevMonthData = Array();
		$countRow = count($this->bsPrevMonth);
			$x =1;
	    $totalBS = 0;
	    $totalBK = 0;
          foreach($this->bsPrevMonth as $keys => $bs)
		  {

						$indexProg   = ($x/$countRow)*14.28;
					if($x > 1)
					{
						$prevProg = (($x-1)/$countRow)*14.28;
						$min   = $indexProg - $prevProg;
						$this->progress += $min;
					}
					
			echo response()->json(['progress3'=>$indexProg,'percent'=>$this->progress]);
				if($countRow<=100)
					{		
						usleep(55500);
					}
	          $exp     = explode("|",$bs);
	          $bsStr      = strtotime($exp[0]);
	          $bsDate     = PHPExcel_Shared_Date::PHPToExcel($bsStr);
	          $bsCheck = $exp[1];
	          $bsDes   = $exp[2];
	          $bsAmt   = $exp[3];
              $bkData  = PdcLine::where('check_no',$bsCheck)
	                     ->where('check_amount',$bsAmt)
                         ->where('baccount_no',$bankno)
	                     ->where('company',$com)
                         ->where('bu_unit',$bu);

                if($bkData->count('id') >0)
                {
                	foreach($bkData->get() as $key => $bk):
		                $cvDate1      = strtotime($bk->cv_date);
		                $cvDate     = PHPExcel_Shared_Date::PHPToExcel($cvDate1);
		                $ckDate1    = strtotime($bk->check_date);
		                $ckDate     = PHPExcel_Shared_Date::PHPToExcel($ckDate1);
                        $prevMonthData[] =[$bsDes,$bsCheck,$bsDate,number_format($bsAmt,2),'  ',$bk->cv_no,$bk->check_no,$cvDate,$ckDate,number_format($bk->check_amount,2),$bk->cv_status];
		                $totalBS +=$bsAmt;
		                $totalBK +=$bk->check_amount;
                	endforeach;
                }
			$x++;
	      }
	    $prevMonthData[] =['','','Total: ',number_format($totalBS,2),'  ','','','','Total: ',number_format($totalBK,2),''];
		   //echo response()->json(['progress1'=>42.87,'percent'=>42.87]);
	    return $prevMonthData;
    }
	
	public function binarysearch($arraycheckno,$findcheckno)
    {
        $start = 0;
        $end   = Count($arraycheckno)-1;

        $start = 0;
        $end = count($arraycheckno)-1;

        while ($start <= $end)
        {

            $mid = (int) floor(($start + $end)/2);

            if ( $arraycheckno[$mid] ==  $findcheckno)
            {
				return $mid;
				//return $this->realindex($arraycheckno,$findcheckno,$mid);
                
				
            }
            elseif ( $findcheckno < $arraycheckno[$mid] )
            {
                $end = $mid-1;
            }
            else
            {
                $start = $mid+1;
            }

        }
        return -1;
    }

    public function  realindex($arraycheckno,$findcheckno,$index1)
    {
        if($index1!=0)
        {
            for($x1=$index1;$x1>=0;$x1--)
            {
                if($findcheckno != $arraycheckno[$x1])
                {
                    return $x1 + 1;
                }
            }
        }
        else
        {
            return 0;
        }	
    }
	
	public function maxindex($arraycheckno,$findcheckno,$index1)
	{
        if($index1!=0)
        {
			if($index1==count($arraycheckno)-1)
			{
				return $index1;
			}
			else
			{				
				for($x1=$index1;$x1<count($arraycheckno);$x1++)
				{
					if($findcheckno != $arraycheckno[$x1])
					{
						
						return $x1 - 1;
					}
				}
			}
        }
        else
        {
            return 0;
        }		
	}

}
