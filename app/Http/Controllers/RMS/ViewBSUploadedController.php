<?php

namespace App\Http\Controllers\RMS;

use App\BankAccount;
use App\BankNo;
use App\BankStatement;
use App\Businessunit;
use App\Company;
use App\PdcLine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ViewBSUploadedController extends Controller
{
    //
    public function BSperCom()
    {
        $com = Company::select('company_code','company','acroname')->get();

        return view('rms.BankStatement.BSperCompany',compact('com'));
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

        return view('rms.BankStatement.BUlist',compact('bu','arrayCount','arrayID','comID'));
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
        return view('rms.BankStatement.bankList',compact('arrayAct','com','bu','bankID'));

    }

    public function BMonthly($data)
    {
        $banklist = Array();
        $exp    = explode(csrf_token(),base64_decode($data));
        $res    = explode("/",$exp[0]);
        $bu     = $res[0];
        $com    = $res[1];
        $bankID = $res[2];
        $type   = "rmsMonthly";

        $bankNum = BankNo::find($bankID);
        $bankno  = $bankNum->bankno;


        $bank = BankStatement::where('company',$com)
            ->where('bu_unit',$bu)
            ->where('bank_account_no',$bankno);
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

    public function showMonthlyBS($data)
    {
      //  $bd->datein/$bankno/$com/$bu/$bl[0]/$bl[1]/$bankID
        $exp       = explode(csrf_token()."?",$data);
        $res       = explode("/",base64_decode($exp[0]));
        $bu        = $res[3];
        $com       = $res[2];
        $bankID    = $res[6];
        $bankno    = $res[1];
        $bankdate  = $res[0];
        $year      = date("Y",strtotime($res[0]));
        $month     = date("m",strtotime($res[0]));
        $bankName  = $res[4];
        $acctno    = $res[5];
		$type      = "rmsMonthly";

        $bank = BankStatement::select('bank_id','bank_date','bank_check_no','bank_amount','bank_balance','type','description','bank_account_no')
        ->where('company',$com)
        ->where('bu_unit',$bu)
        ->whereYear('bank_date',$year)
        ->whereMonth('bank_date',$month)
        ->where('bank_account_no',$bankno)
        ->get();


        return view('rms.BankStatement.viewBankUploaded',compact('bank','bu','com','bankno','bankID','bankdate','bankName','acctno','type'));

    }

    public function updateBSData(Request $request)
    {
        $bankno        = $request->bankno;
        $id       	   = $request->id;
        $des		   = $request->des;
        $bankdate      = date("Y-m-d",strtotime($request->bankdate));
        $checkno       = $request->bankcheckno;
        $bankamountAP  = $request->bankamountAP;
        $bankamountAR  = $request->bankamountAR;
        $buid		   = $request->bu;
        $comid		   = $request->comid;
        $bankbal       = str_replace(",","",$request->bankbal);
		$checknoOld    = $request->checknoOld;

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
        BankStatement::where('bank_id',$id)
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
				BankStatement::where('bank_check_no',$checknoOld)
					->where('company',$comid)
                    ->where('bu_unit',$buid)
					->update(['label_match'=>'']);
					
				PdcLine::where('check_no',$checknoOld)
                    ->where('company',$comid)
                    ->where('bu_unit',$buid)
					->update(['label_match'=>'']);			
			
            $pdc = PdcLine::select('cv_date','check_date','check_no','check_amount')
                ->where('check_no',$checkno)
                ->where('baccount_no',$bankno)
                ->where('company',$comid)
                ->where('bu_unit',$buid);
            if($pdc->count('id') > 0)
            {

					
                BankStatement::where('bank_check_no',$checkno)
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
                       $bs = BankStatement::where('bank_check_no',$checkno)
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
				$countBk = PdcLine::where('check_no',$checknoOld)
                    ->where('company',$comid)
                    ->where('bu_unit',$buid)
					->count('id');
				$countBs = BankStatement::where('bank_check_no',$checknoOld)
					->where('company',$comid)
                    ->where('bu_unit',$buid)
					->count('bank_id');
				if($countBk!=0 and $countBs!=0)
				{
					BankStatement::where('bank_check_no',$checknoOld)
						->where('company',$comid)
						->where('bu_unit',$buid)
						->update(['label_match'=>'match check']);
						
					PdcLine::where('check_no',$checknoOld)
						->where('company',$comid)
						->where('bu_unit',$buid)
						->update(['label_match'=>'match check']);
				}			
			
        }

    }
	
    public function checkpermonth($data)
    {
	    $exp = explode(csrf_token(), base64_decode($data));
	    $res = explode("/", $exp[0]);
	    $bu = $res[0];
	    $com = $res[1];
	    $bankID = $res[2];
	
	    $bankNum = BankNo::find($bankID);
	    $bankno  = $bankNum->bankno;
	
	    $bsData = BankStatement::select(DB::raw("distinct(DATE_FORMAT(bank_date,'%Y-%m')) as datein"))
		    ->where('company',$com)
		    ->where('bu_unit',$bu)
		    ->where('bank_account_no',$bankno)
		    ->get();
	
	    $bankNum = BankNo::find($bankID);
	    $bankno  = $bankNum->bankno;
	    
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
	    
	    return view('rms.BankStatement.checkbalmonth',compact('bsData','bankno','banklist','data'));
    }
    
	public function checkBalancePerBank(Request $request)
	{

		$banklist = Array();
		$exp    = explode(csrf_token(), base64_decode($request->urldata));
		//dd($request->urldata);
		//echo "hahhahah" . $request->_token . " Ok kaau";
	//	echo json_encode(['month'=> $request->urldata]);
		$res    = explode("/", $exp[0]);

		$bu     = $res[0];
		$com    = $res[1];
		$bankID = $res[2];

		$bankNum = BankNo::find($bankID);
		$bankno  = $bankNum->bankno;

//		for($x=0;$x<=1000;$x++)
//		{
//			//usleep(10000);
//			echo json_encode(['month'=>$x]);
//			ob_flush();
//			flush();
//		}

		foreach($request->date as $key => $bs)
		{
			//usleep(10000);
			//dd($request->date);
			$year  = date("Y",strtotime($bs));
			$month = date("m",strtotime($bs));

			$d = BankStatement::whereYear('bank_date',$year)
				->whereMonth('bank_date',$month)
				->where('bank_account_no',$bankno)
				->where('company',$com)
				->where('bu_unit',$bu)
				->get();
			foreach($d as $keys =>$b)
			{

				$type = $b->type;
				$id = 0;
				if($keys!=0)
				{
					$id = $keys -1;
					$bal = $b->bank_balance;
					$prevBal = $d[$id]->bank_balance;
					$amount  = $b->bank_amount;
					//echo $b->bank_balance ." => ".$d[$id]->bank_balance ."<br/>";
					if($type=='AR')
					{
						$balnew = round($prevBal + $amount,2);
					}
					else
					{
						$balnew = round($prevBal - $amount,2);
					}

					if(trim($balnew) != trim($bal))
					{
				
						//echo "\r\n $b->bank_id ".$d[$id]->bank_id." $balnew == $bal NOt total <br/>\r\n";
						BankStatement::where('bank_id',$b->bank_id)->update(['error_label'=>'not balance']);
						echo response()->json([
							'month'=>date("F, Y",strtotime($bs)),
							'bank_date'=>date("m/d/Y",strtotime($b->bank_date)),
							'des'=>$b->description,
							'check_no'=>$b->bank_check_no,
							'amount'=>str_replace(",","",$b->bank_amount),
							'type'=>$b->type,
							//'balance'=>str_replace(",","",$b->bank_balance) ." $balnew = round($prevBal + $amount,2);",
							'balance'=>str_replace(",","",$b->bank_balance),
							'balnew'=>str_replace(",","",$balnew),
							'status'=>'not equal',
							'url'=>$bs."/".$request->urldata."/".$b->bank_id
						]);
						ob_flush();
						flush();
					}
					else
					{
//						echo response()->json([
//							'month'=>date("F, Y",strtotime($bs)),
//							'bank_date'=>date("m/d/Y",strtotime($b->bank_date)),
//							'des'=>$b->description,
//							'check_no'=>$b->bank_check_no,
//							'amount'=>number_format($b->amount,2),
//							'type'=>$b->type,
//							'balance'=>number_format($b->bank_balance,2),
//							'balnew'=>number_format($balnew,2),
//							'status'=>'equal'
//						]);
//						ob_flush();
//						flush();
					}
				}

			}
		}

	}

	public function showErroBS($date,$details,$id)
	{
		$year  = date("Y",strtotime($date));
		$month = date("m",strtotime($date));
		$exp    = explode(csrf_token(), base64_decode($details));
		$res    = explode("/", $exp[0]);
		
		$bu     = $res[0];
		$com    = $res[1];
		$bankID = $res[2];
		
		$bankNum = BankNo::find($bankID);
		$bankno  = $bankNum->bankno;
		
		$bank = BankStatement::whereYear('bank_date',$year)
			->whereMonth('bank_date',$month)
			->where('bank_account_no',$bankno)
			->where('company',$com)
			->where('bu_unit',$bu)
			->get();
		
		session()->forget('bu');
		session()->forget('com');
		session()->forget('bank');
		session()->forget('bank_no');
		session()->forget('bank_date');
		session(['bu'=>$bu]);
		session(['com'=>$com]);
		session(['bank'=>$bank]);
		session(['bank_no'=>$bankno]);
		session(['bank_date'=>$date]);
		
		return view('rms.BankStatement.showErrorbs',compact('bank','com','bu','id'));
	}
	
	public function bsError($data)
	{
		//  $bd->datein/$bankno/$com/$bu/$bl[0]/$bl[1]/$bankID
		$exp = explode(csrf_token() . "?", $data);
		$res = explode("/", base64_decode($exp[0]));
		$bu = $res[3];
		$com = $res[2];
		$bankID = $res[6];
		$bankno = $res[1];
		$bankdate = $res[0];
		$year = date("Y", strtotime($res[0]));
		$month = date("m", strtotime($res[0]));
		$bankName = $res[4];
		$acctno = $res[5];
		$type = "rmsMonthly";
		
		$bank = BankStatement::select('bank_date', 'bank_check_no', 'bank_amount', 'bank_balance', 'type', 'description', 'bank_account_no','error_label','label_match','company','bu_unit')
			->where('company', $com)
			->where('bu_unit', $bu)
			->whereYear('bank_date', $year)
			->whereMonth('bank_date', $month)
			->where('bank_account_no', $bankno)
			->get();
		session()->forget('bu');
		session()->forget('com');
		session()->forget('bank');
		session()->forget('bank_no');
		session()->forget('bank_date');
		session(['bu'=>$bu]);
		session(['com'=>$com]);
		session(['bank'=>$bank]);
		session(['bank_no'=>$bankno]);
		session(['bank_date'=>$bankdate]);
		
		return view('rms.BankStatement.bsError', compact('bank', 'bu', 'com', 'bankno', 'bankID', 'bankdate', 'bankName', 'acctno', 'type'));
		
	}
	
	public function bsinsertingdata($key)
	{
		//$bank = session()->get('bank')->toArray();
		return view('rms.BankStatement.insertData',compact('key'));
	}
	
	public function insertbs(Request $request)
	{
		$bu     = session()->get('bu');
		$com    = session()->get('com');
		$bankno = session()->get('bank_no');
		$month  = date("m",strtotime(session()->get('bank_date')));
		$year   = date("Y",strtotime(session()->get('bank_date')));
		
		
		$countPdc = PdcLine::where('check_no',$request->check_no)
					->where('label_match','!=','match check')
					->where('baccount_no',$bankno)
					->where('bu_unit',$bu)
					->where('company',$com)
					->count('id');
		if($countPdc > 0)
		{
			$label_match = "match check";
		}
		else
		{
			$label_match = "";
		}
		$bank = session()->get('bank')->toArray();
		$amount = 0;
		$type   = "";
		
		if($request->cred_amt!='')
		{
			$amount = $request->cred_amt;
			$type   = "AR";
		}
		else
		{
			$amount = $request->deb_amt;
			$type   = "AP";
		}
		$res = array_slice($bank, 0, $request->key, true) +
			array(
				"new_data$request->key" =>
				array(
				"bank_date" => date("Y-m-d",strtotime($request->bank_date)),
			    "bank_check_no" => $request->check_no,
			    "bank_amount" => str_replace(",","",$amount),
			    "bank_balance" => str_replace(",","",$request->balance),
			    "type" => $type,
			    "description" => $request->des,
			    "bank_account_no" => $bankno,
			    "error_label" => "",
			    "label_match" => $label_match
				)
			) +
			array_slice($bank, $request->key, count($bank) - 1, true) ;
		$collection = $this->arrayNew($res);
		$prevKey = "";
		//dd($collection);
		foreach($collection as $key => $data)
		{
			$b= (object)$data;
			$type = $b->type;
			$id = 0;
			if($key!=0)
			{
				
				$id = $key - 1;
				$prevKey = $key - 1;
				
				$bal = $b->bank_balance;
				$obj = (object)$collection[$id];
				$prevBal = $obj->bank_balance;
				$amount = $b->bank_amount;
				$amtbal = "";
				//echo $b->bank_balance ." => ".$d[$id]->bank_balance ."<br/>";
				if ($type == 'AR')
				{
					$balnew = round($prevBal + $amount, 2);
					$amtbal = $prevBal . " + " . $amount;
				}
				else
				{
					$balnew = round($prevBal - $amount, 2);
					$amtbal = $prevBal . " - " . $amount;
				}
				
				if (trim($balnew) != trim($bal))
				{
					//echo $id . " => " . $amtbal . " " . $b->bank_balance . " => " . $obj->bank_balance . "Not Balance</br>";
				}
//				else
//				{
//					echo "Balance</br>";
//				}
			}
		}
		
		
		BankStatement::where('bank_account_no',$bankno)
			->whereMonth('bank_date',$month)
			->whereYear('bank_date',$year)
			->where('company',$com)
			->where('bu_unit',$bu)
			->delete();
		//dd($collection);
		//DB::enableQueryLog();
		DB::transaction(function()use($collection,$bankno,$com,$bu) {
			foreach ($collection as $key => $obj) {
				$bs = (object)$obj;
				$date =  date("Y-m-d", strtotime($bs->bank_date));
				$data = [
					"bank_date" =>$date,
					"bank_check_no" => $bs->bank_check_no,
					"bank_amount" => $bs->bank_amount,
					"bank_balance" => $bs->bank_balance,
					"type" => $bs->type,
					"description" => $bs->description,
					"bank_account_no" => $bankno,
					"error_label" => $bs->error_label,
					"label_match" => $bs->label_match,
					"company"=>$com,
					"bu_unit"=>$bu
				];

			 	BankStatement::Create($data);
			}
		});
	}
	
	public function reorder($id)
	{
		$bank = session()->get('bank');
		$newdata = Array();
		$exp = explode("|",$id);
		foreach($exp as $key => $data){
			if($key!=0)
			{
				if (array_key_exists($data,$bank->toArray())):
					$newdata[] = $bank[$data]->toArray();
					unset($bank[$data]);
				endif;
			}
		}
		session()->forget('bankdata');
		session()->forget('dataset');
		session()->forget('keydata');
		session(['bankdata'=>$bank]);
		session(['dataset'=>$newdata]);
		session(['keydata'=>$exp]);
		return view('rms.BankStatement.reorderbs',compact('bank','newdata'));
	}
	
	public function ordering($key)
	{
		$bank    = session()->get('bankdata');
		$dataset = session()->get('dataset');
		$keydata = session()->get('keydata');
		$bu      = session()->get('bu');
		$com     = session()->get('com');
		$bankno  = session()->get('bank_no');
		$month   = date("m",strtotime(session()->get('bank_date')));
		$year    = date("Y",strtotime(session()->get('bank_date')));
		$res     = Array();
		if(trim($key)!='top-data')
		{
			foreach($bank->toArray() as $keyof => $bs)
			{
				if($key ==($keyof-1))
				{
					foreach($keydata as $keys => $data):;
						if($keys!=0)
						{
							$res["new_data$data"] =  $dataset[$keys!=0?$keys-1:$keys];
						}
					endforeach;
					$res[] = $bs;
				}
				else
				{
					$res[] = $bs;
				}
			}
		}
		else
		{
			foreach($keydata as $keys => $data):
				if($keys!=0)
				{
					$res["new_data$data"] =  $dataset[$keys!=0?$keys-1:$keys];
				}
			endforeach;
			foreach($bank->toArray() as $keyof => $bs)
			{
					$res[] = $bs;
			}
		}
		$collection = $this->arrayNew($res);
		BankStatement::where('bank_account_no',$bankno)
			->whereMonth('bank_date',$month)
			->whereYear('bank_date',$year)
			->where('company',$com)
			->where('bu_unit',$bu)
			->delete();
		DB::transaction(function()use($collection,$bankno,$com,$bu) {
			foreach ($collection as $key => $obj) {
				$bs = (object)$obj;
				$date =  date("Y-m-d", strtotime($bs->bank_date));
				$data = [
					"bank_date" =>$date,
					"bank_check_no" => $bs->bank_check_no,
					"bank_amount" => $bs->bank_amount,
					"bank_balance" => $bs->bank_balance,
					"type" => $bs->type,
					"description" => $bs->description,
					"bank_account_no" => $bankno,
					"error_label" => $bs->error_label,
					"label_match" => $bs->label_match,
					"company"=>$com,
					"bu_unit"=>$bu
				];

				BankStatement::Create($data);
			}
		});
//		$prevKey = "";
//		foreach($collection as $key => $data)
//		{
//			$b      = (object)$data;
//			$type   = $b->type;
//			$id     = 0;
//			$bal    = $b->bank_balance;
//			$amount = $b->bank_amount;
//			if($key>0)
//			{
//				$id  = $key - 1;
//				$obj = (object)$collection[$id];
//				$prevBal = $obj->bank_balance;
//			}
//			else
//			{
//				if ($type == 'AR')
//				{
//					$prevBal = $bal - $b->bank_amount;
//				}
//				else
//				{
//					$prevBal = $bal + $b->bank_amount;
//				}
//			}
//				if ($type == 'AR')
//				{
//					$balnew = round($prevBal + $amount, 2);
//					$amtbal = $prevBal . " + " . $amount ." = $balnew </br>";
//				}
//				else
//				{
//					$balnew = round($prevBal - $amount, 2);
//					$amtbal = $prevBal . " - " . $amount .  " = $balnew </br> " ;
//
//				}
//
//				if (trim($balnew) != trim($bal))
//				{
//					//echo $key . " => " . $amtbal . " " . $b->bank_balance . " => " . $obj->bank_balance . "UnCheck</br>";
//				}
//				else
//				{
//					//echo "Check</br>";
//				}
//
//		}
	
		
		
	}
	
	public function orderArray($arrayTobeinserted,$arrayTobepush,$keydata)
	{
		foreach($keydata as $keys => $data):
			if($data!=0)
			{
				$arrayTobepush["new_data$data"] =  $arrayTobeinserted[$keys!=0?$keys-1:$keys]->toArray();
//							$res += array_slice($bank->toArray(), 0, $key, true) +
//								array(
//									"new_data$data" => $dataset[$keys!=0?$keys-1:$keys]->toArray()
//								) +
//								array_slice($bank->toArray(), $key, count($bank->toArray()) - 1, true) ;
			}
		endforeach;
		return $arrayTobepush;
	}
	
	public function arrayNew($array)
	{
		$ar = Array();
		foreach($array as $key => $data)
		{
			$obj = (object)$data;
			//dd($obj);
			$ar[] =[
					"bank_date" => date("Y-m-d",strtotime($obj->bank_date)),
					"bank_check_no" => $obj->bank_check_no,
					"bank_amount" => $obj->bank_amount,
					"bank_balance" => $obj->bank_balance,
					"type" => $obj->type,
					"description" => $obj->description,
					"bank_account_no" => $obj->bank_account_no,
					"error_label" => "",
					"label_match" => $obj->label_match
			       ];
		}
		return $ar;
	}
	
	
	public function BankAccountMonitoring($bankId)
	{
		$bank       = BankAccount::find($bankId);
		$com        = $bank->company_code;
		$bu         = $bank->buid;
		$bankno     = $bank->bankcode->bankno;
		
		$bankInfo   = BankStatement::where('bank_account_no',$bankno)
					->where('company',$com)
					->where('bu_unit',$bu)
					->groupBy(DB::raw('DATE_FORMAT(bank_date, "%Y")'))
					->get();
		$data       = Array();
		if(count($bankInfo->toArray())>0)
		{
			
			foreach($bankInfo as $bs)
			{
				$year   = date("Y",strtotime($bs->bank_date));
				$month  = BankStatement::select(DB::raw('DATE_FORMAT(bank_date, "%m") as datein'))->where('bank_account_no',$bankno)
					->where('company',$com)
					->where('bu_unit',$bu)
					->whereYear('bank_date',$year)
					->groupBy(DB::raw('DATE_FORMAT(bank_date, "%Y-%m")'));
				
				if(count($month->get()->toArray())==12)
				{
					$mlist = "";
					//dd($month->get()->toArray());
					$data[] = [1,$year,'Completed from January to December',100];
					
				}
				else
				{
					$mlist = "";
					foreach($month->get() as $m)
					{
						$date   = "2017-".$m->datein."-01";
						$mlist .= " ".date("F",strtotime($date)) .",";
					}
					$percent = (count($month->get()->toArray())/12)*100;
					$mlist = rtrim($mlist,",");
					$data[]  = [1,$year,"Not completed only $mlist",$percent];
					
				}
			}
		}
		else
		{
			$data[] = [0,'No Records found'] ;
			
		}
		//dd($data[0]);
		//dd($bankInfo);
		return view('rms.bankInfo',compact('data'));
	}
	
	
}
