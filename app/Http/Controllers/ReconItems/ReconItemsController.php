<?php

namespace App\Http\Controllers\ReconItems;

use App\BankAccount;
use App\BankNo;
use App\BankStatement;
use App\Businessunit;
use App\Company;
use App\PdcLine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReconItemsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reconItems(Request $request)
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
                    // echo $b->bankno ."</br>";
                    $bankno = $b->bankno;
                    $bankID = 0;
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
                        $banklist[] = [$ba->bank,$ba->accountno,$ba->accountname,$bankno,$bankID];
                    }

                }

            }
            return view('accounting.ReconItems.ReconItems',compact('banklist'));
        }
    }

    public function monthReconItems(Request $request,$bankno)
    {
        $exp    = explode("|",$bankno);
        $bankno = $exp[0];
        $bankID = $exp[1];
        $com    = Auth::user()->company_id;
        $bu     = Auth::user()->bunitid;
        if($request->ajax())
        {
            $arrayData = Array();
            $bs = BankStatement::select(DB::raw('distinct(DATE_FORMAT(bank_date,"%Y-%m")) as datein'))
                ->where('bank_account_no',$bankno)
                ->where('company',$com)
                ->where('bu_unit',$bu)
                ->get();
            $bankAct = BankAccount::select('id','bank','accountno','accountname')
                ->where('company_code',$com)
                ->where('buid',$bu)
                ->where('bankno',$bankID)
                ->get();
            foreach ($bs as $b)
            {
                foreach ($bankAct as $bAct)
                {
                    $arrayData[] = [$b->datein,$bAct->bank,$bAct->accountno,$bAct->accountname,$bAct->id,$bankno];
                }
            }
            return view('accounting.ReconItems.monthReconItems',compact('arrayData'));
        }
    }

    public function reconItemsList(Request $request,$data)
    {
	   
//        if($request->ajax())
//        {
            $exp      = explode(csrf_token(),$data);
            $exp      = base64_decode($exp[0]);
            $exp      = explode("/",$exp);
            $bankno   = $exp[1];
            $datein   = $exp[0];
            $bankName = $exp[2];
            $acctNo   = $exp[3];
            $acctName = $exp[4];
            $year     = date('Y',strtotime($datein));
            $month    = date("m",strtotime($datein));
            $com      = Auth::user()->company_id;
            $bu       = Auth::user()->bunitid;
//            $countpdc = PdcLine::where('baccount_no',$bankno)
//                ->whereYear('cv_date',$year)
//                ->whereMonth('cv_date',$month)
//                ->where('company',$com)
//                ->where('bu_unit',$bu)
//                ->where(DB::raw("(MONTH(cv_date) < MONTH(check_date) and YEAR(cv_date)=YEAR(check_date)) or (MONTH(cv_date) > MONTH(check_date) and YEAR(cv_date) < YEAR(check_date))"))
//                ->count('id');
//
//            $countDM = BankStatement::where('bank_account_no',$bankno)
//                ->where('type','AP')
//                ->where('debit_memos','debit memos')
//                ->where('company',$com)
//                ->where('bu_unit',$bu)
//                ->whereYear('bank_date',$year)
//                ->whereMonth('bank_date',$month)
//                ->count('bank_id');
//
//            $countOC1 = PdcLine::where('baccount_no',$bankno)
//                ->where('status','OC')
//                ->where('company',$com)
//                ->where('bu_unit',$bu)
//              //  ->where('oc_cleared','!=','cleared')
//                ->where("cv_date" ,'<=',date("Y-m-t",strtotime($datein)));
//            $countOC = $countOC1->count('id');
//
//              foreach ($countOC1->get() as $oc)
//                {
//                    if($oc->oc_cleared == 'cleared'
//                        and
//                        ((date("n",strtotime($oc->cv_date)) < date("n",strtotime($datein))
//                            and date("Y",strtotime($oc->cv_date)) == date("Y",strtotime($datein)))
//                        or (date("n",strtotime($oc->cv_date)) > date("n",strtotime($datein))
//                                and date("Y",strtotime($oc->cv_date)) < date("Y",strtotime($datein))
//                            ))
//                    )
//                    {
//                        $countOC--;
//                    }
//                }
	   
         return view('accounting.ReconItems.reconItemsList',compact('datein','acctName','acctNo','bankName','bankno'));
   //     }

    }

    public function reconItemExcel($data)
    {
        $exp = explode(csrf_token(),$data);
        $exp = explode("/",base64_decode($exp[0]));
        $type = $exp[0];
        $bankno = $exp[1];
        $com    = Auth::user()->company_id;
        $bu     = Auth::user()->bunitid;
        $year   = date("Y",strtotime($exp[2]));
        $month  = date("m",strtotime($exp[2]));
        $datein = date("Y-m-t",strtotime($exp[2]));

        $bankName = $exp[3];
        $acctNo   = $exp[4];

        if($type=="Debit Memos")
        {
           // $query    = "select * from bank_statement where bank_account_no='$bank_no' and type='AP' and debit_memos='debit memos' and bu_unit='$bu_unit' and company=$company and MONTH(bank_date)='$month' and YEAR(bank_date)='$year'";

                Excel::create("Debit Memos - " . $bankName ." - ". $acctNo,function($excel)use($com,$bankno,$bu,$year,$month,$datein,$bankName,$acctNo){

                    // Set the title
                    $excel->setTitle('Debit Memos');

                    // Chain the setters
                    $excel->setCreator('BRS PROGRAMMER REY JOSEPH T. BAAY, JELLARY CADUTDUT,GLENN MICHAEL MEJEIAS')->setCompany('Coporate AGC');

                    $excel->setDescription('All debit memos in bank statement');

                    $excel->sheet("Debit Memos",function($sheet)use($com,$bankno,$bu,$year,$month,$datein,$bankName,$acctNo){
                        $arrayDM = Array();
                        $debmem = BankStatement::select('bank_id','bank_date','description','bank_check_no','bank_amount','debit_memos')
                            ->where('bank_account_no',$bankno)
                            ->where('company',$com)
                            ->where('bu_unit',$bu)
                            ->whereYear('bank_date',$year)
                            ->whereMonth('bank_date',$month)
                            ->where('debit_memos','debit memos')
                            ->where('type','AP')
                            ->get();
                        foreach($debmem as $dm)
                        {
                            $arrayDM = [
                                date("n/j/Y",strtotime($dm->bank_date)),
                                $dm->description,
                                number_format($dm->bank_amount,2),
                                $dm->debit_memos
                            ];
                        }

                        $sheet->setOrientation('landscape');
                        $count = count($debmem->all()) + 5;

                        $headings = array('BANK DATE', 'DESCRIPTION','BANK AMOUNT','STATUS');

                        $b       = Businessunit::findOrFail($bu);
                        $comName = $b->company->company;
                        $buName  = $b->bname;


                        $sheet->mergecells('A1:G1');
                        $sheet->row(1,array($comName." : ".$buName." : ".$bankName." - ".$acctNo));

                        $sheet->prependRow(5, $headings);
                        $sheet->setBorder('A4:D4', 'thin');
                        $sheet->mergecells('A4:D4');
                        $sheet->row(4,array('Debit Memos'));
                        $sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
//                        $sheet->setBorder('A3:E3', 'thin');
                        $sheet->setBorder('A5:D'.$count, 'thin');
//                        $arrayBS = Array();
                        $sheet->fromArray($arrayDM,NULL,"A6",false,false);

                        for($s=6;$s<=$count;$s++)
                        {
                            $sheet->getStyle('A'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                        }


                    });

                })->download('xlsx');


        }
        elseif($type =="Outstanding Check")
        {

                Excel::create("Outstanding Checks - " . $bankName ." - ". $acctNo,function($excel)use($com,$bankno,$bu,$year,$month,$datein,$bankName,$acctNo){

                    // Set the title
                    $excel->setTitle('Outstanding Checks');

                    // Chain the setters
                    $excel->setCreator('BRS PROGRAMMER REY JOSEPH T. BAAY, JELLARY CADUTDUT,GLENN MICHAEL MEJEIAS')->setCompany('Coporate AGC');

                    $excel->setDescription('All outstanding check in book');
/*-----------------------------------------------------------------------------------------------------------------------------------------
 * OUTSTANDING CHECKS
 *-----------------------------------------------------------------------------------------------------------------------------------------
*/
                    $excel->sheet("Outstanding Checks",function($sheet)use($com,$bankno,$bu,$year,$month,$datein,$bankName,$acctNo){
                        $arrayOC = Array();
                        $OCdata  = PdcLine::where('baccount_no',$bankno)
                            ->where('status','OC')
                            ->where('company',$com)
                            ->where('bu_unit',$bu)
                            //  ->where('oc_cleared','!=','cleared')
                            ->where("cv_date" ,'<=',date("Y-m-t",strtotime($datein)))
							->orderBy('cv_date','asc')
                           // ->where(DB::raw('cv_date < check_date'))
                            ->get();
                   //     dd($OCdata);
                        foreach($OCdata as $oc)
                        {
                            $clearingDate = "";
                            $mCV     = date("n",strtotime($oc->cv_date));
                            $mCheck  = date("n",strtotime($oc->check_date));
                            $yCV     = date("Y",strtotime($oc->cv_date));
                            $yCheck  = date("Y",strtotime($oc->check_date));

							$dateRange   = strtotime(date("Y-m-t",strtotime($datein)));
							$dateCleared = "";
							$bsChecks = BankStatement::select('bank_date','bank_check_no')
									->where('type','AP')
									->where('bank_check_no',$oc->check_no);
								 foreach($bsChecks->get() as $bs)
                                    {
										$dateCleared = strtotime($bs->bank_date);
									}
									
							
                            if ($oc->oc_cleared == "cleared"
                                    and
                                date("Y", strtotime($oc->cv_date)) == $year
                                    and
                                date("m", strtotime($oc->cv_date)) == $month
									and
                                ($mCV == $mCheck and $yCV == $yCheck)
									and
								(($dateCleared > $dateRange) or ($dateCleared==""))
                            )

                            {

                                if($bsChecks->count('bank_id') > 0)
                                {
                                    foreach($bsChecks->get() as $bs)
                                    {
                                        $clearingDate = date("n/j/Y",strtotime($bs->bank_date));
                                    }
                                }
                                else
                                {
                                    $clearingDate = "";
                                }
                                $arrayOC[] = [

                                    $oc->cv_no,
                                    date("n/j/Y",strtotime($oc->cv_date)),
                                    date("n/j/Y",strtotime($oc->check_date)),
                                    $oc->check_no,
                                    number_format($oc->check_amount,2),
                                    $clearingDate,
	                                $oc->payee
                                ];
                            }
                            elseif($mCV == $mCheck and $yCV == $yCheck and (($dateCleared > $dateRange) or ($dateCleared=="")))
                            {
                                $bsChecks = BankStatement::select('bank_date','bank_check_no')
                                    ->where('bank_check_no',$oc->check_no)->orderBy('bank_date','asc');
                                if($bsChecks->count('bank_id') > 0)
                                {
                                    foreach($bsChecks->get() as $bs)
                                    {
                                        $clearingDate = date("n/j/Y",strtotime($bs->bank_date));
                                    }
                                }
                                else
                                {
                                    $clearingDate = "";
                                }
                                $arrayOC[] = [

                                    $oc->cv_no,
                                    date("n/j/Y",strtotime($oc->cv_date)),
                                    date("n/j/Y",strtotime($oc->check_date)),
                                    $oc->check_no,
                                    number_format($oc->check_amount,2),
                                    $clearingDate,
	                                $oc->payee
                                ];
                            }
                        }

                        $sheet->setOrientation('landscape');
                        $count = count($arrayOC) + 5;

                        $headings = array('CV NO','CV DATE', 'CHECK DATE','CHECK NO','CHECK AMOUNT','CLEARING DATE','PAYEE');

                        $b       = Businessunit::findOrFail($bu);
                        $comName = $b->company->company;
                        $buName  = $b->bname;


                        $sheet->mergecells('A1:G1');
                        $sheet->row(1,array($comName." : ".$buName." : ".$bankName." - ".$acctNo));

                        $sheet->prependRow(5, $headings);
                        $sheet->setBorder('A4:G4', 'thin');
                        $sheet->mergecells('A4:G4');
                        $sheet->row(4,array('OUTSTANDING CHECKS'));
                        $sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
//                        $sheet->setBorder('A3:E3', 'thin');
                        $sheet->setBorder('A5:G'.$count, 'thin');

                        $sheet->fromArray($arrayOC,NULL,'A6',false,false);
                        for($s=6;$s<=$count;$s++)
                        {
                            $sheet->getStyle('E'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                        }
                    });
/*-----------------------------------------------------------------------------------------------------------------------------------------
 * PRE-DATED CHECKS
 *-----------------------------------------------------------------------------------------------------------------------------------------
*/
                    $excel->sheet("PRE-DATED Checks",function($sheet)use($com,$bankno,$bu,$year,$month,$datein,$bankName,$acctNo){
                        $arrayOC = Array();
                        $OCdata  = PdcLine::where('baccount_no',$bankno)
                            ->where('status','OC')
                            ->where('company',$com)
                            ->where('bu_unit',$bu)
//                            //  ->where('oc_cleared','!=','cleared')
                           ->where("cv_date" ,'<=',date("Y-m-t",strtotime($datein)))
                            ->get();

                        foreach($OCdata  as $oc)
                        {
                            $clearingDate = "";
                            $mCV     = date("n",strtotime($oc->cv_date));
                            $mCheck  = date("n",strtotime($oc->check_date));
                            $yCV     = date("Y",strtotime($oc->cv_date));
                            $yCheck  = date("Y",strtotime($oc->check_date));

                            if ($oc->oc_cleared == "cleared"
                                    and
                                date("Y", strtotime($oc->cv_date)) == $year
                                    and
                                date("m", strtotime($oc->cv_date)) == $month
                                and (($mCV > $mCheck and $yCV == $yCheck) or ($mCV < $mCheck and $yCV > $yCheck))
                              )
                            {
                                $bsChecks = BankStatement::select('bank_date','bank_check_no')
                                    ->where('bank_check_no',$oc->check_no);
                                if($bsChecks->count('bank_id') > 0)
                                {
                                    foreach($bsChecks->get() as $bs)
                                    {
                                        $clearingDate = date("n/j/Y",strtotime($bs->bank_date));
                                    }
                                }
                                else
                                {
                                    $clearingDate = "";
                                }
                                $arrayOC[] = [

                                    $oc->cv_no,
                                    date("n/j/Y",strtotime($oc->cv_date)),
                                    date("n/j/Y",strtotime($oc->check_date)),
                                    $oc->check_no,
                                    number_format($oc->check_amount,2),
                                    $clearingDate,
	                                $oc->payee
                                ];
                            }
                            elseif(($mCV > $mCheck and $yCV == $yCheck) or ($mCV < $mCheck and $yCV > $yCheck))
                            {
                                $bsChecks = BankStatement::select('bank_date','bank_check_no')
                                    ->where('bank_check_no',$oc->check_no);
                                if($bsChecks->count('bank_id') > 0)
                                {
                                    foreach($bsChecks->get() as $bs)
                                    {
                                        $clearingDate = date("n/j/Y",strtotime($bs->bank_date));
                                    }
                                }
                                else
                                {
                                    $clearingDate = "";
                                }
                                $arrayOC[] = [

                                    $oc->cv_no,
                                    date("n/j/Y",strtotime($oc->cv_date)),
                                    date("n/j/Y",strtotime($oc->check_date)),
                                    $oc->check_no,
                                    number_format($oc->check_amount,2),
                                    $clearingDate,
	                                $oc->payee
                                ];
                            }
                        }
                       // dd($arrayOC);

                        $sheet->setOrientation('landscape');
                        $count = count($arrayOC) + 5;

                        $headings = array('CV NO','CV DATE', 'CHECK DATE','CHECK NO','CHECK AMOUNT','CLEARING DATE');

                        $b       = Businessunit::findOrFail($bu);
                        $comName = $b->company->company;
                        $buName  = $b->bname;

                        $sheet->mergecells('A1:G1');
                        $sheet->row(1,array($comName." : ".$buName." : ".$bankName." - ".$acctNo));

                        $sheet->prependRow(5, $headings);
                        $sheet->setBorder('A4:G4', 'thin');
                        $sheet->mergecells('A4:G4');
                        $sheet->row(4,array('Outstanding Check but Pre-Dated Check       NOTE:Check Date is earlier than CV Date'));
                        $sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
//                        $sheet->setBorder('A3:E3', 'thin');
                        $sheet->setBorder('A5:G'.$count, 'thin');

                        $sheet->fromArray($arrayOC,NULL,'A6',false,false);
                        for($s=6;$s<=$count;$s++)
                        {
                            $sheet->getStyle('E'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                        }
                    });

                    $excel->setActiveSheetIndex(0);

                })->download('xlsx');


        }
        elseif($type == "PDC")
        {
            $pdc = PdcLine::where('baccount_no',$bankno)
                ->whereYear('cv_date',$year)
                ->whereMonth('cv_date',$month)
                ->where('company',$com)
                ->where('bu_unit',$bu)
                ->where(DB::raw("(MONTH(cv_date) < MONTH(check_date) and YEAR(cv_date)=YEAR(check_date)) or (MONTH(cv_date) > MONTH(check_date) and YEAR(cv_date) < YEAR(check_date))"))
                ->get();

            Excel::create("PDC - " . $bankName ." - ". $acctNo,function($excel)use($com,$bankno,$bu,$year,$month,$datein,$bankName,$acctNo){

                // Set the title
                $excel->setTitle('PDC');

                // Chain the setters
                $excel->setCreator('BRS PROGRAMMER REY JOSEPH T. BAAY, JELLARY CADUTDUT,GLENN MICHAEL MEJEIAS')->setCompany('Coporate AGC');

                $excel->setDescription('All postdated check in book');

                $excel->sheet("PDC",function($sheet)use($com,$bankno,$bu,$year,$month,$datein,$bankName,$acctNo){
                   $arrayPDC = Array();
                    $pdc = PdcLine::where('baccount_no',$bankno)
                        ->whereYear('cv_date',$year)
                        ->whereMonth('cv_date',$month)
                        ->where('company',$com)
                        ->where('bu_unit',$bu)
                        ->where(DB::raw("(MONTH(cv_date) < MONTH(check_date) and YEAR(cv_date)=YEAR(check_date)) or (MONTH(cv_date) > MONTH(check_date) and YEAR(cv_date) < YEAR(check_date))"))
                        ->get();
                    foreach ($pdc as $data)
                    {
                        $arrayPDC[] = [
                            $data->cv_no,
                            date("n/j/Y",strtotime($data->cv_date)),
                            date("n/j/Y",strtotime($data->check_date)),
                            $data->check_no,
                            number_format($data->check_amount,2),
                            'PDC',
	                        $data->payee
                        ];
                    }


                    $sheet->setOrientation('landscape');
                    $count = count($pdc->all()) + 5;

                    $headings = array('CV NO','CV DATE', 'CHECK DATE','CHECK NO','CHECK AMOUNT','STATUS','PAYEE');

                    $b       = Businessunit::findOrFail($bu);
                    $comName = $b->company->company;
                    $buName  = $b->bname;


                    $sheet->mergecells('A1:G1');
                    $sheet->row(1,array($comName." : ".$buName." : ".$bankName." - ".$acctNo));

                    $sheet->prependRow(5, $headings);
                    $sheet->setBorder('A4:G4', 'thin');
                    $sheet->mergecells('A4:G4');
                    $sheet->row(4,array('POSTDATED CHECKS'));
                    $sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
//                        $sheet->setBorder('A3:E3', 'thin');
                    $sheet->setBorder('A5:G'.$count, 'thin');

                    $sheet->fromArray($arrayPDC,NULL,'A6',false,false);
                    for($s=6;$s<=$count;$s++)
                    {
                        $sheet->getStyle('E'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                    }

                });

            })->download('xlsx');

        }
        else
        {
	
	        Excel::create("Stale Checks - " . $bankName ." - ". $acctNo,function($excel)use($com,$bankno,$bu,$year,$month,$datein,$bankName,$acctNo){
		
		        // Set the title
		        $excel->setTitle('PDC');
		
		        // Chain the setters
		        $excel->setCreator('BRS PROGRAMMER REY JOSEPH T. BAAY, JELLARY CADUTDUT,GLENN MICHAEL MEJEIAS')->setCompany('Coporate AGC');
		
		        $excel->setDescription('All postdated check in book');
		
		        $excel->sheet("Stale Checks",function($sheet)use($com,$bankno,$bu,$year,$month,$datein,$bankName,$acctNo){
			        $arraySC = Array();
			        $sc = PdcLine::where('baccount_no',$bankno)
				        ->whereYear('cv_date',$year)
				        ->whereMonth('cv_date',$month)
				        ->where('company',$com)
				        ->where('bu_unit',$bu)
				        ->where('label_match','')
				        ->get();
			        foreach ($sc as $data)
			        {
				        $newDate = date('Y-m-d', strtotime("+6 months", strtotime($data->check_date)));
				       // echo $newDate ."</br>";
				        $now = strtotime(date('Y-m-d'));
				        $bs = BankStatement::where('bank_check_no',$data->check_no)
					        ->where('company',$com)
					        ->where('bu_unit',$bu)
					        ->where('bank_account_no',$bankno)
					        ->get();
				        $date = 0;
				        foreach($bs as $b)
				        {
					        $date = strtotime($b->bank_date);
				        }
				        if(($now >= strtotime($newDate) and $data->label_match != "match check") or ($now >= strtotime($newDate) and $data->label_match == "match check" and $data->status=="OC" and $date >=strtotime($newDate)))
				        {
					        $arraySC[] = [
						        $data->cv_no,
						        date("n/j/Y", strtotime($data->cv_date)),
						        date("n/j/Y", strtotime($data->check_date)),
						        $data->check_no,
						        number_format($data->check_amount, 2),
						        $data->cv_status,
						        $data->payee
					        ];
				        }
			        }
			
			
			        $sheet->setOrientation('landscape');
			        $count = count($sc->all()) + 5;
			
			        $headings = array('CV NO','CV DATE', 'CHECK DATE','CHECK NO','CHECK AMOUNT','STATUS','PAYEE');
			
			        $b       = Businessunit::findOrFail($bu);
			        $comName = $b->company->company;
			        $buName  = $b->bname;
			
			
			        $sheet->mergecells('A1:G1');
			        $sheet->row(1,array($comName." : ".$buName." : ".$bankName." - ".$acctNo));
			
			        $sheet->prependRow(5, $headings);
			        $sheet->setBorder('A4:G4', 'thin');
			        $sheet->mergecells('A4:G4');
			        $sheet->row(4,array('STALE CHECKS'));
			        $sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
//                        $sheet->setBorder('A3:E3', 'thin');
			        $sheet->setBorder('A5:G'.$count, 'thin');
			
			        $sheet->fromArray($arraySC,NULL,'A6',false,false);
			        for($s=6;$s<=$count;$s++)
			        {
				        $sheet->getStyle('E'.$s)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
			        }
			
		        });
		
	        })->download('xlsx');
        }

    }

}
