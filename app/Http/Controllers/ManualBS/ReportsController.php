<?php

namespace App\Http\Controllers\ManualBS;

use App\BankAccount;
use App\BankNo;
use App\Businessunit;
use App\Company;
use App\ManualBs;
use App\PdcLine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public function manualBS_summary(Request $request)
    {
        if($request->ajax())
        {
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
            }
            return view('reports.manualBS_sum',compact('banklist'));
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

            }
            return view('reports.month_bank',compact('data','banklist','bankno','com','bu','type'));
        }
    }


    public function ManualBSExcel($bDate,$bankno,$com,$bu,$bankname,$accountno)
    {
        $datetitle = date('F, Y',strtotime($bDate));
        $type     = "match check and amount";
        $data     = $this->BSdata($type,$bDate,$bankno,$com,$bu);

        $type     = "match check no only";
        $data1    = $this->BSdata($type,$bDate,$bankno,$com,$bu);

        $data3    =  $this->dupCheckEntry($bDate,$bankno,$com,$bu);
        $type     = "unmatch with checkno";
        $unmatch1 = $this->unMatch($type,$bDate,$bankno,$com,$bu);

        $type     = "unmatch without checkno";
        $unmatch2 = $this->unMatch($type,$bDate,$bankno,$com,$bu);

        $com_name = Company::find($com)->company;
        $bu_name  = Businessunit::find($bu)->bname;

        Excel::create("Manual BS DISBURSEMENT - $datetitle - $bankname - $accountno", function($excel) use($data,$data1,$data3,$com_name,$bu_name,$datetitle,$bankname,$accountno,$unmatch1,$unmatch2) {

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
                $sheet->setBorder('F4:J'.$count, 'thin');



                $headings = array('Trans Description', 'Check No.','Bank Statement','Bank Amount','','CV No','Check No','Posting Date','Check Date','Check Amount');

                $sheet->prependRow(5, $headings);
                $sheet->fromArray($data, NULL, 'A6',false,false);
                $sheet->mergecells('A4:J4');
                $sheet->mergecells('A1:J1');
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
                $sheet->setBorder('F4:J'.$count, 'thin');



                $headings = array('Trans Description', 'Check No.','Bank Statement','Bank Amount','','CV No','Check No','Posting Date','Check Date','Check Amount');

                $sheet->prependRow(5, $headings);

                $sheet->fromArray($data1, NULL, 'A6',false,false);
                $sheet->mergecells('A4:J4');
                $sheet->mergecells('A1:J1');
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

                $datanew = Array();
                foreach($data3 as $d)
                {
                    $exp = explode("|",$d);
                    $datanew [] = [$exp[0],$exp[1],$exp[2],$exp[3],$exp[4],$exp[5],$exp[6],$exp[7],$exp[8],$exp[9],$exp[10]];
                }
                $sheet->setOrientation('landscape');
                $count = count($datanew) + 5;

                $sheet->setBorder('A4:K'.$count, 'thin');
                //$sheet->setBorder('F4:J'.$count, 'thin');



                $headings = array('Trans Description', 'Check No.','Bank Statement','Bank Amount','Total in Bank','CV No','Check No','Posting Date','Check Date','Check Amount','Total in Book');

                $sheet->prependRow(5, $headings);

                $sheet->fromArray($datanew, NULL, 'A6',false,false);
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
                $sheet->setBorder('F4:J'.$count, 'thin');



                $headings = array('Trans Description', 'Check No.','Bank Statement','Bank Amount','','CV No','Check No','Posting Date','Check Date','Check Amount');

                $sheet->prependRow(5, $headings);

                $sheet->fromArray($unmatch1, NULL, 'A6',false,false);
                $sheet->mergecells('A4:J4');
                $sheet->mergecells('A1:J1');
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
                $sheet->setBorder('F4:J'.$count, 'thin');



                $headings = array('Trans Description', 'Check No.','Bank Statement','Bank Amount','','CV No','Check No','Posting Date','Check Date','Check Amount');

                $sheet->prependRow(5, $headings);

                $sheet->fromArray($unmatch2, NULL, 'A6',false,false);
                $sheet->mergecells('A4:J4');
                $sheet->mergecells('A1:J1');
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
        })->download('xlsx');
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

        $bs = ManualBs::select('bank_date', 'bank_amount', 'description', 'bank_check_no')
            ->whereMonth('bank_date', $month)
            ->whereYear('bank_date', $year)
            ->where('company', $com)
            ->where('type', 'AP')
            ->where('bu_unit', $bu)
            ->where('bank_account_no',$bankno)
            ->where('status_matching','match check');

        if($bs->count('bank_id') > 0)
        {

            foreach ($bs->get() as $b)
            {
                //             $b->bank_date . " = ".  $b->bank_amount ."</br>";
                $bsamount = $b->bank_amount;
                $bscheckno = $b->bank_check_no;
                if($type == "match check and amount")
                {
                    $countBook = PdcLine::where('check_no', "$bscheckno")
                        ->where("check_amount", "$bsamount")
                        ->where('company', $com)
                        ->where('bu_unit', $bu)
                        ->where('baccount_no', $bankno)
                        ->where('status_matching','match check')
                        ->count('id');

                    if ($countBook == 1)
                    {
                        $book = PdcLine::select('cv_date', 'check_date', 'check_no', 'check_amount','cv_no')
                            ->where('check_no', "$bscheckno")
                            ->where("check_amount", "$bsamount")
                            ->where('company', $com)
                            ->where('bu_unit', $bu)
                            ->where('baccount_no',$bankno)
                            ->where('status_matching','match check')
                            ->get();

                        $key = Array('cv_date','check_date','check_no','check_amount','cv_no');
                        $value = Array();
                        foreach($book as $bk)
                        {
                            $value = Array($bk->cv_date, $bk->check_date, $bk->check_no,$bk->check_amount, $bk->cv_no);
                        }
                        $new_value = array_combine($key,$value);

                        $array[] = [
                            $b->description,
                            $b->bank_check_no,
                            date("m/d/Y", strtotime($b->bank_date)),
                            number_format($b->bank_amount,2),
                            '     ',
                            $new_value['cv_no'],
                            $new_value['check_no'],
                            date("m/d/Y", strtotime($new_value['cv_date'])),
                            date("m/d/Y", strtotime($new_value['check_date'])),
                            number_format($new_value['check_amount'],2)

                        ];

                    }
                }
                elseif($type =="match check no only")
                {
                    $countBook = PdcLine::where('check_no', "$bscheckno")
                        ->where("check_amount","!=","$bsamount")
                        ->where('company', $com)
                        ->where('bu_unit', $bu)
                        ->where('baccount_no', $bankno)
                        ->where('status_matching','match check')
                        ->count('id');

                    if ($countBook == 1)
                    {
                        $book = PdcLine::select('cv_date', 'check_date', 'check_no', 'check_amount','cv_no')
                            ->where('check_no', "$bscheckno")
                            ->where("check_amount","!=","$bsamount")
                            ->where('company', $com)
                            ->where('bu_unit', $bu)
                            ->where('baccount_no', $bankno)
                            ->where('status_matching','match check')
                            ->get();

                        $key = Array('cv_date','check_date','check_no','check_amount','cv_no');
                        $value = Array();
                        foreach($book as $bk)
                        {
                            $value = Array($bk->cv_date, $bk->check_date, $bk->check_no,$bk->check_amount, $bk->cv_no);
                        }
                        $new_value = array_combine($key,$value);

                        $array[] = [
                            $b->description,
                            $b->bank_check_no,
                            date("m/d/Y", strtotime($b->bank_date)),
                            number_format($b->bank_amount,2),
                            '     ',
                            $new_value['cv_no'],
                            $new_value['check_no'],
                            date("m/d/Y", strtotime($new_value['cv_date'])),
                            date("m/d/Y", strtotime($new_value['check_date'])),
                            number_format($new_value['check_amount'],2)

                        ];
                    }
                }
            }
        }
        if($type =="match check and amount" or $type =="match check no only")
        {
            return $array;
        }


    }

    function  dupCheckEntry($bDate,$bankno,$com,$bu)
    {
        $month  = date('m',strtotime($bDate));
        $year   = date('Y',strtotime($bDate));
        $bankArray = Array();
        $dup       ="";
        $bookarray  = Array();
        $bookamtsum = 0;
        $allentry   = Array();

        $bookarray1 = Array();
        $bankarray1 = Array();

        $bs = ManualBs::select('bank_date','bank_check_no','bank_amount','description')
            ->whereMonth('bank_date', $month)
            ->whereYear('bank_date', $year)
            ->where('company', $com)
            ->where('type', 'AP')
            ->where('bu_unit', $bu)
            ->where('bank_account_no',$bankno)
            ->where('status_matching','match check');
        if($bs->count('bank_id') > 0)
        {

            foreach ($bs->get() as $b)
            {
                $bsdate   = $b->bank_date;
                $bscheck  = $b->bank_check_no;
                $bsamount = $b->bank_amount;
                $bsdes    = $b->description;

                $countbs  = $bs->where('bank_amount',$bsamount)->where('bank_check_no',"$bscheck")->count('bank_id');
                $countbk = PdcLine::select('cv_date', 'check_date', 'check_no', 'check_amount','cv_no')
                    ->where('check_no', "$bscheck")
                    ->where("check_amount","!=","$bsamount")
                    ->where('company', $com)
                    ->where('bu_unit', $bu)
                    ->where('baccount_no',$bankno)
                    ->where('status_matching','match check')
                    ->count('id');

                if($countbk > $countbs)
                {
                    $bankArray[] = number_format($bsamount,2)."|".$bscheck."|".date("m/d/Y",strtotime($bsdate))."|".$bsdes."| ";
                    $loop        = $countbk;
                    for($i=1;$i<$loop;$i++)
                    {
                        $bankArray[] = " | | | | ";
                        $dup         = "Duplicate Entry in Book";
                    }
                    $bankArray[]="|Total Amount:| | | ";
                }
            }


            foreach ($bankArray as $key => $bank)
            {
                $explod   = explode("|",$bank);
                $amount   = $explod[0];
                $checkno  = $explod[1];
                $bankdate = $explod[2];
                $transdes = $explod[3];
                if($checkno!=" ")
                {
                    $book = PdcLine::select('cv_date', 'check_date', 'check_no', 'check_amount','cv_no')
                        ->where('check_no', "$checkno")
                        ->where("check_amount","!=","$amount")
                        ->where('company', $com)
                        ->where('bu_unit', $bu)
                        ->where('baccount_no',$bankno)
                        ->where('status_matching','match check');

                    if($book->count('id') > 1)
                    {
                        foreach ($book->get() as $row)
                        {
                            $checknonew   = $row->check_no;
                            $amountnew    = $row->check_amount;
                            $cv_date      = $row->cv_date;
                            $check_date   = $row->check_date;
                            $cv_no		  = $row->cv_no;
                            $bookarray[]  = $cv_no."|".$checknonew."|".date("m/d/Y",strtotime($cv_date))."|".date("m/d/Y",strtotime($check_date))."|".number_format($amountnew,2)."| ";
                        }

                        $sum = PdcLine::where('check_no', "$checkno")
                            ->where("check_amount","!=","$amount")
                            ->where('company', $com)
                            ->where('bu_unit', $bu)
                            ->where('baccount_no',$bankno)
                            ->where('status_matching','match check')->sum('check_amount');

                        $booksum = $sum;
                        // dd($booksum);
                        if($booksum !=0)
                        {
                            $bookarray[] = " | | | | |" . number_format($booksum,2);
                        }

                    }
                }
            }
            foreach($bankArray  as $key => $value)
            {
                //	var_dump($value,$bookarray[$key]);
                $explod    = explode("|",$value);
                $amount    = $explod[0];
                $checkno   = $explod[1];
                $bankdate  = $explod[2];
                $transdes  = $explod[3];
                $totalbank = $explod[4];

                $book      = explode("|",$bookarray[$key]);
                $cv_no     = $book[0];
                $checkbook = $book[1];
                $cv_date   = $book[2];
                $checkdate = $book[3];
                $bookamt   = $book[4];
                $totalbook = $book[5];

                $allentry[] = $transdes ."|".$checkno."|".$bankdate."|".$amount."|".$totalbank."|".$cv_no."|".$checkbook."|".$cv_date."|".$checkdate."|".$bookamt."|".$totalbook;

            }
        }


        /*
          ------------------------------------------START BANK DUPLICATE ENTRY--------------------------------------------------
        */
//        $book = PdcLine::select('cv_date', 'check_date', 'check_no', 'check_amount','cv_no')
//            ->whereMonth('cv_date',$month)
//            ->whereYear('cv_date',$year)
//            ->where('company', $com)
//            ->where('bu_unit', $bu)
//            ->where('baccount_no',$bankno)
//            ->where('status_matching','match check');
//        if($book->count('id') > 0)
//        {
//
//            foreach($book->get() as $bk)
//            {
//                $amount        = $bk->check_amount;
//                $checkno       = $bk->check_no;
//                $cv_date       = $bk->cv_date;
//                $checkdate     = $bk->check_date;
//                $cv_no		   = $bk->cv_no;
//
//                $countbk  = $book->where('check_amount',$amount)->where('check_no',"$checkno")->count('id');
//                $countbs  = ManualBs::select('bank_date', 'bank_check_no', 'description', 'bank_amount')
//                    ->where('bank_check_no', "$checkno")
//                 //   ->where("bank_amount","$amount")
//                    ->where('company', $com)
//                    ->where('bu_unit', $bu)
//                    ->where('bank_account_no',$bankno)
//                    ->where('status_matching','match check')
//                    ->count('bank_id');
//
//                if($countbs > $countbk)
//                {
//                    $bookarray1[] = $cv_no."|".number_format($amount,2)."|".$checkno."|".date("m/d/Y",strtotime($cv_date))."|".date("m/d/Y",strtotime($checkdate))."| ";
//                    $loop         = $countbs;
//                    for($i=1;$i<$loop;$i++)
//                    {
//                        $bookarray1[] = " | | | | | ";
//                        $dup          = "Duplicate Entry in Bank";
//                    }
//                    $bookarray1[]=" | | | | | ";
//                }
//
//
//                foreach($bookarray1 as $key => $value)
//                {
//                    $explod = explode("|",$value);
//                    $cv_no  = $explod[0];
//                    $amount = $explod[1];
//                    $checkno= $explod[2];
//                    //$count  = $explod[2];
//                    if($checkno!=" ")
//                    {
//
//
//                        $book       = ManualBs::select('bank_date','bank_amount','bank_check_no','description')
//                            ->where('bank_check_no',"$checkno")
//                            ->where('bank_amount',"$amount")
//                            ->where('bank_account_no',$bankno);
//                        $counts     = $book->count('bank_id');
//                        if($counts > 0)
//                        {
//                            foreach($book->get() as $row)
//                            {
//                                $checknonew  = $row->bank_check_no;
//                                $amountnew   = $row->bank_amount;
//                                $bankdate    = $row->bank_date;
//                                $transdes    = $row->description;
//
//                                $bankarray1[] = $transdes."|".$checknonew."|".date("m/d/Y",strtotime($bankdate))."|".number_format($amountnew,2)."| ";
//                            }
//
//                            $sumamt       = $book->sum('bank_amount');
//
//                            $bankamtsum  = $sumamt;
//                            if($bankamtsum != 0)
//                            {
//                                $bankarray1[] = " |Total Amount:| | | " . number_format($bankamtsum,2);
//
//                            }
//
//                        }
//
//                    }
//
//
//                }
////dd($book->get());
//                foreach($bookarray1  as $key => $value)
//                {
//                    //	var_dump($value,$bookarray[$key]);
//                   // $bookarray1[] = $cv_no."|".number_format($amount,2)."|".$checkno."|".date("m/d/Y",strtotime($cv_date))."|".date("m/d/Y",strtotime($checkdate))."| ";
//                    $explod    = explode("|",$value);
//                    $cv_no     = $explod[0];
//                    $bookamt   = $explod[1];
//                    $checkbook = $explod[2];
//                    $cv_date   = $explod[3];
//                    $checkdate = $explod[4];
//                    $totalbook = $explod[5];
//
//                    $bank      = explode("|",$bankarray1[$key]);
//                    $amount    = $bank[3];
//                    $checkno   = $bank[1];
//                    $bankdate  = $bank[2];
//                    $transdes  = $bank[0];
//                    $totalbank = $bank[4];
//
//                    $allentry[] = $transdes ."|".$checkno."|".$bankdate."|".$amount."|".$totalbank."|".$cv_no."|".$checkbook."|".$cv_date."|".$checkdate."|".$bookamt."|".$totalbook;
//
//                }
//
//
//
//                /*
//                    ----------------------------END BANK DUPLICATE ENTRY-----------------------------------------------------------------
//                 */
//            }
//        }
        return $allentry;
    }

    public function unMatch($type,$bDate,$bankno,$com,$bu)
    {
        $month = date('m',strtotime($bDate));
        $year  = date('Y',strtotime($bDate));

        $bank = ManualBs::select('bank_date', 'bank_amount', 'description', 'bank_check_no')
            ->whereMonth('bank_date', $month)
            ->whereYear('bank_date', $year)
            ->where('company', $com)
            ->where('type', 'AP')
            ->where('bu_unit', $bu)
            ->where('bank_account_no',$bankno);
        $book = PdcLine::select('cv_date', 'check_date', 'check_no', 'check_amount','cv_no')
            ->whereMonth('cv_date', $month)
            ->whereYear("cv_date",$year)
            ->where('company', $com)
            ->where('bu_unit', $bu)
            ->where('baccount_no',$bankno);
        $array   = Array();
        $bsArray = Array();
        $bkArray = Array();
        if($type == "unmatch with checkno")
        {
            foreach($bank->where('bank_check_no','!=','')->where('status_matching','')->get() as $bS)
            {
                $bsArray[] = $bS->description."|".$bS->bank_check_no."|".date("m/d/Y",strtotime($bS->bank_date))."|".number_format($bS->bank_amount,2)."|".' ';
            }

            foreach($book->where('check_no','!=','')->where('status_matching','')->get() as $bK)
            {
                $bkArray[] = $bK->cv_no."|".$bK->check_no."|".date("m/d/Y",strtotime($bK->cv_date))."|".date("m/d/Y",strtotime($bK->check_date))."|".number_format($bK->check_amount,2);
            }

            return $this->normalizeArray($bsArray,$bkArray);
        }
        else
        {
            foreach($bank->where('bank_check_no','')->where('status_matching','')->get() as $bS)
            {
                $bsArray[] = $bS->description."|".$bS->bank_check_no."|".date("m/d/Y",strtotime($bS->bank_date))."|".number_format($bS->bank_amount,2)."|".' ';
            }

            foreach($book->where('check_no','')->where('status_matching','')->get() as $bK)
            {
                $bkArray[] = $bK->cv_no."|".$bK->check_no."|".date("m/d/Y",strtotime($bK->cv_date))."|".date("m/d/Y",strtotime($bK->check_date))."|".number_format($bK->check_amount,2);
            }

            return $this->normalizeArray($bsArray,$bkArray);
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
}
