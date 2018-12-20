<?php

namespace App\Http\Controllers\Disbursement;

use App\BankAccount;
use App\BankNo;
use App\BankStatement;
use App\PdcLine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DisbursementController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function matchCheck(Request $request)
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
            return view('accounting.Disbursement.monthlyCheck',compact('banklist'));
        }
    }

    public function MonthlyCheck(Request $request,$bankno,$type)
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
                   // ->where('label_match','match check')
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
                 //dd($banklist);
            }
            return view('reports.month_bank',compact('data','banklist','bankno','com','bu','type'));
        }
    }

    public function showMacthCheck(Request $request,$data)
    {
        $ex = explode(csrf_token()."?",$data);
        $url = base64_decode($ex[0]);
        $exp = explode("/",$url);

        $bankno = $exp[1];
        $year   = date("Y",strtotime($exp[0]));
        $month  = date("m",strtotime($exp[0]));
        $com    = $exp[2];
        $bu     = $exp[3];

        $disBS  = BankStatement::select('bank_date','bank_account_no','bank_check_no','bank_amount','bank_balance','type','description','label_match')
            ->where('bank_account_no',$bankno)
            ->where('company',$com)
            ->where('bu_unit',$bu)
            ->whereYear('bank_date',$year)
            ->whereMonth('bank_date',$month)
            ->where('type','AP')
            ->where('label_match','match check')
            ->get();
        $disBook = PdcLine::select('cv_date','baccount_no','check_no','check_amount','check_date','cv_date','payee','cv_no','label_match')
            ->where('baccount_no',$bankno)
            ->where('company_code',$com)
            ->where('bu_unit',$bu)
            ->whereYear('cv_date',$year)
            ->whereMonth('cv_date',$month)
            ->where('label_match','match check')
            ->get();

        return view('accounting.Disbursement.matchCheck',compact('disBS','disBook'));
    }

    public function unMatchCheck(Request $request)
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
            }return view('accounting.Disbursement.monthlyUnMatched',compact('banklist'));
        }
    }

    public function monthlyUnmatch(Request $request,$bankno,$type)
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
                    // ->where('label_match','match check')
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
            return view('reports.month_bank',compact('data','banklist','bankno','com','bu','type'));
        }
    }

    public function showMonthlyUnmatched(Request $request, $data)
    {
        $ex = explode(csrf_token()."?",$data);
        $url = base64_decode($ex[0]);
        $exp = explode("/",$url);

        $bankno = $exp[1];
        $year   = date("Y",strtotime($exp[0]));
        $month  = date("m",strtotime($exp[0]));
        $com    = $exp[2];
        $bu     = $exp[3];

        $disBS  = BankStatement::select('bank_date','bank_account_no','bank_check_no','bank_amount','bank_balance','type','description','label_match')
            ->where('bank_account_no',$bankno)
            ->where('company',$com)
            ->where('bu_unit',$bu)
            ->whereYear('bank_date',$year)
            ->whereMonth('bank_date',$month)
            ->where('type','AP')
            ->where('label_match','')
            ->get();
        $disBook = PdcLine::select('cv_date','baccount_no','check_no','check_amount','check_date','cv_date','payee','cv_no','label_match')
            ->where('baccount_no',$bankno)
            ->where('company_code',$com)
            ->where('bu_unit',$bu)
            ->whereYear('cv_date',$year)
            ->whereMonth('cv_date',$month)
            ->where('label_match','')
            ->get();

        return view('accounting.Disbursement.unmatchCheck',compact('disBS','disBook'));
    }

    public function ocCheck(Request $request)
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
            return view('accounting.Disbursement.monthlyOC',compact('banklist'));
        }
    }

    public function monthlyOC(Request $request,$bankno,$type)
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
                    // ->where('label_match','match check')
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
            return view('reports.month_bank',compact('data','banklist','bankno','com','bu','type'));
        }
    }

    public function showMonthlyOC(Request $request, $data)
    {
        $ex = explode(csrf_token()."?",$data);
        $url = base64_decode($ex[0]);
        $exp = explode("/",$url);

        $bankno = $exp[1];
        $year   = date("Y",strtotime($exp[0]));
        $month  = date("m",strtotime($exp[0]));
        $com    = $exp[2];
        $bu     = $exp[3];


        $disBook = PdcLine::select('cv_date','baccount_no','check_no','check_amount','check_date','cv_date','payee','cv_no','label_match')
            ->where('baccount_no',$bankno)
            ->where('company_code',$com)
            ->where('bu_unit',$bu)
            ->whereYear('cv_date',$year)
            ->whereMonth('cv_date',$month)
            ->where('status','OC')
            ->get();

        return view('accounting.Disbursement.OCCheck',compact('disBook'));
    }
    public function dmCheck(Request $request)
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
            return view('accounting.Disbursement.monthlyDM',compact('banklist'));
        }
    }

    public function monthlyDM(Request $request,$bankno,$type)
    {
        $banklist = Array();
        $com      = Auth::user()->company_id;
        $bu       = Auth::user()->bunitid;

//        if($request->ajax())
//        {

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
                    // ->where('label_match','match check')
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
            return view('reports.month_bank',compact('data','banklist','bankno','com','bu','type'));
      //  }
    }
    public function showMonthlyDM(Request $request, $data)
    {
        $ex = explode(csrf_token()."?",$data);
        $url = base64_decode($ex[0]);
        $exp = explode("/",$url);

        $bankno = $exp[1];
        $year   = date("Y",strtotime($exp[0]));
        $month  = date("m",strtotime($exp[0]));
        $com    = $exp[2];
        $bu     = $exp[3];


        $disBS  = BankStatement::select('bank_date','bank_account_no','bank_check_no','bank_amount','bank_balance','type','description','label_match','debit_memos')
            ->where('bank_account_no',$bankno)
            ->where('company',$com)
            ->where('bu_unit',$bu)
            ->whereYear('bank_date',$year)
            ->whereMonth('bank_date',$month)
            ->where('type','AP')
            ->where('debit_memos','debit memos')
            ->get();

        return view('accounting.Disbursement.DMCheck',compact('disBS'));
    }
    public function PDC_DMCheck(Request $request)
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
            return view('accounting.Disbursement.monthlyPDC_DC',compact('banklist'));
        }
    }
    public function monthlyPDC_DC(Request $request,$bankno,$type)
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
                    // ->where('label_match','match check')
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
            return view('reports.month_bank',compact('data','banklist','bankno','com','bu','type'));
        }
    }

    public function showMonthlyPDC_DC(Request $request, $data)
    {
        $ex = explode(csrf_token()."?",$data);
        $url = base64_decode($ex[0]);
        $exp = explode("/",$url);

        $bankno = $exp[1];
        $year   = date("Y",strtotime($exp[0]));
        $month  = date("m",strtotime($exp[0]));
        $com    = $exp[2];
        $bu     = $exp[3];


        $disBook = PdcLine::select('cv_date','baccount_no','check_no','check_amount','check_date','cv_date','payee','cv_no','label_match')
            ->where('baccount_no',$bankno)
            ->where('company_code',$com)
            ->where('bu_unit',$bu)
            ->whereYear('cv_date',$year)
            ->whereMonth('cv_date',$month)
            ->get();

        return view('accounting.Disbursement.PDC_DCCheck',compact('disBook'));
    }
    public function CancelledCheck(Request $request)
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
            return view('accounting.Disbursement.monthlyCancelled',compact('banklist'));
        }
    }

    public function monthlyCancellded(Request $request,$bankno,$type)
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
                    // ->where('label_match','match check')
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
            return view('reports.month_bank',compact('data','banklist','bankno','com','bu','type'));
        }
    }

    public function showMonthlyCancelled(Request $request, $data)
    {
        $ex = explode(csrf_token()."?",$data);
        $url = base64_decode($ex[0]);
        $exp = explode("/",$url);

        $bankno = $exp[1];
        $year   = date("Y",strtotime($exp[0]));
        $month  = date("m",strtotime($exp[0]));
        $com    = $exp[2];
        $bu     = $exp[3];


        $disBook = PdcLine::select('cv_date','cv_status','baccount_no','check_no','check_amount','check_date','cv_date','payee','cv_no','label_match')
            ->where('baccount_no',$bankno)
            ->where('company_code',$com)
            ->where('bu_unit',$bu)
            ->whereYear('cv_date',$year)
            ->whereMonth('cv_date',$month)
            ->where('cv_status','Cancelled')
            ->get();

        return view('accounting.Disbursement.CancelledCheck',compact('disBook'));
    }

    public function PostedCheck(Request $request)
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
            return view('accounting.Disbursement.monthlyPosted',compact('banklist'));
        }
    }

    public function monthlyPosted(Request $request,$bankno,$type)
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
                    // ->where('label_match','match check')
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
                return view('reports.month_bank',compact('data','banklist','bankno','com','bu','type'));
            }
        }
    }

    public function showMonthlyPosted(Request $request, $data)
    {
        $ex = explode(csrf_token()."?",$data);
        $url = base64_decode($ex[0]);
        $exp = explode("/",$url);

        $bankno = $exp[1];
        $year   = date("Y",strtotime($exp[0]));
        $month  = date("m",strtotime($exp[0]));
        $com    = $exp[2];
        $bu     = $exp[3];


        $disBook = PdcLine::select('cv_date','cv_status','baccount_no','check_no','check_amount','check_date','cv_date','payee','cv_no','label_match')
            ->where('baccount_no',$bankno)
            ->where('company_code',$com)
            ->where('bu_unit',$bu)
            ->whereYear('cv_date',$year)
            ->whereMonth('cv_date',$month)
            ->where('cv_status','Posted')
            ->get();

        return view('accounting.Disbursement.PostedCheck',compact('disBook'));
    }

    public function staleCheck(Request $request)
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
            return view('accounting.Disbursement.monthlyStale',compact('banklist'));
        }
    }

    public function monthlyStale(Request $request,$bankno,$type)
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
                    // ->where('label_match','match check')
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
                return view('reports.month_bank',compact('data','banklist','bankno','com','bu','type'));
            }
        }
    }

    public function showMonthlyStale(Request $request, $data)
    {
        $ex = explode(csrf_token()."?",$data);
        $url = base64_decode($ex[0]);
        $exp = explode("/",$url);

        $bankno = $exp[1];
        $year   = date("Y",strtotime($exp[0]));
        $month  = date("m",strtotime($exp[0]));
        $com    = $exp[2];
        $bu     = $exp[3];

//        $newDate = date('Y-m-d', strtotime("+6 months", strtotime("2016-08-28")));
//        echo $newDate;

        $disBook  = Array();
        $disBook1 = PdcLine::select('cv_date','cv_status','baccount_no','check_no','check_amount','check_date','cv_date','payee','cv_no','label_match')
            ->where('baccount_no',$bankno)
            ->where('company_code',$com)
            ->where('bu_unit',$bu)
            ->whereYear('cv_date',$year)
            ->whereMonth('cv_date',$month)
            ->where('cv_status','Posted')
           // ->where('check_date')
            ->get();
        foreach($disBook1 as $book)
        {
            $newDate = date('Y-m-d', strtotime("+6 months", strtotime($book->check_date)));
            $now = strtotime(date('Y-m-d'));
            $bs = BankStatement::where('bank_check_no',$book->check_no)
                ->where('company',$com)
                ->where('bu_unit',$bu)
                ->where('bank_account_no',$bankno)
                ->get();
            $date = 0;
            foreach($bs as $b)
            {
                $date = strtotime($b->bank_date);
            }
            if(($now >= strtotime($newDate) and $book->label_match != "match check") or ($now >= strtotime($newDate) and $book->label_match == "match check" and $book->status=="OC" and $date >=strtotime($newDate)))
            {
               // echo date("m/d/Y",strtotime($book->cv_date)) . " => " . date("m/d/Y",strtotime($book->check_date))."</br>";
               $disBook[] = [
                    $book->cv_no,
                    $book->cv_date,
                    $book->check_date,
                    $book->check_no,
                    $book->payee,
                    $book->check_amount,
                    $book->cv_status,
                    $book->baccount_no,
                    $book->label_match] ;
            }

        }
//dd($disBook);
        return view('accounting.Disbursement.StaleCheck',compact('disBook'));
    }


}
