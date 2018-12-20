<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\BankNo;
use App\BankStatement;
use App\Businessunit;
use App\Company;
use App\ManualBs;
use App\PdcLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

use PHPExcel_Cell;
use PHPExcel_Shared_Date;
use PHPExcel_Style_NumberFormat;

class ViewController extends Controller
{
    //
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

    public function dis_summary(Request $request)
    {
        if($request->ajax())
        {
            $banklist = Array();
            $com  = Auth::user()->company_id;
            $bu   = Auth::user()->bunitid;
            $bank = BankStatement::where('company',$com)
                ->where('bu_unit',$bu);
            if($bank->count('bank_id') > 0)
            {
                foreach($bank->distinct()->get(['bank_account_no as bankno']) as $b)
                {
                    // echo $b->bankno ."</br>";
                    $bankno = $b->bankno;
                    $bankName = BankNo::where('bankno',$bankno)->get();
                    foreach($bankName as $ba)
                    {
                        $bankID = $ba->id;
                    }
                    $bankName1 = BankAccount::select('bank','accountno','accountname')
                        ->where('bankno',$bankID)
                        ->where('company_code',$com)
                        ->where('buid',$bu)
                        ->get();
                    foreach ($bankName1 as $ba)
                    {
                        $banklist[] = [$ba->bank,$ba->accountno,$ba->accountname,$bankno];
                    }


                }
            }
            return view('reports.dis_sum',compact('banklist'));
        }

    }

    public function monthBank(Request $request,$bankno,$type)
    {
        $banklist = Array();
        $com      = Auth::user()->company_id;
        $bu       = Auth::user()->bunitid;

        $bankinfo = [$bankno,$com,$bu];
        if($request->ajax())
        {

            $bank = BankStatement::where('company',$com)
                ->where('bu_unit',$bu)
                ->where('bank_account_no',$bankno);
            if($bank->count('bank_id') > 0)
            {
               // $data = $bank->distinct()->get(["DATE_FORMAT(bank_date,'%Y-%m') as datein"]);
                $data = BankStatement::select(DB::raw("distinct(DATE_FORMAT(bank_date,'%Y-%m')) as datein"))
                    ->where('company',$com)
                    ->where('bu_unit',$bu)
                    ->where('bank_account_no',$bankno)
                    ->get();;

                $bankName = BankNo::where('bankno',$bankno)->get();
                foreach($bankName as $ba)
                {
                    $bankID = $ba->id;
                }
                $bankName1 = BankAccount::select('bank','accountno','accountname')
                    ->where('bankno',$bankID)
                    ->where('company_code',$com)
                    ->where('buid',$bu)
                    ->get();
                foreach ($bankName1 as $ba)
                {
                    $banklist[] = [$ba->bank,$ba->accountno,$ba->accountname,$bankno];
                }

            }
            return view('reports.month_bank',compact('data','banklist','bankno','com','bu','type'));
        }
    }

    public function findCheck()
    {
        return view('accounting.option.searchCheck');
    }

    public function getCheck($checkno)
    {
        $check = PdcLine::select('cv_date','check_date','baccount_no','check_no','check_amount','cv_no','payee')
            ->where('check_no',$checkno)
            ->get();

        return view('accounting.option.loadCheckResult',compact('check'));

    }

    public function manualBS(Request $request)
    {
        if($request->ajax())
        {
            $com = Auth::User()->company_id;
            $bu  = Auth::User()->bunitid;
            $bankAct = BankAccount::where('company_code',$com)
                ->where('buid',$bu)
                ->get()
                ->pluck('BankAccountList','id')
                ->all();
            return view('accounting.option.manualBS',compact('bankAct'));
        }

    }

    public function checkTheCheck($checkno,Request $request)
    {
        if($request->ajax())
        {
            $check = PdcLine::where('check_no',$checkno)
                ->count('id');

            echo $check;
        }


    }

    public function inputBS(Request $request)
    {
//        if($request->ajax())
//        {
            $banklist = Array();
            $com  = Auth::user()->company_id;
            $bu   = Auth::user()->bunitid;
            $bank = ManualBs::where('company',$com)
                ->where('bu_unit',$bu);

            if($bank->count('bank_id') > 0)
            {
                foreach($bank->distinct()->get(['bank_account_no as bankno']) as $b)
                {
                    // echo $b->bankno ."</br>";
                    $bankno = $b->bankno;
                    $bankName = BankNo::where('bankno',$bankno)->get();
                    foreach($bankName as $ba)
                    {
                        $bankID = $ba->id;
                    }
                    $bankName1 = BankAccount::select('bank','accountno','accountname')
                        ->where('bankno',$bankID)
                        ->where('company_code',$com)
                        ->where('buid',$bu)
                        ->get();
                    foreach ($bankName1 as $ba)
                    {
                        $banklist[] = [$ba->bank,$ba->accountno,$ba->accountname,$bankno];
                    }

                }
            } return view('accounting.option.viewManualBS',compact('banklist'));
     //   }
    }

    public function monthlyBS(Request $request,$bankno,$type)
    {
        $banklist = Array();
        $com      = Auth::user()->company_id;
        $bu       = Auth::user()->bunitid;

        if($request->ajax())
        {

            $bank = ManualBs::where('company',$com)
                ->where('bu_unit',$bu)
                ->where('bank_account_no',$bankno);
            if($bank->count('bank_id') > 0)
            {
                // $data = $bank->distinct()->get(["DATE_FORMAT(bank_date,'%Y-%m') as datein"]);
                $data = ManualBs::select(DB::raw("distinct(DATE_FORMAT(bank_date,'%Y-%m')) as datein"))
                    ->where('company',$com)
                    ->where('bu_unit',$bu)
                    ->where('bank_account_no',$bankno)
                    ->get();;

                $bankName = BankNo::where('bankno',$bankno)->get();
                foreach($bankName as $ba)
                {
                    $bankID = $ba->id;
                }
                $bankName1 = BankAccount::select('bank','accountno','accountname')
                    ->where('bankno',$bankID)
                    ->where('company_code',$com)
                    ->where('buid',$bu)
                    ->get();
                foreach ($bankName1 as $ba)
                {
                    $banklist[] = [$ba->bank,$ba->accountno,$ba->accountname,$bankno];
                }
               // dd($banklist);
            }return view('reports.month_bank',compact('data','banklist','bankno','com','bu','type'));
        }
    }

    public function showData(Request $request,$data)
    {
        $ex = explode(csrf_token()."?",$data);
        $url = base64_decode($ex[0]);
        $exp = explode("/",$url);

        $bankno = $exp[1];
        $year   = date("Y",strtotime($exp[0]));
        $month  = date("m",strtotime($exp[0]));
        $com    = $exp[2];
        $bu     = $exp[3];

        $bankNOID = BankNo::where('bankno',$bankno)->get();
        foreach ($bankNOID as $bid)
        {
            $bankID = $bid->id;
        }

        $bAcct  = BankAccount::where('company_code',$com)
        ->where('buid',$bu)
        ->where('bankno',$bankID)
        ->get();
        foreach ($bAcct as $acct)
        {
            $bankName = $acct->bank;
            $acctno   = $acct->accountno;
            $acctname = $acct->accountname;
        }

        $mBS = ManualBs::select('bank_id','bank_date','bank_account_no','description','bank_check_no','bank_amount','bank_balance','type')
        ->where('bank_account_no',$bankno)
        ->where('company',$com)
        ->where('bu_unit',$bu)
        ->whereYear('bank_date',$year)
        ->whereMonth('bank_date',$month)
        ->get();

        return view('accounting.option.monthlyManualBS',compact('mBS','com','bu','bankName','acctno','acctname'));
    }

    public function viewDis(Request $request)
    {
        if($request->ajax())
        {
            $banklist = Array();
            $com  = Auth::user()->company_id;
            $bu   = Auth::user()->bunitid;
            $bank = PdcLine::where('company_code',$com)
                ->where('bu_unit',$bu);

            if($bank->count('id') > 0)
            {
                //echo $bank->count();
                foreach($bank->distinct()->get(['baccount_no as bankno']) as $b)
                {
//                   echo $b->bankno ."</br>";
                    $bankno = $b->bankno;
                    $bankName = BankNo::where('bankno',$bankno);
                    if($bankName->count('id') > 0)
                    {
                        foreach($bankName->get() as $ba)
                        {
                            $bankID = $ba->id;
                        }
                        $bankName1 = BankAccount::select('bank','accountno','accountname')
                            ->where('bankno',$bankID)
                            ->where('company_code',$com)
                            ->where('buid',$bu)
                            ->get();
                        foreach ($bankName1 as $ba)
                        {
                            $banklist[] = [$ba->bank,$ba->accountno,$ba->accountname,$bankno];
                        }
                    }


                }

            }
            return view('accounting.option.viewDis',compact('banklist'));
        }
    }

    public function monthlyDis(Request $request,$bankno,$type)
    {
        $banklist = Array();
        $com      = Auth::user()->company_id;
        $bu       = Auth::user()->bunitid;

        if($request->ajax())
        {

            $bank = PdcLine::where('company_code',$com)
                ->where('bu_unit',$bu)
                ->where('baccount_no',$bankno);
            if($bank->count('id') > 0)
            {
                // $data = $bank->distinct()->get(["DATE_FORMAT(bank_date,'%Y-%m') as datein"]);
                $data = PdcLine::select(DB::raw("distinct(DATE_FORMAT(cv_date,'%Y-%m')) as datein"))
                    ->where('company_code',$com)
                    ->where('bu_unit',$bu)
                    ->where('baccount_no',$bankno)
                    ->get();;

                $bankName = BankNo::where('bankno',$bankno)->get();
                foreach($bankName as $ba)
                {
                    $bankID = $ba->id;
                }
                $bankName1 = BankAccount::select('bank','accountno','accountname')
                    ->where('bankno',$bankID)
                    ->where('company_code',$com)
                    ->where('buid',$bu)
                    ->get();
                foreach ($bankName1 as $ba)
                {
                    $banklist[] = [$ba->bank,$ba->accountno,$ba->accountname,$bankno];
                }
                // dd($banklist);

            }return view('reports.month_bank',compact('data','banklist','bankno','com','bu','type'));
        }
    }

    public function showDataDis(Request $request,$data)
    {
        $ex = explode(csrf_token()."?",$data);
        $url = base64_decode($ex[0]);
        $exp = explode("/",$url);

        $bankno = $exp[1];
        $year   = date("Y",strtotime($exp[0]));
        $month  = date("m",strtotime($exp[0]));
        $com    = $exp[2];
        $bu     = $exp[3];

        $disBS  = BankStatement::select('bank_date','bank_account_no','bank_check_no','bank_amount','bank_balance','type','description')
            ->where('bank_account_no',$bankno)
            ->where('company',$com)
            ->where('bu_unit',$bu)
            ->whereYear('bank_date',$year)
            ->whereMonth('bank_date',$month)
            ->where('type','AP')
            ->get();
        $disBook = PdcLine::select('cv_date','baccount_no','check_no','check_amount','check_date','cv_date','payee','cv_no')
            ->where('baccount_no',$bankno)
            ->where('company_code',$com)
            ->where('bu_unit',$bu)
            ->whereYear('cv_date',$year)
            ->whereMonth('cv_date',$month)
            ->get();

        return view('accounting.option.monthlyDis',compact('disBS','disBook'));
    }

    /*-----------------------------------------------------------------------------------------
     *Designex Upload
     *-----------------------------------------------------------------------------------------
    */
    public function show()
    {
        return view('accounting.Designex.check_voucher');
    }
	
		
	/*-----------------------------------------------------------------------------------------
	 *Profile Picture
	 *-----------------------------------------------------------------------------------------
	*/
	
	public function profilePic()
	{
		$users = Auth::user();
		return view('profile.profile',compact('users'));
	}
	
	public function changePic(Request $request)
	{
		$this->validate($request, [
			// check validtion for image or file
			'pic' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
		]);
		
		
	echo $getimageName = time().'.'.$request->pic->getClientOriginalExtension();
		$request->pic->move(public_path('img/avatars/'), $getimageName);
		$userID = Auth::user()->user_id;
		User::where('user_id',$userID)->update(['profile_pic'=>'img/avatars/'.$getimageName]);

	}

    /*
     * --------------------------------------------------------------------------------------------------------------
     * CV Middleware
     * --------------------------------------------------------------------------------------------------------------
  */

    public function dataCV()
    {
        return view('CV.upload');
    }

    public function dataCVProcess(Request $request)
    {
        $filepathCV      = $request->file('mainfiles1excel')->getPathName();
        $filepathHead    = $request->file('mainfiles1excel2')->getPathName();

        Excel::load($filepathCV,function($reader){
            $objWorksheet  = $reader->getActiveSheet();
            $highestRow    = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();

            $cvNo      = strtolower($objWorksheet->getCellByColumnAndRow(0,1));
            $lineNo    = strtolower($objWorksheet->getCellByColumnAndRow(1,1));
            $cvStatus  = strtolower($objWorksheet->getCellByColumnAndRow(2,1));
            $checkNo   = strtolower($objWorksheet->getCellByColumnAndRow(3,1));
            $checkAmt  = strtolower($objWorksheet->getCellByColumnAndRow(4,1));
            $bankNo    = strtolower($objWorksheet->getCellByColumnAndRow(5,1));
            $bankName  = strtolower($objWorksheet->getCellByColumnAndRow(6,1));
            $ckDate    = strtolower($objWorksheet->getCellByColumnAndRow(7,1));
            $cleardate = strtolower($objWorksheet->getCellByColumnAndRow(8,1));
            $clearflag = strtolower($objWorksheet->getCellByColumnAndRow(9,1));
            $payee     = strtolower($objWorksheet->getCellByColumnAndRow(10,1));

            if(
                $cvNo      != "cv no." or
                $lineNo    != "line no." or
                $cvStatus  != "cv status" or
                $checkNo   != "check number" or
                $checkAmt  != "check amount" or
                $bankNo    != "bank account no." or
                $bankName  != "bank name" or
                $ckDate    != "check date" or
                $cleardate != "clearing date" or
                $clearflag != "cleared flag" or
                $payee     != "payee"
            ):
                $this->showMessageError("Invalid Check Register File!","Error!",'home');
                die();
            else:
                for($x=2;$x<=$highestRow;$x++)
                {
                    $cvNo    = $objWorksheet->getCellByColumnAndRow(0,$x);
                    $lineNo  = $objWorksheet->getCellByColumnAndRow(1,$x);
                    $cvStatus = $objWorksheet->getCellByColumnAndRow(2,$x);
                    $checkNo  = $objWorksheet->getCellByColumnAndRow(3,$x);
                    $checkAmt = $objWorksheet->getCellByColumnAndRow(4,$x);
                    $bankNo   = $objWorksheet->getCellByColumnAndRow(5,$x);
                    $bankName = $objWorksheet->getCellByColumnAndRow(6,$x);
                    //$ckDate   = date('d/m/Y',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow($objWorksheet->getCellByColumnAndRow(7,$x)->getValue())));
                    $ckDate   = date('d/m/Y',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(7, $x)->getValue()));
                    $clearDate = date('d/m/Y',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(8, $x)->getValue()));
                    $clearflag = $objWorksheet->getCellByColumnAndRow(9,$x);
                    $payee     = $objWorksheet->getCellByColumnAndRow(10,$x);

                    $this->arrayCV[] = $cvNo."|".$lineNo."|".$cvStatus."|".$checkNo."|".$checkAmt."|".$bankNo."|".$bankName."|".$ckDate."|".$clearDate."|".$clearflag."|".$payee;
                }
            endif;

        });

        Excel::load($filepathHead,function($reader){
            $objWorksheet  = $reader->getActiveSheet();
            $highestRow    = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();

            $cvNo    = strtolower($objWorksheet->getCellByColumnAndRow(0,1));
            $cvDate  = strtolower($objWorksheet->getCellByColumnAndRow(1,1));
            if(
                $cvNo != "check voucher no." or
                $cvDate != "cv date"
            ):
                $this->showMessageError("Invalid CV Header File!","Error!",'home');
                die();
            else:

                for($x=2;$x<=$highestRow;$x++)
                {
                    $cvNo    = $objWorksheet->getCellByColumnAndRow(0,$x);
                    //$cvDate  = date('d/m/Y',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow($objWorksheet->getCellByColumnAndRow(1,$x)->getValue())));
                    $cvDate  = date('d/m/Y',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(1, $x)->getValue()));

                    $this->arrayHeader[] = $cvNo."|".$cvDate;
                }
            endif;


        });


        foreach($this->arrayCV as $key => $cv):
            $exp = explode("|",$cv);
            $cvNo    = $exp[0];
            $lineNo  = $exp[1];
            $cvStatus = $exp[2];
            $checkNo  = $exp[3];
            $checkAmt = $exp[4];
            $bankNo   = $exp[5];
            $bankName = $exp[6];
            $ckDate   = $exp[7];
            $clearDate = $exp[8];
            $clearflag = $exp[9];
            $payee     = $exp[10];
            foreach ($this->arrayHeader as $key1 => $head)
            {
                $exp2  = explode("|",$head);
                $cvNo1  = $exp2[0];
                $cvDate = $exp2[1];
                if(trim($cvNo)==trim($cvNo1)):
                    $this->arrayFinal[] = [$cvNo,$lineNo,$cvStatus,$cvDate,$checkNo,$checkAmt,$bankNo,$bankName,$ckDate,$clearDate,$clearflag,$payee];
                endif;
            }
        endforeach;
        //   dd($this->arrayFinal);

        Excel::create('CV For Uploading', function($excel) {

            // Set the title
            $excel->setTitle('CHECK VOUCHER FORMAT');

            // Chain the setters
            $excel->setCreator('BRS PROGRAMMER REY JOSEPH T. BAAY, JELLARY CADUTDUT,GLENN MICHAEL MEJEIAS')->setCompany('Coporate AGC');

            $excel->sheet('Sheet 1', function ($sheet) {

                $sheet->setOrientation('landscape');

                $headings = array(
                    'CV No.',
                    'Line No.',
                    'CV Status',
                    'CV Date',
                    'Check Number',
                    'Check Amount',
                    'Bank Account No.',
                    'Bank Name',
                    'Check Date',
                    'Clearing Date',
                    'Cleared Flag',
                    'Payee');

                $sheet->prependRow(1, $headings);

                for($s=2;$s<=count($this->arrayFinal)+1;$s++)
                {
                    $sheet->setColumnFormat(array(
                        'D'.$s => 'mm/dd/yyyy',
                        'F'.$s => '0.00',
                        'I'.$s => 'mm/dd/yyyy',
                        'J'.$s => 'mm/dd/yyyy',
                    ));

                    $sheet->getStyle('F'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));

                }
                $sheet->fromArray($this->arrayFinal, NULL, 'A2',false,false);

            });

        })->download('xlsx');
    }
	
/*-------------------------------------------------------------------------------------------------------------------------
* For Check Voucher Uploader
*-------------------------------------------------------------------------------------------------------------------------
*/
	public function cvUploader(Request $request)
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
			
				for($y=2;$y<=$highestRow;$y++):
					$cvNo      = $objWorksheet->getCellByColumnAndRow(0,$y)->getValue();
					$lineNo    = $objWorksheet->getCellByColumnAndRow(1,$y)->getValue();
					$cvStatus  = $objWorksheet->getCellByColumnAndRow(2,$y)->getValue();
					$cvDate    = date("n/d/Y",PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(3,$y)->getValue()));
					$checkNo   = $objWorksheet->getCellByColumnAndRow(4,$y)->getValue();
					$checkAmt  = $objWorksheet->getCellByColumnAndRow(5,$y)->getValue();
					$bankNo    = $objWorksheet->getCellByColumnAndRow(6,$y)->getValue();
					$bankName  = $objWorksheet->getCellByColumnAndRow(7,$y)->getValue();
					$checkDate = date("n/d/Y",PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(8,$y)->getValue()));
					$clearDate = date("n/d/Y",PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(9,$y)->getValue()));
					$clearflag = $objWorksheet->getCellByColumnAndRow(10,$y)->getValue();
					$payee     = $objWorksheet->getCellByColumnAndRow(11,$y)->getValue();
					if($cvNo!='' and $cvNo!=null)
					{
						$this->excelData[] = [$cvNo,$lineNo,$cvStatus,$cvDate,$checkNo,$checkAmt,$bankNo,$bankName,$checkDate,$clearDate,$clearflag,$payee];
						$this->dateData[]  = $cvDate;
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
			$cvNo      = $data[0];
			$lineNo    = $data[1];
			$cvStatus  = $data[2];
				$PHPDateValue = strtotime($data[3]);
			$cvDate = PHPExcel_Shared_Date::PHPToExcel($PHPDateValue);
			$checkNo   = $data[4];
			$checkAmt  = $data[5];
			$bankNo    = $data[6];
			$bankName  = $data[7];
				$dateCheck = strtotime($data[8]);
			$checkDate = PHPExcel_Shared_Date::PHPToExcel($dateCheck);
				$flagClear = strtotime($data[9]);
			$clearDate = PHPExcel_Shared_Date::PHPToExcel($flagClear);
			$clearflag = $data[10];
			$payee     = $data[11];
			$yearIn    = date("Y",strtotime($data[3]));
			$monthIn   = date("m",strtotime($data[3]));
			foreach(array_unique($yearArray) as $key2 => $year)
			{
				if($monthIn == $monthArray[0] and $yearIn == $year):
					$jan[] = [$cvNo,$lineNo,$cvStatus,$cvDate,$checkNo,$checkAmt,$bankNo,$bankName,$checkDate,$clearDate,$clearflag,$payee];
				elseif($monthIn == $monthArray[1] and $yearIn == $year):
					$feb[] = [$cvNo,$lineNo,$cvStatus,$cvDate,$checkNo,$checkAmt,$bankNo,$bankName,$checkDate,$clearDate,$clearflag,$payee];
				elseif($monthIn == $monthArray[2] and $yearIn == $year):
					$mar[] = [$cvNo,$lineNo,$cvStatus,$cvDate,$checkNo,$checkAmt,$bankNo,$bankName,$checkDate,$clearDate,$clearflag,$payee];
				elseif($monthIn == $monthArray[3] and $yearIn == $year):
					$apr[] = [$cvNo,$lineNo,$cvStatus,$cvDate,$checkNo,$checkAmt,$bankNo,$bankName,$checkDate,$clearDate,$clearflag,$payee];
				elseif($monthIn == $monthArray[4] and $yearIn == $year):
					$may[] = [$cvNo,$lineNo,$cvStatus,$cvDate,$checkNo,$checkAmt,$bankNo,$bankName,$checkDate,$clearDate,$clearflag,$payee];
				elseif($monthIn == $monthArray[5] and $yearIn == $year):
					$jun[] = [$cvNo,$lineNo,$cvStatus,$cvDate,$checkNo,$checkAmt,$bankNo,$bankName,$checkDate,$clearDate,$clearflag,$payee];
				elseif($monthIn == $monthArray[6] and $yearIn == $year):
					$jul[] = [$cvNo,$lineNo,$cvStatus,$cvDate,$checkNo,$checkAmt,$bankNo,$bankName,$checkDate,$clearDate,$clearflag,$payee];
				elseif($monthIn == $monthArray[7] and $yearIn == $year):
					$aug[] = [$cvNo,$lineNo,$cvStatus,$cvDate,$checkNo,$checkAmt,$bankNo,$bankName,$checkDate,$clearDate,$clearflag,$payee];
				elseif($monthIn == $monthArray[8] and $yearIn == $year):
					$sep[] = [$cvNo,$lineNo,$cvStatus,$cvDate,$checkNo,$checkAmt,$bankNo,$bankName,$checkDate,$clearDate,$clearflag,$payee];
				elseif($monthIn == $monthArray[9] and $yearIn == $year):
					$oct[] = [$cvNo,$lineNo,$cvStatus,$cvDate,$checkNo,$checkAmt,$bankNo,$bankName,$checkDate,$clearDate,$clearflag,$payee];
				elseif($monthIn == $monthArray[10] and $yearIn == $year):
					$nov[] = [$cvNo,$lineNo,$cvStatus,$cvDate,$checkNo,$checkAmt,$bankNo,$bankName,$checkDate,$clearDate,$clearflag,$payee];
				elseif($monthIn == $monthArray[11] and $yearIn == $year):
					$dec[] = [$cvNo,$lineNo,$cvStatus,$cvDate,$checkNo,$checkAmt,$bankNo,$bankName,$checkDate,$clearDate,$clearflag,$payee];
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
		
		$path = storage_path("exports\sample-excel\\$com\\$bu");
		File::makeDirectory($path, 0777, true, true);
		
		for($x=1;$x<=12;$x++)
		{
			if($x==1 and count($jan)!=0):
				$title = "CV for January $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($jan,$title,$com,$bu);
			elseif($x==2 and count($feb)!=0):
				$title = "CV for February $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($feb,$title,$com,$bu);
			elseif($x==3 and count($mar)!=0):
				$title = "CV for March $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($mar,$title,$com,$bu);
			elseif($x==4 and count($apr)!=0):
				$title = "CV for April $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($apr,$title,$com,$bu);
			elseif($x==5  and count($may)!=0):
				$title = "CV for May $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($may,$title,$com,$bu);
			elseif($x==6 and count($jun)!=0):
				$title = "CV for June $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($jun,$title,$com,$bu);
			elseif($x==7 and count($jul)!=0):
				$title = "CV for July $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($jul,$title,$com,$bu);
			elseif($x==8 and count($aug)!=0):
				$title = "CV for August $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($aug,$title,$com,$bu);
			elseif($x==9 and count($sep)!=0):
				$title = "CV for September $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($sep,$title,$com,$bu);
			elseif($x==10 and count($oct)!=0):
				$title = "CV for October $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($oct,$title,$com,$bu);
			elseif($x==11 and count($nov)!=0):
				$title = "CV for November $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($nov,$title,$com,$bu);
			elseif($x==12 and count($dec)!=0):
				$title = "CV for December $yearOf - $bankAct->BankAccountList - $bank_no";
				$this->ExcelSaving($dec,$title,$com,$bu);
			endif;
		}
		
		echo "Success";
		return redirect('home');

	}
	
	public function ExcelSaving($data,$title,$com,$bu)
	{
		Excel::create("sample-excel\\$com\\$bu/$title",function($excel)use($data){
			
			// Set the title
			$excel->setTitle('CHECK VOUCHER FORMAT');
			
			// Chain the setters
			$excel->setCreator('BRS PROGRAMMER REY JOSEPH T. BAAY, JELLARY CADUTDUT,GLENN MICHAEL MEJEIAS')->setCompany('Coporate AGC');
			
			$excel->sheet('Sheet 1', function ($sheet)use($data) {
				
				$sheet->setOrientation('landscape');
				
				$headings = array(
					'CV No.',
					'Line No.',
					'CV Status',
					'CV Date',
					'Check Number',
					'Check Amount',
					'Bank Account No.',
					'Bank Name',
					'Check Date',
					'Clearing Date',
					'Cleared Flag',
					'Payee');
				$count = count($data);
				for($row=2;$row<=$count;$row++)
				{
					$sheet->getStyleByColumnAndRow(3, $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyleByColumnAndRow(8, $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyleByColumnAndRow(9, $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
				}
				$sheet->prependRow(1, $headings);
				$sheet->fromArray($data, NULL, 'A2',false,false);
				
				for($row=2;$row<=$count;$row++)
				{
					$sheet->getStyleByColumnAndRow(3, $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyleByColumnAndRow(8, $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
					$sheet->getStyleByColumnAndRow(9, $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
				}
				
			});
		})->save('xlsx');
	}
	
/*-------------------------------------------------------------------------------------------------------------------------
* Uploader CV Excel Uploaded Viewing
*-------------------------------------------------------------------------------------------------------------------------
*/
	public function CVperCom()
	{
		$com = Company::select('company_code','company','acroname')->get();
		
		return view('CV.CheckVoucher.CVperCompany',compact('com'));
	}
	
	public function BUlist($comID)
	{
		$arrayID    = Array();
		$arrayCount = Array();
		$bu = Businessunit::select('unitid','bname','company_code')->where('company_code',$comID)->get();
		foreach($bu as $BU)
		{
			$buID  = $BU->unitid;
			$bname = $BU->bname;
			$bs    = BankStatement::where('company',$comID)->where('bu_unit',$buID)->distinct()->get(['bank_account_no as bankno']);
			$count = $bs->count('bank_id');
			
			$arrayCount[] = $count;
			if($count > 0)
			{
				foreach ($bs as $BS)
				{
					$bankno = $BS->bankno;
					$bankNO1    = BankNo::select('id','bankno')->where('bankno',$bankno)->get();
					foreach($bankNO1 as $bk)
					{
						$arrayID[] = $bk->id;
					}
					
				}
			}
			else
			{
				$arrayID[] = 0;
			}
		}
		
		return view('CV.CheckVoucher.BUlist',compact('bu','arrayCount','arrayID','comID'));
	}
	
	public function BankList($data)
	{
		$exp    = explode(csrf_token()."?",base64_decode($data));
		$res    = explode("/",$exp[0]);
		$bu     = $res[0];
		$com    = $res[1];
		$bankID = $res[2];
		
		$arrayAct = Array();
		
		$bs = BankStatement::where('company',$com)->where('bu_unit',$bu)->distinct()->get(['bank_account_no as bankno']);
		foreach ($bs as $b)
		{
			$bankno = $b->bankno;
			$bank   = BankNo::where('bankno',$bankno)->get();
			foreach ($bank as $bno)
			{
				$bnoID = $bno->id;
			}
			
			$bAct = BankAccount::select('id','bank','accountno','accountname')
				->where('company_code',$com)
				->where('buid',$bu)
				->where('bankno',$bnoID)
				->get();
			foreach ($bAct as $bA)
			{
				$arrayAct[] = $bA->bank . "|" .$bA->accountno . "|" .$bA->accountname."|".$bnoID;
			}
		}
		return view('CV.CheckVoucher.bankList',compact('arrayAct','com','bu','bankID'));
		
	}
	
	public function cvList($data)
	{
		$banklist = Array();
		$exp    = explode(csrf_token(),base64_decode($data));
		$res    = explode("/",$exp[0]);
		$bu     = $res[0];
		$com    = $res[1];
		$bu1    = Businessunit::find($res[0])->bname;
		
		$com1   = Company::find($res[1])->company;
		$bId    = $res[2];
		
		$bankID = $res[2];
		$bAcct  = BankAccount::select('bank','accountname','accountno')
			->where('bankno',$bankID)
			->where('company_code',$com)
			->where('buid',$bu)
			->get();
		foreach($bAcct as $key => $b)
		{
			$bName = $b->bank;
			$bNum  = $b->accountno;
		}
		$bankNO = BankNo::find($res[2])->bankno;
		
		$path   = storage_path("exports/sample-excel/$com1/$bu1");
		$month  = Array();
		$file   = Array();
		$list   = glob("$path/*.xlsx");
		
		foreach($list as $li):
			$exp = explode("/",$li);
			$file[] = $exp[4];
		endforeach;
		
		$pathDep   = storage_path("exports/deposit-excel/$com1/$bu1");
		
		$fileDep   = Array();
		$listDep   = glob("$pathDep/*.xlsx");
		
		foreach($listDep as $liDep)
		{
			$exp1 = explode("/",$liDep);
			$fileDep[] = $exp1[4];
		}
		
		return view('CV.CheckVoucher.fileList',compact('list','file','listDep','fileDep','com','bu','data','bankID','bId','bName','bNum'));
		
	}
	
	public function viewExcel($file)
	{
//		$files = Storage::files('exports/sample-excel/');
//		echo $files;
		$arrayExcel = Array();
		Excel::load(base64_decode($file),function($reader){
			$objWorksheet = $reader->getActiveSheet();
			$highestRow   = $objWorksheet->getHighestRow();
			
			
			for($y=2;$y<=$highestRow;$y++):
				$cvNo      = $objWorksheet->getCellByColumnAndRow(0,$y)->getValue();
				$lineNo    = $objWorksheet->getCellByColumnAndRow(1,$y)->getValue();
				$cvStatus  = $objWorksheet->getCellByColumnAndRow(2,$y)->getValue();
				$cvDate    = date("n/d/Y",PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(3,$y)->getValue()));
				$checkNo   = $objWorksheet->getCellByColumnAndRow(4,$y)->getValue();
				$checkAmt  = $objWorksheet->getCellByColumnAndRow(5,$y)->getValue();
				$bankNo    = $objWorksheet->getCellByColumnAndRow(6,$y)->getValue();
				$bankName  = $objWorksheet->getCellByColumnAndRow(7,$y)->getValue();
				$checkDate = date("n/d/Y",PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(8,$y)->getValue()));
				$clearDate = date("n/d/Y",PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(9,$y)->getValue()));
				$clearflag = $objWorksheet->getCellByColumnAndRow(10,$y)->getValue();
				$payee     = $objWorksheet->getCellByColumnAndRow(11,$y)->getValue();
				$this->viewExcelData[] = [$cvNo,$lineNo,$cvStatus,$cvDate,$checkNo,$checkAmt,$bankNo,$bankName,$checkDate,$clearDate,$clearflag,$payee];
			endfor;
		});
		
		$excelData = $this->viewExcelData;
		
		return view('CV.CheckVoucher.viewCVUploaded',compact('excelData'));
	}
	
/*-------------------------------------------------------------------------------------------------------------------------
* Accounting CV Uploaded Viewing
*-------------------------------------------------------------------------------------------------------------------------
*/
	
	public function viewExcelDep($file)
	{
//		$files = Storage::files('exports/sample-excel/');
//		echo $files;
		$arrayExcel = Array();
		Excel::load(base64_decode($file),function($reader){
			$objWorksheet = $reader->getActiveSheet();
			$highestRow   = $objWorksheet->getHighestRow();
			
			
			for($y=2;$y<=$highestRow;$y++):
				$entryno     = $objWorksheet->getCellByColumnAndRow(0,$y)->getValue();
				$bankno      = $objWorksheet->getCellByColumnAndRow(1,$y)->getValue();
				$postingdate = date('m/d/Y',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(2, $y)->getValue()));
				$docType     = $objWorksheet->getCellByColumnAndRow(3,$y)->getValue();
				$docno       = $objWorksheet->getCellByColumnAndRow(4,$y)->getValue();
				$extdocno    = $objWorksheet->getCellByColumnAndRow(5,$y)->getValue();
				$des         = $objWorksheet->getCellByColumnAndRow(6,$y)->getValue();
				$userID      = $objWorksheet->getCellByColumnAndRow(7,$y)->getValue();
				$amount      = $objWorksheet->getCellByColumnAndRow(8,$y)->getValue();
				$this->viewExcelData[] = [
					$entryno,
					$bankno,
					$postingdate,
					$docType,
					$docno,
					$extdocno,
					$des,
					$userID,
					$amount
				];
			endfor;
		});
		
		$excelData = $this->viewExcelData;
		
		return view('deposit.viewDSUploaded',compact('excelData'));
	}

	
	public function acctBankList($com,$bu)
	{
		$arrayAct = Array();
		
		$bs = BankStatement::where('company',$com)->where('bu_unit',$bu)->distinct()->get(['bank_account_no as bankno']);
		foreach ($bs as $b)
		{
			$bankno = $b->bankno;
			$bank   = BankNo::where('bankno',$bankno)->get();
			foreach ($bank as $bno)
			{
				$bnoID = $bno->id;
			}
			
			$bAct = BankAccount::select('id','bank','accountno','accountname')
				->where('company_code',$com)
				->where('buid',$bu)
				->where('bankno',$bnoID)
				->get();
			foreach ($bAct as $bA)
			{
				$arrayAct[] = $bA->bank . "|" .$bA->accountno . "|" .$bA->accountname."|".$bnoID;
			}
		}
		
		return view('accounting.CV.bankList',compact('arrayAct','com','bu'));
		
	}
	
	public function acctCVList($data)
	{
		$banklist = Array();
		$exp    = explode(csrf_token(),base64_decode($data));
		$res    = explode("/",$exp[0]);
		$bu     = $res[0];
		$com    = $res[1];
		$bu1    = Businessunit::find($res[0])->bname;
		
		$com1   = Company::find($res[1])->company;
		$bId    = $res[2];
		
		$bankID = $res[2];
		$bAcct  = BankAccount::select('bank','accountname','accountno')
			->where('bankno',$bankID)
			->where('company_code',$com)
			->where('buid',$bu)
			->get();
		foreach($bAcct as $key => $b)
		{
			$bName = $b->bank;
			$bNum  = $b->accountno;
		}
		$bankNO = BankNo::find($res[2])->bankno;
		
		$path   = storage_path("exports/sample-excel/$com1/$bu1");
		$month  = Array();
		$file   = Array();
		$list   = glob("$path/*.xlsx");
		
		foreach($list as $li):
			$exp = explode("/",$li);
			$file[] = $exp[4];
		endforeach;
		return view('accounting.CV.fileList',compact('list','file','com','bu','data','bankID','bId','bName','bNum'));
		
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
