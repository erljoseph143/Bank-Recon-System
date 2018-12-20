<?php

namespace App\Http\Controllers\Deposit;

use App\BankAccount;
use App\Businessunit;
use App\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Cell;
use PHPExcel_Shared_Date;
use PHPExcel_Style_NumberFormat;

class DepositExcelController extends Controller
{
	public $excelData;
	public $dateData;
	public $viewExcelData;
	
	public function __construct()
	{
		$this->middleware('auth');
		$this->excelData = Array();
		$this->dateData  = Array();
		$this->viewExcelData = Array();
	}
	
	public function Deposit(Request $request)
	{
		if($request->ajax())
		{
			$y = date("Y");
			$yearOf = Array();
			$yearOf[$y] = $y;
			for($x = 1;$x<=10;$x++)
			{
				$m = $y - $x;
				$yearOf[$m] = $y - $x;
			}
			//dd($year);
			
			$com = Company::pluck('company','company_code')->all();
			
			// dd($com);
			return view('CV.Deposit.deposit',compact('com','yearOf'));
		}
	}
	
	public function depUploader(Request $request)
	{
		$files = $request->file('mainfiles');
		$path = $files->getPathname();
		$fileName = $files->getClientOriginalName();
		$extensions = array('.xls','.XLS','.xlsx','.XLSX'); // mao rani ang allowed nga file extension .xls,.XLS,.xlsx or XLSX
		$extension = strrchr($fileName, '.');
		if (!in_array($extension, $extensions))
		{
			$this->showMessageError("One of the files has invalid file extension!Only .xls or xlsx files are accepted to be uploaded!","Error!",'home');
			die();
		}
		
		Excel::load($path,function($reader) {
			$objWorksheet = $reader->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow();
			$highestColumn = $objWorksheet->getHighestColumn();
			$colNumber = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$colString = PHPExcel_Cell::stringFromColumnIndex($colNumber);
//CV No.	Line No.	CV Status	CV Date	Check Number	Check Amount	Bank Account No.	Bank Name	Check Date	Clearing Date	Cleared Flag	Payee
			
			$entry_no  = strtolower($objWorksheet->getCellByColumnAndRow(0,1));
			$bankActNO = strtolower($objWorksheet->getCellByColumnAndRow(1,1));
			$pdate     = strtolower($objWorksheet->getCellByColumnAndRow(2,1));
			$doctype   = strtolower($objWorksheet->getCellByColumnAndRow(3,1));
			$docNum    = strtolower($objWorksheet->getCellByColumnAndRow(4,1));
			$extDocNum = strtolower($objWorksheet->getCellByColumnAndRow(5,1));
			$desCr     = strtolower($objWorksheet->getCellByColumnAndRow(6,1));
			$username  = strtolower($objWorksheet->getCellByColumnAndRow(7,1));
			$AMT       = strtolower($objWorksheet->getCellByColumnAndRow(8,1));
			if(
				$entry_no != 'entry no.'
				or
				$bankActNO != 'bank account no.'
				or
				$pdate != 'posting date'
				or
				$doctype != 'document type'
				or
				$docNum  != 'document no.'
				or
				$extDocNum !='external document no.'
				or
				$desCr  != 'description'
				or
				$username !='user id'
				or
				$AMT != 'amount'
			)
			{
				$this->showMessageError("Invalid Deposit File!","Error!",'home');
				die();
			}
			
			for($y=2;$y<=$highestRow;$y++):
				$entry_no  = $objWorksheet->getCellByColumnAndRow(0,$y)->getValue();
				$bankActNO = $objWorksheet->getCellByColumnAndRow(1,$y)->getValue();
				$pdate     = date("n/d/Y",PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(2,$y)->getValue()));
				$doctype   = $objWorksheet->getCellByColumnAndRow(3,$y)->getValue();
				$docNum    = $objWorksheet->getCellByColumnAndRow(4,$y)->getValue();
				$extDocNum = $objWorksheet->getCellByColumnAndRow(5,$y)->getValue();
				$desCr     = $objWorksheet->getCellByColumnAndRow(6,$y)->getValue();
				$username  = $objWorksheet->getCellByColumnAndRow(7,$y)->getValue();
				$AMT       = $objWorksheet->getCellByColumnAndRow(8,$y)->getValue();
				if($entry_no!='' and $entry_no!=null)
				{
					$this->excelData[] = [
						$entry_no,
						$bankActNO,
						$pdate,
						$doctype,
						$docNum,
						$extDocNum,
						$desCr,
						$username,
						$AMT
					];
					$this->dateData[]  = $pdate;
				}
			endfor;
			
		});

//		usort($this->excelData, function($a,$b){
//			return $a[3] - $b[3];
//		});
		$yearArray = Array();
		$monthArray = ['01','02','03','04','05','06','07','08','09','10','11','12'];
		foreach($this->dateData as $key => $year):
			$yearArray[] = date("Y",strtotime($year));
		endforeach;
		$jan = Array();
		$feb = Array();
		$mar = Array();
		$apr = Array();
		$may = Array();
		$jun = Array();
		$jul = Array();
		$aug = Array();
		$sep = Array();
		$oct = Array();
		$nov = Array();
		$dec = Array();
		//	dd($this->excelData);
		foreach($this->excelData as $key => $data):
			
			
			$entry_no     = $data[0];
			$bankActNO    = $data[1];
			$PHPDateValue = strtotime($data[2]);
			$pdate        = PHPExcel_Shared_Date::PHPToExcel($PHPDateValue);
			$doctype      = $data[3];
			$docNum       = $data[4];
			$extDocNum    = $data[5];
			$desCr        = $data[6];
			$username     = $data[7];
			$AMT          = $data[8];
			
			$yearIn       = date("Y",strtotime($data[2]));
			$monthIn      = date("m",strtotime($data[2]));
			foreach(array_unique($yearArray) as $key2 => $year)
			{
				if($monthIn == $monthArray[0] and $yearIn == $year):
					$jan[] = [
						$entry_no,
						$bankActNO,
						$pdate,
						$doctype,
						$docNum,
						$extDocNum,
						$desCr,
						$username,
						$AMT
					];
				elseif($monthIn == $monthArray[1] and $yearIn == $year):
					$feb[] = [
						$entry_no,
						$bankActNO,
						$pdate,
						$doctype,
						$docNum,
						$extDocNum,
						$desCr,
						$username,
						$AMT
					];
				elseif($monthIn == $monthArray[2] and $yearIn == $year):
					$mar[] = [
						$entry_no,
						$bankActNO,
						$pdate,
						$doctype,
						$docNum,
						$extDocNum,
						$desCr,
						$username,
						$AMT
					];
				elseif($monthIn == $monthArray[3] and $yearIn == $year):
					$apr[] = [
						$entry_no,
						$bankActNO,
						$pdate,
						$doctype,
						$docNum,
						$extDocNum,
						$desCr,
						$username,
						$AMT
					];
				elseif($monthIn == $monthArray[4] and $yearIn == $year):
					$may[] = [
						$entry_no,
						$bankActNO,
						$pdate,
						$doctype,
						$docNum,
						$extDocNum,
						$desCr,
						$username,
						$AMT
					];
				elseif($monthIn == $monthArray[5] and $yearIn == $year):
					$jun[] = [
						$entry_no,
						$bankActNO,
						$pdate,
						$doctype,
						$docNum,
						$extDocNum,
						$desCr,
						$username,
						$AMT
					];
				elseif($monthIn == $monthArray[6] and $yearIn == $year):
					$jul[] = [
						$entry_no,
						$bankActNO,
						$pdate,
						$doctype,
						$docNum,
						$extDocNum,
						$desCr,
						$username,
						$AMT
					];
				elseif($monthIn == $monthArray[7] and $yearIn == $year):
					$aug[] = [
						$entry_no,
						$bankActNO,
						$pdate,
						$doctype,
						$docNum,
						$extDocNum,
						$desCr,
						$username,
						$AMT
					];
				elseif($monthIn == $monthArray[8] and $yearIn == $year):
					$sep[] = [
						$entry_no,
						$bankActNO,
						$pdate,
						$doctype,
						$docNum,
						$extDocNum,
						$desCr,
						$username,
						$AMT
					];
				elseif($monthIn == $monthArray[9] and $yearIn == $year):
					$oct[] = [
						$entry_no,
						$bankActNO,
						$pdate,
						$doctype,
						$docNum,
						$extDocNum,
						$desCr,
						$username,
						$AMT
					];
				elseif($monthIn == $monthArray[10] and $yearIn == $year):
					$nov[] = [
						$entry_no,
						$bankActNO,
						$pdate,
						$doctype,
						$docNum,
						$extDocNum,
						$desCr,
						$username,
						$AMT
					];
				elseif($monthIn == $monthArray[11] and $yearIn == $year):
					$dec[] = [
						$entry_no,
						$bankActNO,
						$pdate,
						$doctype,
						$docNum,
						$extDocNum,
						$desCr,
						$username,
						$AMT
					];
				endif;
			}
		endforeach;
		//dd(array_unique($yearArray));
		$bActId  = $request->bankact;
		$yearOf  = $request->year;
		$company = $request->company;
		$com     = Company::find($company)->company;
		$buUnit  = $request->bu_unit;
		$bu      = Businessunit::find($buUnit)->bname;
		$bankAct = BankAccount::find($bActId);
		$bank_no = $bankAct->bankcode->bankno;
		
		$path = storage_path("exports\deposit-excel\\$com\\$bu");
		File::makeDirectory($path, 0777, true, true);
		
		for($x=1;$x<=12;$x++)
		{
			if($x==1 and count($jan)!=0):
				$title = "Deposit for January $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($jan,$title,$com,$bu);
			elseif($x==2 and count($feb)!=0):
				$title = "Deposit for February $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($feb,$title,$com,$bu);
			elseif($x==3 and count($mar)!=0):
				$title = "Deposit for March $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($mar,$title,$com,$bu);
			elseif($x==4 and count($apr)!=0):
				$title = "Deposit for April $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($apr,$title,$com,$bu);
			elseif($x==5  and count($may)!=0):
				$title = "Deposit for May $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($may,$title,$com,$bu);
			elseif($x==6 and count($jun)!=0):
				$title = "Deposit for June $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($jun,$title,$com,$bu);
			elseif($x==7 and count($jul)!=0):
				$title = "Deposit for July $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($jul,$title,$com,$bu);
			elseif($x==8 and count($aug)!=0):
				$title = "Deposit for August $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($aug,$title,$com,$bu);
			elseif($x==9 and count($sep)!=0):
				$title = "Deposit for September $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($sep,$title,$com,$bu);
			elseif($x==10 and count($oct)!=0):
				$title = "Deposit for October $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($oct,$title,$com,$bu);
			elseif($x==11 and count($nov)!=0):
				$title = "Deposit for November $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($nov,$title,$com,$bu);
			elseif($x==12 and count($dec)!=0):
				$title = "Deposit for December $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($dec,$title,$com,$bu);
			endif;
		}
		
		echo "Success";
		return redirect('home');
		
	}
	
	public function ExcelSaving($data,$title,$com,$bu)
	{
		Excel::create("deposit-excel\\$com\\$bu/$title",function($excel)use($data){
			
			// Set the title
			$excel->setTitle('CHECK VOUCHER FORMAT');
			
			// Chain the setters
			$excel->setCreator('BRS PROGRAMMER REY JOSEPH T. BAAY, JELLARY CADUTDUT,GLENN MICHAEL MEJEIAS')->setCompany('Coporate AGC');
			
			$excel->sheet('Sheet 1', function ($sheet)use($data) {
				
				$sheet->setOrientation('landscape');
				
				$headings = [
								'Entry No.',
								'Bank Account No.',
								'Posting Date',
								'Document Type',
								'Document No.',
								'External Document No.',
								'Description',
								'User ID',
								'Amount'
							];
				
				$count = count($data);
				for($row=2;$row<=$count;$row++)
				{
					$sheet->getStyleByColumnAndRow(2, $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
//					$sheet->getStyleByColumnAndRow(8, $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
//					$sheet->getStyleByColumnAndRow(9, $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
				}
				$sheet->prependRow(1, $headings);
				$sheet->fromArray($data, NULL, 'A2',false,false);
				
				for($row=2;$row<=$count;$row++)
				{
					$sheet->getStyleByColumnAndRow(2, $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
//					$sheet->getStyleByColumnAndRow(8, $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
//					$sheet->getStyleByColumnAndRow(9, $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
				}
				
			});
		})->save('xlsx');
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
