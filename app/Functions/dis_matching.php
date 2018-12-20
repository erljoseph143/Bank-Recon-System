<?php
/**
 * Created by PhpStorm.
 * User: Erljoseph143
 * Date: 5/17/2001
 * Time: 4:55 PM
 */

namespace App\Functions;


use App\BankStatement;
use App\PdcLine;

class dis_matching
{


    function binarysearch($arraycheckno,$findcheckno)
    {
        $start = 0;
        $end   = Count($arraycheckno)-1;

        $start = 0;
        $end = count($arraycheckno)-1;

        while ($start <= $end)
        {

            $mid = (int) floor(($start + $end)/2);

            if ( $arraycheckno[$mid] ==  $findcheckno)
            {
                return $mid;
            }
            elseif ( $findcheckno < $arraycheckno[$mid] )
            {
                $end = $mid-1;
            }
            else
            {
                $start = $mid+1;
            }

        }
        return -1;
    }

    function  realindex($arraycheckno,$findcheckno,$index1)
    {
        if($index1!=0)
        {
            for($x1=$index1;$x1>=0;$x1--)
            {
                if($findcheckno != $arraycheckno[$x1])
                {
                    return $x1 + 1;
                }
            }
        }
        else
        {
            return 0;
        }
    }

    function matchingdis($arraycheckno,$findcheckno,$arraybook,$arraybank)
    {
        //include('session/dbconnect.php');
        $count = count($arraycheckno);
        $index12 = $this->binarysearch($arraycheckno,$findcheckno);
        $realindex = $this->realindex($arraycheckno,$findcheckno,$index12);

        if($index12 !=-1)
        {

//            echo    $count;
//            echo "</br>";
            if($realindex == null)
            {
                $realindex = 0;
            }
            else
            {
                $realindex = $realindex;
            }

            for($i1 = $realindex; $i1<$count;$i1++)
            {

               // echo $findcheckno ." == ". $arraycheckno[$i1] ."</br>";
                //echo "[$realindex] " .$findcheckno ." == ". $arraycheckno[$i1] ."</br>";
                $checkme = $arraycheckno[$i1];
                if($checkme!=null or $checkme!=0)
                {
                    if($findcheckno ==$checkme )
                    {
                        $expl        = explode("|",$arraybook);
                        $bookid      = $expl[0];
                        $checkno     = $expl[1];
                        $cv_date     = $expl[2];
                        $check_date  = $expl[3];
                        $checkamount = $expl[4];

                        $expl2       = explode("|",$arraybank[$i1]);
                        $bankid      = $expl2[0];
                        $bankcheckno = $expl2[1];
                        $bankdate    = $expl2[2];
                        $bankamount  = $expl2[3];
                        //$dba = $this->connect();
                        //	echo $check_date. " match checked </br>";

                        if(trim($checkno) == trim($bankcheckno))
                        {

//                            $dba->query("update bank_statement set label_match='match check' where bank_id=$bankid");
//                            $dba->query("update pdc_line set label_match='match check' where id=$bookid");
                            BankStatement::where('bank_id',$bankid)->update(['label_match'=>'match check']);
                            PdcLine::where('id',$bookid)->where('cv_status','Posted')->update(['label_match'=>'match check']);


                            $bankyear = date('Y',strtotime($bankdate));
                            $pdcyear = date('Y',strtotime($check_date));
                            $bankmonth = date('n',strtotime($bankdate));
                            $pdcmonth = date('n',strtotime($check_date));

                            if(($pdcmonth < $bankmonth and $bankyear == $pdcyear) or ($pdcyear < $bankyear and $pdcmonth > $bankmonth) )
                            {
                                PdcLine::where('id',$bookid)->update(['status'=>'OC','oc_cleared'=>'cleared']);
                              //  $dba->query("update pdc_line set status='OC',oc_cleared='cleared' where id=$bookid");
                            }
                            else
                            {
                                PdcLine::where('id',$bookid)->where('check_no','!=','')->update(['status'=>'']);
                            }
                        }
                        else
                        {
                            PdcLine::where('id',$bookid)->where('check_no','!=','')->update(['status'=>'OC']);
                          // echo $bookid;
                            //$updating = $dba->query("update pdc_line set status='OC' where id=$bookid");
                            //$updating->execute();
                        }

                    }
                }

            }
        }
        else
        {
            PdcLine::where('check_no',$findcheckno)->update(['status'=>'OC']);
        }
    }


}