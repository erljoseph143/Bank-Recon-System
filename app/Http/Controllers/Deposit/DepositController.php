<?php

namespace App\Http\Controllers\Deposit;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BankNo;
use App\BankStatement;
use App\Deposit;
use App\Functions\dis_matching;
use App\Functions\Progress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Shared_Date;

class DepositController extends Controller
{
	
	public function __construct()
	{
		$this->middleware('auth');
		$this->totalRow = 0;
		$this->bank_number ="";
		$this->cv_date_me="";
		$this->progressBar = new Progress();
		echo view('layouts.progressbar');
		//echo view('layouts.bsRadar');
		$this->progressBar->initprogress();
		$this->x =1;
		$this->dismatching = new dis_matching();
		$this->bank_no ="";
		$this->date="";
		$this->bankID="";
	}
	
	public function saveExcel(Request $request)
	{
		$this->com = Auth::user()->company_id;
		$this->bu  = Auth::user()->bunitid;
		/*-------------------------------------------------------------------------------------------------------------------------
		 * Check the file if it is an excel format or not
		 *-------------------------------------------------------------------------------------------------------------------------
		 */
		
		$fileName = $request->filename;
		$extensions = array('.xls','.XLS','.xlsx','.XLSX'); // mao rani ang allowed nga file extension .xls,.XLS,.xlsx or XLSX
		$extension = strrchr($fileName, '.');
		if (!in_array($extension, $extensions))
		{
			$this->showMessageError("One of the files has invalid file extension!Only .xls or xlsx files are accepted to be uploaded!","Error!",'home');
			die();
		}
		
		/*-------------------------------------------------------------------------------------------------------------------------
		 * Check the excel file header format is correct, Set also the Total Rows for progress bar
		 *-------------------------------------------------------------------------------------------------------------------------
		 */
		 $this->bankID = $request->bankAct;
		$path = $request->path;

			Excel::load($path,function($reader) {
				DB::transaction(function()use($reader){
					$objWorksheet = $reader->getActiveSheet();
					$highestRow = $objWorksheet->getHighestRow();
					$highestColumn = $objWorksheet->getHighestColumn();
					
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
//Entry No.	Bank Account No.	Posting Date	Document Type	Document No.	External Document No.	Description	User ID	Amount
					
					$index=2;
					$num_rows = $highestRow;
					$this->progressBar->settotalvalues($num_rows-1);
					while($index<=$num_rows)
					{
						$this->progressBar->setprogress($this->x);
						$this->progressBar->displayprogress('true');
						$getPercent = $this->progressBar->getpercentrounded();
						if($index<$num_rows)
						{
							$this->x++;
						}
						$entryno     = $objWorksheet->getCellByColumnAndRow(0,$index)->getValue();
						$bankno      = $objWorksheet->getCellByColumnAndRow(1,$index)->getValue();
						$postingdate = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(2, $index)->getValue()));
						$docno       = $objWorksheet->getCellByColumnAndRow(4,$index)->getValue();
						$extdocno    = $objWorksheet->getCellByColumnAndRow(5,$index)->getValue();
						$des         = $objWorksheet->getCellByColumnAndRow(6,$index)->getValue();
						$userID      = $objWorksheet->getCellByColumnAndRow(7,$index)->getValue();
						$amount      = $objWorksheet->getCellByColumnAndRow(8,$index)->getValue();
						$countDep    = Deposit::where('entry_no',$entryno)
							->where('bank_account_no',$bankno)
							->where('posting_date',$postingdate)
							->where('doc_no',$docno)
							->where('ext_doc_no',$extdocno)
							->where('amount',$amount)
							->count('id');
						//	echo htmlentities($des, ENT_COMPAT,'ISO-8859-1', true);
						if($countDep<=0)
						{
							Deposit::updateOrCreate([
								'entry_no'=>$entryno,
								'bank_account_no'=>$bankno,
								'posting_date'=>$postingdate,
								'doc_no'=>$docno,
								'ext_doc_no'=>$extdocno,
								'description'=>$des,
								'amount'=>$amount,
								'users'=>utf8_encode($userID),
								'company'=>$this->com,
								'bu_unit'=>$this->bu
							]);
						}
						$this->bank_no = $bankno;
						$this->date    = $postingdate;
						$index++;
					}
					
				});
			});
			
	    $bankno  = $this->bank_no;
		$date    = $this->date;
		$bankAct = $this->bankID;
		$title   = "BRS Uploading";
		$message = "Deposit Data Uploaded Successfully";
		echo view('deposit.layouts.doneUpload',compact('title','message','bankno','date','bankAct'));
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
