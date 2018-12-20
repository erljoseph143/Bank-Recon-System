<?php

namespace App\Http\Controllers\Designex;

use App\BankStatement;
use App\CVDesignex;
use App\Functions\dis_matching;
use App\Functions\Progress;
use App\PdcLine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CVController extends Controller
{
    protected $com;
    protected $bu;
    protected $totalRow;
    protected $bank_number;
    protected $cv_date_me;
    protected $progressBar;
    protected $x;
    protected $bank_no;
    protected $dismatching;

    public function __construct()
    {
        $this->progressBar = new Progress();
        echo view('layouts.progressbar');
        echo view('layouts.bsRadar');
        $this->progressBar->initprogress();
        $this->x = 1;
        $this->dismatching = new dis_matching();


    }



    public function store(Request $request)
    {
        $this->com = Auth::user()->company_id;
        $this->bu  = Auth::user()->bunitid;
        $file =  $request->file('mainfiles1excel');

        foreach($file as $files)
        {

            $data = $this->regex($files->getPathName());
//dd($data);
            $this->progressBar->settotalvalues(count($data));
            DB::transaction(function ()use($data) {
                foreach($data as $d)
                {
                    $this->bank_no = $d['bcode'];
                    $this->progressBar->setprogress($this->x);
                    $this->progressBar->displayprogress('true');
                    $getPercent = $this->progressBar->getpercentrounded();
                    CVDesignex::updateOrCreate([
                        'cv_no'=>$d['cv_no'],
                        'cv_date'=>date("Y-m-d",strtotime($d['cv_date'])),
                        'check_date'=>date("Y-m-d",strtotime($d['ckdate'])),
                        'check_no'=>$d['checkno'],
                        'check_amount'=>str_replace(",","",$d['amount']),
                        'bcode'=>$d['bcode'],
                        'bdesc'=>$d['bdesc'],
                        'des'=>$d['desc'],
                        'payee'=>$d['payee'],
                        'company'=>$this->com,
                        'bu_unit'=>$this->bu,
                    ]);

                    PdcLine::updateOrCreate([
                        'cv_no'=>$d['cv_no'],
                        'cv_status'=>'Posted',
                        'cv_date'=>date("Y-m-d",strtotime($d['cv_date'])),
                        'check_no'=>$d['checkno'],
                        'check_amount'=>str_replace(",","",$d['amount']),
                        'baccount_no'=>$d['bcode'],
                        'check_date'=>date("Y-m-d",strtotime($d['ckdate'])),
                        'payee'=>$d['payee'],
                        'bu_unit'=>$this->bu,
                        'company'=>$this->com,
                        'company_code'=>$this->com
                    ]);
                    echo "<script>   
                        $('#mid-radar-process').css({
                            'transform': 'rotate(' + $this->x + 'deg)',
                            '-moz-transform': 'rotate(' + $this->x+ 'deg)',
                            '-o-transform': 'rotate(' + $this->x + 'deg)',
                            '-webkit-transform': 'rotate(' + $this->x + 'deg)'                  
                        });
                    </script>";
                    $this->x++;
                }
            });

        }

        echo '<script>
               $(".absolute-left").removeClass("hidden");
                $(".margin-process-left").fadeOut("slow");
                $(".margin-process-left").fadeIn("slow");
                $(".margin-process-left").addClass("margin-process-right");
                $(".margin-process-left").removeClass("margin-process-left");
                $(".margin-process-left").removeClass("margin-process-left");
                $(".absolute-right").addClass("hidden");
                $("#loadnow").animate({width:"100%"});
                $(".mid-radar-grid-left").fadeOut("slow");
                $(".mid-radar-grid-left").fadeIn("slow");
                $(".mid-radar-grid-left").css("background-image","url(\'css/check.png\')");
				$("#tag-process").html("Disbursement Matching");
	          </script>';

        $bookcheckno = Array();
        $pdc_line = PdcLine::select('id','cv_date','check_date','check_amount',DB::raw('CAST(check_no as UNSIGNED) as check_no'))
            ->where('label_match','!=','match check')
            ->where('cv_status','Posted')
            ->where('baccount_no',$this->bank_no)
            ->where('bu_unit',$this->bu)
            ->where('company_code',$this->com)
            //->whereYear('cv_date',$request->year)
            ->orderBy('check_no','ASC')
            ->get();

        //    dd($pdc_line->all());
        foreach ($pdc_line as $row)
        {
            $bookcheckno[] = $row->id."|".$row->check_no."|".$row->cv_date."|".$row->check_date."|".$row->check_amount;
        }

        $bankcheckno = Array();
        $bank_dis = BankStatement::select('bank_id','bank_date','bank_amount',DB::raw('CAST(bank_check_no as UNSIGNED) as bank_check_no'))
            ->where('label_match','!=','match check')
            ->where('bank_account_no',$this->bank_no)
            ->where('company',$this->com)
            ->where('bu_unit',$this->bu)
            ->orderBy('bank_check_no','ASC')
            ->get();

        foreach ($bank_dis as $row1)
        {
            $bankcheckno[] = $row1->bank_id."|".$row1->bank_check_no."|".$row1->bank_date."|".$row1->bank_amount;
        }

        $arraycheckno = array();
        foreach($bankcheckno as $key => $value1)
        {
            $exp  = explode("|",$value1);
            $checkno_only = $exp[1];
            $arraycheckno[] = $checkno_only;
        }

        DB::transaction(function()use($bookcheckno,$arraycheckno,$bankcheckno){
            $this->progressBar->settotalvalues(count($bookcheckno));
            $rowme = 1;
            foreach($bookcheckno as $key => $arraybook)
            {
                $this->progressBar->setprogress($rowme);
                $this->progressBar->displayprogress();
                $getPercent = $this->progressBar->getpercentrounded();

                $expl  = explode("|",$arraybook);
                $checkno = $expl[1];

                $this->dismatching->matchingdis($arraycheckno,$checkno,$arraybook,$bankcheckno);

                echo  "<script>

            $('#mid-radar-process').css({
                                        'transform': 'rotate(' + $rowme + 'deg)',
            '-moz-transform': 'rotate(' + $rowme + 'deg)',
            '-o-transform': 'rotate(' + $rowme + 'deg)',
            '-webkit-transform': 'rotate(' + $rowme + 'deg)'
            
            });
            
            </script>";
                $rowme++;
            }
        });


        echo 	'<script>
    $(".absolute-right").removeClass("hidden");
		$(".margin-process-right").fadeOut("slow");
		$(".margin-process-right").fadeIn("slow");

		$(".margin-process-right").addClass("hidden");
	
		$(".mid-radar-grid-right").fadeOut("slow");
		$(".mid-radar-grid-right").fadeIn("slow");
		$(".mid-radar-grid-right").css("background-image","url(\'css/check.png\')");
		

		$("#tag-right").html("Done Disbursement </br>Matching");
	</script>';

        $title = "BRS Uploading";
        $message = "Check Voucher Data Uploaded Successfully";
        echo view('layouts.doneUploading',compact('title','message'));

    }

    public function regex($file)
    {
        $path = $file;
        $content = file_get_contents($path);
        //unlink($path);

        $content = preg_replace('/\n\S/i', "\n", $content);
        $content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content);
        //$content = preg_replace("");
        $content = rtrim($content);
        // $pattern = '/\s{19}\d{2}\/\d{2}\/\d{4}.*\d{1,3}\.\d{2}(\n[^>]*\n){1,}.*(\n\s{19}[^\d].*){1,}.*/i';
        $pattern = '/\s{19}\d{2}\/\d{2}\/\d{4}.*\d{1,3}\.\d{2}(\n[^>]*\n){1,}.*(\n\s{19}[^\d]){1,}.*/i';
        preg_match_all($pattern, $content, $matches);
      //  ini_set("pcre.recursion_limit", 280);
//        $content = preg_replace('/\n\S/i', "\n", $content);
//        $content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content);
//        $content = preg_replace("/\n\s{19,20}[a-z]+.*/i", "", $content);
//        $content = rtrim($content);
        //file_put_contents('./uploads/parsed/full.txt', $content);



//        $pattern = '/\n\s{19}[^\s]{1,10}.*/';
//        preg_match_all($pattern, $content, $matches);
//        $details = preg_split($pattern,$content);
//        // file_put_contents('./uploads/temp.txt', $matches[0][4]);
       // dd($matches);
//     dd($details[1]);
//        preg_match_all('/\n\s{40}\d{2}.*/i',$details[1][1],$newmatch);
//        dd($newmatch);
        //  dd($this->check_vouchers($matches[0]));
        // echo $split[1];
        return $this->check_vouchers($matches[0]);
    }

    private function check_vouchers($data)
    {
        $cvs = array();
        foreach ($data as $d)
        {
            $cvs[] = array('cv_date' => substr($d, 19, 10),
                'cv_no' => substr($d, 30, 8),
                'payee' => trim(substr($d, 39, 30)),
                'desc' => $this->description($d),
                'checkno' => $this->check_detail(substr($d, 71, 35), '/-\s*\d{4,10}\s*-/i'),
                'ckdate' => $this->check_detail(substr($d, 71, 35), '/\d{2}\/\d{2}\/\d{4}/i'),
                'bcode' => $this->bank_detail($d)['code'],
                'bdesc' => $this->bank_detail($d)['desc'],
                'amount' => trim(substr($d, 109, 15)));
        }
        return $cvs;
    }

    private function description($data)
    {
        $desc = '';
        if (preg_match_all('/(\s{39}[^\s].*\n){1,3}.*/i', $data, $matches) > 0)
        {
            $desc = preg_replace('/\s{39}\d{2}\..*/i', '', $matches[0][0]);
            $desc = preg_replace('/\s*\n\s{39}/i', ' ', $desc);
            $desc = preg_replace('/-*$/i', '', $desc);
        }
        return trim($desc);
    }

    private function check_detail($data, $pattern)
    {
        $result = '';
        if (preg_match_all($pattern, $data, $matches) > 0)
        {
            $result = preg_replace('/-/i', '', $matches[0][0]);
        }
        return trim($result);
    }

    private function bank_detail($data)
    {
        $bank['code'] = '';
        $bank['desc'] = '';
        if (preg_match_all('/.*$/i', $data, $matches) > 0)
        {
            $bank['code'] = trim(substr($matches[0][0], 63, 8));
            $bank['desc'] = trim(substr($matches[0][0], 72, 35));
        }
        return $bank;
    }
}
