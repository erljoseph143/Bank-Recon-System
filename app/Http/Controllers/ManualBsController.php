<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\BankNo;
use App\ManualBs;
use App\PdcLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManualBsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {

       $com     = Auth::User()->company_id;
       $bu      = Auth::User()->bunitid;
       $bankdate = date("Y-m-d",strtotime($request->bankdate));
       $checkno  = $request->checkno;
       $des      = $request->des;
       $debit    = $request->debit;
       $credit   = $request->credit;
       if($des =="" or $des==null)
       {
           $des="";
       }
       else
       {
           $des=$request->des;
       }

       if($checkno!="")
       {
           $checkno  = $request->checkno;
       }
       else
       {
           $checkno  = "";
       }
       if($debit == "0.00")
       {
           $bankamount = str_replace(",","",$credit);
           $type       = "AR";
       }
       else
       {
           $bankamount = str_replace(",","",$debit);
           $type       = "AP";
       }
//echo $bankamount;
       $balance  = str_replace(",","",$request->bankbalance);

       $bankaccount = BankAccount::find($request->bankaccount);

       $bankno = $bankaccount->bankno;

       $bank_no = BankNo::find($bankno);
       $bank_account_no = $bank_no->bankno;

       $pdc = PdcLine::where('check_no',$checkno)
           ->where("check_no","!=","")
           ->where('bank_account_no',$bank_account_no)
           ->where('company_code',$com)
           ->where('bu_unit',$bu)
           ->count('id');
       if($pdc > 0)
       {
           $label_match ="match check";
           $dis = PdcLine::where('check_no','!=','')
               ->where('check_no',$checkno)
               ->where('company_code',$com)
               ->where('bu_unit',$bu);

              $dis->update(['status_matching'=>'match check']);

           ManualBs::updateOrCreate([
               'bank_date'=>$bankdate,
               'description'=>$des,
               'bank_check_no'=>$checkno,
               'bank_amount'=>$bankamount,
               'bank_balance'=>$balance,
               'status_matching'=>$label_match,
               'company'=>$com,
               'bu_unit'=>$bu,
               'type'=>$type,
               'bank_account_no'=>$bank_account_no

           ]);

           foreach ($dis->get() as $oc)
           {

               $bankyear = date('Y',strtotime($bankdate));
               $pdcyear = date('Y',strtotime($oc->check_date));
               $bankmonth = date('n',strtotime($bankdate));
               $pdcmonth = date('n',strtotime($oc->check_date));

               if(($pdcmonth < $bankmonth and $bankyear == $pdcyear) or ($pdcyear < $bankyear and $pdcmonth > $bankmonth) )
               {
                   PdcLine::where('check_no',$checkno)->update(['status'=>'OC','oc_cleared'=>'cleared']);
               }
           }
       }
       else
       {
           $label_match = "";
           ManualBs::updateOrCreate([
               'bank_date'=>$bankdate,
               'description'=>$des,
               'bank_check_no'=>$checkno,
               'bank_amount'=>$bankamount,
               'bank_balance'=>$balance,
               'status_matching'=>$label_match,
               'company'=>$com,
               'bu_unit'=>$bu,
               'type'=>$type,
               'bank_account_no'=>$bank_account_no

           ]);

         //  PdcLine::where('id',$bookid)->where('check_no','!=','')->update(['status'=>'OC']);
       }


    }

    public function update(Request $request)
    {

        $bankno        = $request->bankno;
        $id       	   = $request->id;
        $des		   = $request->des;
        $bankdate      = date("Y-m-d",strtotime($request->bankdate));
        $checkno       = $request->checkno;
        $bankamountAP  = $request->deb;
        $bankamountAR  = $request->cred;
        $buid		   = Auth::user()->bunitid;
        $comid		   = Auth::user()->company_id;
        $bankbal       = str_replace(",","",$request->bal);

        if($bankamountAP == "")
        {
            $amount = str_replace(",","",$bankamountAR);
            $type   = "AR";
            $debit_memos ="";
        }
        else
        {
            $amount = str_replace(",","",$bankamountAP);
            $type   = "AP";
            if($checkno == "")
            {
                $debit_memos = "debit memos";
            }
            else
            {
                $debit_memos ="";
            }

        }
        ManualBs::where('bank_id',$id)
            ->update([
                'bank_date'=>$bankdate,
                'bank_amount'=>$amount,
                'bank_check_no'=>$checkno,
                'bank_balance'=>$bankbal,
                'description'=>$des,
                'deposit_status'=>'need to label',
                'credit_memos'=>'',
                'debit_memos'=>$debit_memos,
            ]);

        if($type=="AP")
        {
            $pdc = PdcLine::select('cv_date','check_date','check_no','check_amount')
                ->where('check_no',$checkno)
                ->where('baccount_no',$bankno)
                ->where('company',$comid)
                ->where('bu_unit',$buid);
            if($pdc->count('id') > 0)
            {
                ManualBs::where('bank_check_no',$checkno)
                    ->where('company',$comid)
                    ->where('bu_unit',$buid)
                    ->where('label_match','!=','match check')
                    ->where('bank_account_no',$bankno)
                    ->update(['label_match'=>'match check']);

                PdcLine::where('check_no',$checkno)
                    ->where('company',$comid)
                    ->where('bu_unit',$buid)
                    ->where('label_match','!=','match check')
                    ->where('baccount_no',$bankno)
                    ->where('cv_status','Posted')
                    ->update(['label_match'=>'match check']);
                foreach ($pdc->get() as $p)
                {
                    $bankyear  = date('Y',strtotime($bankdate));
                    $pdcyear   = date('Y',strtotime($p->check_date));
                    $bankmonth = date('n',strtotime($bankdate));
                    $pdcmonth  = date('n',strtotime($p->check_date));
                    if(($pdcmonth < $bankmonth and $bankyear == $pdcyear) or ($pdcyear < $bankyear and $pdcmonth > $bankmonth) )
                    {
                        PdcLine::where('check_no',$checkno)
                            ->where('company',$comid)
                            ->where('bu_unit',$buid)
                            ->where('baccount_no',$bankno)
                            ->where('cv_status','Posted')
                            ->where('check_no','!=','')
                            ->update(['status'=>'oc']);
                        $bs = ManualBs::where('bank_check_no',$checkno)
                            ->where('company',$comid)
                            ->where('bu_unit',$buid)
                            ->where('bank_account_no',$bankno)
                            ->count('bank_id');
                        if($bs > 0)
                        {
                            PdcLine::where('check_no',$checkno)
                                ->where('company',$comid)
                                ->where('bu_unit',$buid)
                                ->where('baccount_no',$bankno)
                                ->where('cv_status','Posted')
                                ->where('check_no','!=','')
                                ->update(['oc_cleared'=>'cleard']);
                        }

                    }
                }
            }
        }
    }
}
