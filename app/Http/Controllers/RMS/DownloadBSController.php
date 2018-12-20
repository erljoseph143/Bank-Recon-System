<?php

namespace App\Http\Controllers\RMS;

use App\BankStatement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Shared_Date;
use PHPExcel_Style_NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DownloadBSController extends Controller
{

    public function getBS($bDate,$bankno,$com,$bu,$bankname,$accountno)
    {
		if(trim($bankname)=='FCB')
		{
			$this->FCBbank($bDate,$bankno,$com,$bu,$bankname,$accountno);
		}
		else
		{
			$this->regularbank($bDate,$bankno,$com,$bu,$bankname,$accountno);
		}
    }
    
    public function regularbank($bDate,$bankno,$com,$bu,$bankname,$accountno)
    {
	    $filename = "Bank Statement for " .date("F, Y",strtotime($bDate)) . " - " . $bankname ." - " . $accountno;
	    Excel::create($filename,function($excel)use($bDate,$bankno,$com,$bu,$bankname,$accountno) {
		
		    // Set the title
		    $excel->setTitle('Bank Statement');
		
		    // Chain the setters
		    $excel->setCreator('BRS PROGRAMMER REY JOSEPH T. BAAY, JELLARY CADUTDUT,GLENN MICHAEL MEJEIAS')->setCompany('Coporate AGC');
		
		    $excel->setDescription('Consolidated Bank Statement');
		
		    $excel->sheet('Bank Statement',function($sheet)use($bDate,$bankno,$com,$bu,$bankname,$accountno){
			    $month = date("m", strtotime($bDate));
			    $year = date("Y", strtotime($bDate));
			    $bs = BankStatement::select('bank_id', 'bank_date', 'description', 'bank_account_no', 'bank_check_no', 'bank_amount', 'bank_balance', 'type')
				    ->whereMonth('bank_date', $month)
				    ->whereYear('bank_date', $year)
				    ->where('bank_account_no', $bankno)
				    ->where('company', $com)
				    ->where('bu_unit', $bu)
				    ->get();
			
			    $sheet->setOrientation('landscape');
			    $count = count($bs->all()) + 5;
			
			    $headings = array('BANK DATE', 'DESCRIPTION','CHECK NO','DEBIT AMOUNT','CREDIT AMOUNT','BANK BALANCE');
			
			    $sheet->prependRow(4, $headings);
			    $sheet->setBorder('A4:F4', 'thin');
			    $sheet->mergecells('A5:E5');
			    $sheet->row(5,array('BALANCE FORWARDED'));
			    $sheet->setBorder('A5:F5', 'thin');
			    $sheet->setBorder('A6:F'.$count, 'thin');
			    $arrayBS = Array();
			    foreach ($bs as $b)
			    {
				    $type = $b->type;
				    if($type == "AP")
				    {
					    $begbal = $b->bank_balance + $b->bank_amount;
				    }
				    else
				    {
					    $begbal = $b->bank_balance - $b->bank_amount;
				    }
				    break;
			    }
			    $sheet->row(1,array($bankname ." - ". $accountno));
			    $sheet->row(2,array(date("F, Y",strtotime($bDate))));
			    $sheet->cell('F5', function($cell)use($begbal) {
				
				    // manipulate the cell
				    $cell->setValue($begbal);
				
			    });
			
			    foreach($bs as $b)
			    {
				    $type = $b->type;
				    if($type == "AP")
				    {
					    $ap_amount = $b->bank_amount;
					    $ar_amount = "";
				    }
				    else
				    {
					    $ar_amount = $b->bank_amount;
					    $ap_amount = "";
				    }
				    $PHPDateValue = strtotime($b->bank_date);
				    $bsDate = PHPExcel_Shared_Date::PHPToExcel($PHPDateValue);
				    $arrayBS[] = Array($bsDate,$b->description,$b->bank_check_no,$ap_amount,$ar_amount,(double)trim($b->bank_balance));
			    }
			
			    $sheet->fromArray($arrayBS, NULL, 'A6',false,false);
			
			    $sheet->row(3,array('Bank Statement'));
			    $sheet->setBorder('A3', 'thin');
			    $sheet->mergecells('A3:F3');
			    $sheet->getStyle('A3')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
			
			    $sheet->getStyle('F5')->getAlignment()->applyFromArray(array('horizontal' => 'right'));
			    for($s=6;$s<=$count;$s++)
			    {
				    $sheet->getStyle('D'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
				    $sheet->getStyle('E'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
				    $sheet->getStyle('F'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
				    $sheet->getStyleByColumnAndRow(0, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);

			    }
			    $sheet->getStyle("D5:E{$count}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			    $sheet->getStyle("E5:F{$count}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			    $sheet->getStyle("F5:G{$count}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			
		    });
		
	    })->download('xlsx');
    }
    
    public function FCBbank($bDate,$bankno,$com,$bu,$bankname,$accountno)
    {
	    $filename = "Bank Statement for " .date("F, Y",strtotime($bDate)) . " - " . $bankname ." - " . $accountno;
	    Excel::create($filename,function($excel)use($bDate,$bankno,$com,$bu,$bankname,$accountno) {
		
		    // Set the title
		    $excel->setTitle('Bank Statement');
		
		    // Chain the setters
		    $excel->setCreator('BRS PROGRAMMER REY JOSEPH T. BAAY, JELLARY CADUTDUT,GLENN MICHAEL MEJEIAS')->setCompany('Coporate AGC');
		
		    $excel->setDescription('Consolidated Bank Statement');
		
		    $excel->sheet('Bank Statement',function($sheet)use($bDate,$bankno,$com,$bu,$bankname,$accountno){
			    $month = date("m", strtotime($bDate));
			    $year = date("Y", strtotime($bDate));
			    $bs = BankStatement::select('bank_id', 'bank_date','bank_ref_no','description', 'bank_account_no', 'bank_check_no', 'bank_amount', 'bank_balance', 'type','actual_balance')
				    ->whereMonth('bank_date', $month)
				    ->whereYear('bank_date', $year)
				    ->where('bank_account_no', $bankno)
				    ->where('company', $com)
				    ->where('bu_unit', $bu)
				    ->get();
			
			    $sheet->setOrientation('landscape');
			    $count = count($bs->all()) + 5;
			
			    $headings = array('BANK DATE', 'REFERENCE','CHECK NO','TR CODE','DEBIT AMOUNT','CREDIT AMOUNT','ACTUAL BALANCE','CLEARED BALANCE');
			
			    $sheet->prependRow(4, $headings);
			    $sheet->setBorder('A4:H4', 'thin');
			    $sheet->mergecells('A5:E5');
			    $sheet->row(5,array('BALANCE FORWARDED'));
			    $sheet->setBorder('A5:H5', 'thin');
			    $sheet->setBorder('A6:H'.$count, 'thin');
			    $arrayBS = Array();
			    foreach ($bs as $b)
			    {
				    $type = $b->type;
				    if($type == "AP")
				    {
					    $begbal = $b->bank_balance + $b->bank_amount;
				    }
				    else
				    {
					    $begbal = $b->bank_balance - $b->bank_amount;
				    }
				    break;
			    }
			    $sheet->row(1,array($bankname ." - ". $accountno));
			    $sheet->row(2,array(date("F, Y",strtotime($bDate))));
			    $sheet->cell('G5', function($cell)use($begbal) {
				
				    // manipulate the cell
				    $cell->setValue($begbal);
				
			    });
			    $sheet->cell('H5', function($cell)use($begbal) {
				
				    // manipulate the cell
				    $cell->setValue($begbal);
				
			    });
			
			    foreach($bs as $b)
			    {
				    $type = $b->type;
				    if($type == "AP")
				    {
					    $ap_amount = $b->bank_amount;
					    $ar_amount = 0;
				    }
				    else
				    {
					    $ar_amount = $b->bank_amount;
					    $ap_amount = 0;
				    }
				    $PHPDateValue = strtotime($b->bank_date);
				    $bsDate       = PHPExcel_Shared_Date::PHPToExcel($PHPDateValue);
				    $arrayBS[]    = Array($bsDate,$b->bank_ref_no,$b->bank_check_no,$b->description,$ap_amount,$ar_amount,$b->actual_balance,(double)trim($b->bank_balance));
			    }
			
			    $sheet->fromArray($arrayBS, NULL, 'A6',false,false);
			
			    $sheet->row(3,array('Bank Statement'));
			    $sheet->setBorder('A3', 'thin');
			    $sheet->mergecells('A3:H3');
			    $sheet->getStyle('A3')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
			
			    $sheet->getStyle('G5')->getAlignment()->applyFromArray(array('horizontal' => 'right'));
			    $sheet->getStyle('H5')->getAlignment()->applyFromArray(array('horizontal' => 'right'));
			    for($s=6;$s<=$count;$s++)
			    {
				    $sheet->getStyle('E'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
				    $sheet->getStyle('F'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
				    $sheet->getStyle('G'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
				    $sheet->getStyle('H'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
				    $sheet->getStyleByColumnAndRow(0, $s)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
			    }
			    $sheet->getStyle("E6:E{$count}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			    $sheet->getStyle("F6:F{$count}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			    $sheet->getStyle("G5:G{$count}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			    $sheet->getStyle("H5:H{$count}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			
		    });
		
	    })->download('xlsx');
    }
}
