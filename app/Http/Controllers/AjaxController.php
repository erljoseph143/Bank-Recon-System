<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\BankNo;
use App\BankStatement;
use App\Businessunit;
use App\Company;
use App\PdcLine;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Cell;

class AjaxController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }
	

    public function dashboard(Request $request)
    {
        if($request->ajax())
        {
            return view('rms.dashboard');
        }

    }
    public function dashboardAcct(Request $request)
    {
        if($request->ajax())
        {
            return view('accounting.dashboard');
        }

    }

    public function CheckVoucher(Request $request)
    {
        if($request->ajax())
        {
            return view('accounting.check_voucher');
        }

    }
	
    public function company(Request $request)
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
            return view('rms.bank_statement',compact('com','yearOf'));
        }


    }
	
	public function companyUploader(Request $request)
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
			return view('CV.CV',compact('com','yearOf'));
		}
		
		
	}

    public function company_bu($id)
    {

        //$company = Company::where('company_code',1)->get();
//dd($company->all());
        $bu = Businessunit::where('company_code',$id)->get();
        foreach($bu as $com)
        {

               echo $com->bname . "</br>";

        }
    }

    public function loadBu($id, Request $request)
    {
        if($request->ajax())
        {
            $bu = Businessunit::where('company_code',$id)->pluck('bname','unitid')->all();
            //dd($bu);
            return view('rms.bu_unit',compact('bu'));
        }
    }

    public function bankAct($comid, $buid, Request $request)
    {

//        $bankact = BankAccount::where('buid',$buid)->get();
//         $new =   $bankact->pluck('BankAccountList','id')->all();
//          dd($new);
        if($request->ajax())
        {
            $new = BankAccount::where('buid',$buid)->where('company_code',$comid)->get();
            $bankact =   $new->pluck('BankAccountList','id')->all();
            return view('rms.bank_act_list',compact('bankact'));
        }
    }


    public function removeDir(Request $request)
    {
       if($request->ajax())
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

           $files = glob("functions/tempuploads/$localIP/*"); // get all file names
           foreach($files as $file){ // iterate files
               echo $file;
               if(is_file($file))
                   unlink($file); // delete file
           }

           rmdir("functions/tempuploads/$localIP");
       }

    }

    public function readExcel($filename,$col,$row,Request $request)
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



        Excel::load("functions/tempuploads/".$localIP."/".$filename,function($reader)use($col,$row){
            $objWorksheet   = $reader->getActiveSheet();


            $highestRow    = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
           // $highestColumnIndex = $highestColumn;
            $error_format = $col;
            if($error_format !='Error')
            {
                $row_format = 0;
            }
            else
            {
                $row_format = $row;
            }

            if($row_format == 0)
            {
                $class_tr ="";
                $color  = "";
            }
            else
            {
                $class_tr ="red";
                $color = 'white';
            }
            
            echo $col;
//----end error bank format---//

            echo '<table border="1" style="width:100%">' ;
            echo "<tr><th></th><th style='text-align:center'>A</th><th style='text-align:center'>B</th><th style='text-align:center'>C</th><th style='text-align:center'>D</th><th style='text-align:center'>E</th><th style='text-align:center'>F</th><th style='text-align:center'>G</th></tr>";
            for ($row = 1; $row <= $highestRow; ++$row) {
                if($row_format == $row)
                {
                    echo '<tr style="background-color:'.$class_tr.';color:'.$color.'">';
                }
                else
                {
                    //echo "Error";
                    echo '<tr>' ;
                }
                echo '<td>'.$row.'</td>';

                for ($col = 0; $col <= $highestColumnIndex; ++$col) {


                    echo '<td style=""><font size="2.5">&nbsp;' . $objWorksheet->getCellByColumnAndRow($col, $row)->getFormattedValue() . '</font></td>';

                }

                echo '</tr>';
            }
            echo '</table>';


        });
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

                $ar2[] = '  |  |  |  |  ';
            }
            $array = Array();
            foreach($ar1 as $key => $a)
            {
                $exp     = explode("|",$a);
                $exp1    = explode("|",$ar2[$key]);
                $array[] = [$exp[0],$exp[1],$exp[2],$exp[3],$exp[4],$exp1[0],$exp1[1],$exp1[2],$exp1[3],$exp1[4]];
            }
           // dd($array);
            return $array;
        }
        elseif($count1 < $count2)
        {
            $diff = $count2 - $count1;
            for($x=1;$x<=$diff;$x++)
            {
                $ar1[] = '  |  |  |  |  ';
            }

            $array = Array();
            foreach($ar2 as $key => $a)
            {
                $exp1   = explode("|",$a);
                $exp    = explode("|",$ar1[$key]);
                $array[] = [$exp[0],$exp[1],$exp[2],$exp[3],$exp[4],$exp1[0],$exp1[1],$exp1[2],$exp1[3],$exp1[4]];
            }
           // dd($array);
            return $array;

        }
        else
        {
            $array = Array();
            foreach($ar1 as $key => $a)
            {
                $array[] = array_merge($a, $ar2[$key]);
            }
            return $array;
        }


    }

    public function viewUpBS()
    {
        $com  = Auth::user()->company_id;
        $bu   = Auth::user()->bunitid;
        $acct = BankAccount::where('company_code',$com)
        ->where('buid',$bu)
        ->get();
        $arrayAct = Array();
        foreach ($acct as $ac)
        {
             $bankno = BankNo::find($ac->bankno);
             $bankno->bankno;
             $bs     = BankStatement::select(DB::raw('max(bank_date) as maxdate'),DB::raw('min(bank_date) as mindate'))
                ->where('bank_account_no',$bankno->bankno)
                ->where('company',$com)
                ->where('bu_unit',$bu)
                ->get();
            foreach($bs as $b)
            {
                if($b->mindate=="")
                {
                    $records ="No records found";
                }
                else
                {
                    $records = date("F d, Y",strtotime($b->mindate)) . " to " .date("F d, Y",strtotime($b->maxdate));
                }
                $arrayAct[] = Array($ac->bank,$ac->accountno,$ac->accountname,$records,$bankno->bankno);

            }
          //  dd($bs);
        }

        return view('accounting.viewUploadedBS',compact('arrayAct'));
    }

    public function BSmonthly($data)
    {
//        base64_encode(Auth::user()->company_id."/".Auth::user()->bunitid."/".$act[4])
//base64_encode(Auth::user()->company_id."/".Auth::user()->bunitid."/".$act[4]) .csrf_token()."?".base64_encode("BRS")
        $banklist = Array();

        $type = "acctg-View-BS";
        $exp = explode(csrf_token(),$data);
         base64_decode($exp[0]);
        $exp = explode("/",base64_decode($exp[0]));
        $com = $exp[0];
        $bu  = $exp[1];
        $bankno = $exp[2];

        $bank = BankStatement::where('company',$com)
            ->where('bu_unit',$bu)
            ->where('bank_account_no',$bankno);
        $data     = Array();
        if($bank->count('bank_id') > 0)
        {
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
            // dd($banklist);
        }
        return view('reports.month_bank',compact('data','banklist','bankno','com','bu','type','bankID'));

    }

    public function showMonthlyBS_ACCTG($data)
    {
        $exp       = explode(csrf_token(),$data);
        $res       = explode("/",base64_decode($exp[0]));
        $bu        = $res[3];
        $com       = $res[2];

        $bankno    = $res[1];
        $bankdate  = $res[0];
        $year      = date("Y",strtotime($res[0]));
        $month     = date("m",strtotime($res[0]));
        $bankName  = $res[4];
        $acctno    = $res[5];

        $bank = BankStatement::select('bank_id','bank_date','bank_check_no','bank_amount','bank_balance','type','description','bank_account_no')
            ->where('company',$com)
            ->where('bu_unit',$bu)
            ->whereYear('bank_date',$year)
            ->whereMonth('bank_date',$month)
            ->where('bank_account_no',$bankno)
            ->get();


        return view('accounting.BSperMonth',compact('bank','bu','com','bankno','bankdate','bankName','acctno'));

    }
	
	    public function getBankNo($id)
    {
        $bank = BankAccount::find($id);
        $bno  = BankNo::find($bank->bankno);
        return $bno->bankno;
    }
    
    public function getresult(Request $request)
    {
       // dd($request->data);
	   //[table,com,bu,bankno,checkno,checkamt];
	    $data          = $request->data;
	    $com           = $data[1];
	    $bu            = $data[2];
	    $bankno        = $data[3];
	    $checkno       = $data[4];
	    $checkamt      = $data[5];
	    $fieldcheck    = "";
	    $fieldcheckamt = "";
	    $bankaccount   = "";
	    if($data[0]=='bank_statement')
	    {
		    $fieldcheck    = "bank_check_no";
		    $fieldcheckamt = "bank_amount";
		    $bankaccount   = "bank_account_no";
		    $table         = BankStatement::select(DB::raw("DATE_FORMAT(bank_date,'%m/%d/%Y') as bankdate"),'description','bank_check_no',DB::raw('FORMAT(bank_amount,2) as bankamount'),DB::raw('FORMAT(bank_balance,2) as bankbalance'),'label_match')
		                    ->where('company',$com)
			                ->where('bu_unit',$bu)
			                ->where('type','AP')
			                ->where('bank_account_no',$bankno);
		    $header        = ['Bank Date','Description','Bank Check No','Bank Amount','Bank Balance','Matching Status'];
		}
		elseif($data[0]=='pdc_line')
		{
			$fieldcheck    = "check_no";
			$fieldcheckamt = "check_amount";
			$bankaccount   = "baccount_no";
			$table         = PdcLine::select('cv_no',DB::raw("DATE_FORMAT(cv_date,'%m/%d/%Y') as cvdate"),DB::raw("DATE_FORMAT(check_date,'%m/%d/%Y') as checkdate"),'check_no',DB::raw('FORMAT(check_amount,2) as checkamount'),'status','payee','label_match')
							->where('company',$com)
							->where('bu_unit',$bu)
							->where('baccount_no',$bankno);
			$header        = ['CV No','CV Date','Check Date','Check No','Check Amount','Status','Payee','Matching Status'];
		}
		
		if($checkno!='' and $checkamt=='')
		{
			$result = $table->where($fieldcheck,$checkno)->get()->toArray();
		}
		elseif($checkno=='' and $checkamt!='')
		{
			$result = $table->where($fieldcheckamt,$checkamt)->get()->toArray();
		}
		elseif($checkno!='' and $checkamt!='')
		{
			$result = $table->where($fieldcheck,$checkno)->where($fieldcheckamt,$checkamt)->get()->toArray();
		}
		else
		{
			$result = $table->get()->toArray();
		}
		
		return view('data.result',compact('result','header'));
		
    }

}
