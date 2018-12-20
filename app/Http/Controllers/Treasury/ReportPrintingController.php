<?php

namespace App\Http\Controllers\Treasury;

use Illuminate\Http\Request;
use Codedge\Fpdf\Facades\Fpdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReportPrintingController extends Controller
{
    //
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function logPdf($salesdate,$depositdate)
	{
		$cpoPayment = session()->get('cpo-payment');
		$allcheck = $this->allChecks(session()->get('check-data'),$cpoPayment);

		$summary  = $this->allSummary(session()->get('adjustment-data'));
		$data = $this->mergeAllCheckAndSummary($allcheck,$summary);
		//dd($data);
		ob_get_clean();

//		$exp       = explode("/",base64_decode($data));
//		$bankno    = $exp[0];
//		$depDate   = $exp[1];
//		$salesDate = $exp[2];
//		$bankAcct  = $exp[3];
		
		$com      = Auth::user()->company_id;
		$bu       = Auth::user()->bunitid;
		Fpdf::SetLineWidth(0.1);
		Fpdf::AddPage();
		Fpdf::SetFont('Times','', 12);
		
		
		Fpdf::Cell(180,7,'Alturas Supermarket Corporation',0,'','C');
		Fpdf::ln();
		Fpdf::Cell(180,7,'Island City Mall',0,'','C');
		Fpdf::ln();
		Fpdf::Cell(180,7,'Treasury Log Book',0,'','C');
		
		Fpdf::ln();
		Fpdf::SetFont('Times','B', 10);
		Fpdf::Cell(30, 10, 'Sales Date:');
		Fpdf::Cell(50, 10, date("F d, Y",strtotime($salesdate)));
		
		Fpdf::Cell(30,10, '');
		Fpdf::Cell(30, 10, 'Deposit Date:');
		Fpdf::Cell(50, 10, date("F d, Y",strtotime($depositdate)));
		Fpdf::ln();
		
		Fpdf::Cell(50,7,'DESCRIPTION',1,'','C');
		Fpdf::Cell(20,7,'Trans Type',1,'','C');
		Fpdf::Cell(30,7,'AMOUNT',1,'','C');
		
		Fpdf::Cell(10,7,'',0);
		Fpdf::Cell(50,7,'DESCRIPTION',1,'','C');
		//Fpdf::Cell(30,7,'DS No.',1,'','C');
		Fpdf::Cell(30,7,'AMOUNT',1,'','C');
		Fpdf::ln();
		Fpdf::SetFont('Times','', 10);
		foreach($data as $key => $d)
		{
			if($d->check_amt_total!='' and $d->check_amt_total!='')
			{
				$borderCheck = 1;
			}
			else
			{
				$borderCheck = 0;
			}
			Fpdf::Cell(50,7,$d->check_class,$borderCheck,'','L');
			Fpdf::Cell(20,7,$d->check_from,$borderCheck,'','L');
			Fpdf::Cell(30,7,$d->check_amt_total!=''? number_format($d->check_amt_total,2):'',$borderCheck,'','R');
			
			Fpdf::Cell(10,7,'',0);
			
			if($d->type!='' and $d->amount!='')
			{
				$borderSum = 1;
			}
			else
			{
				$borderSum = 0;
			}
			
			Fpdf::Cell(50,7,$d->type,$borderSum,'','L');
			//Fpdf::Cell(30,7,'DS No.',1,'','C');
			Fpdf::Cell(30,7,$d->amount!=''?number_format($d->amount,2):'',$borderSum,'','R');
			Fpdf::ln();
		}
		
		Fpdf::ln(25);
		
		Fpdf::SetFont('Times','B', 11);
		Fpdf::Cell(50,7,'DESCRIPTION',1,'','C');
		//Fpdf::Cell(40,7,'SalesDate',1,'','C');
		Fpdf::Cell(40,7,'DS No',1,'','C');
		Fpdf::Cell(50,7,'AMOUNT SM',1,'','C');
		Fpdf::Cell(50,7,'FINAL AMOUNT',1,'','C');
		Fpdf::ln();

		$total = 0;

		Fpdf::SetFont('Times','', 11);
		foreach(session()->get('sales-data') as $key=> $cash):
			if($cash->final_amount!='')
			{
				$total += $cash->final_amount;
			}

			Fpdf::Cell(50,7,$cash->ds_no!=''?$cash->description:'',1,'','L');
			Fpdf::Cell(40,7,$cash->ds_no!=''?'DS '. $cash->ds_no:$cash->description,1,'',$cash->ds_no!=''?'L':'R');
			Fpdf::Cell(50,7,$cash->amount_sm!=''?number_format($cash->amount_sm,2):'',1,'','R');
			Fpdf::Cell(50,7,$cash->final_amount!=''?number_format($cash->final_amount,2):'',1,'','R');
			Fpdf::ln();
		endforeach;
		Fpdf::Cell(140,7,'Total Cash: ',1,'B','R');
		Fpdf::Cell(50,7,number_format($total,2),1,'B','R');
		
		Fpdf::Output();
	}
	
	public function allChecks($checkData,$cpoPayment)
	{
		$dataCheck = Array();
		$total     = 0;
		 foreach($checkData as $key => $check)
		 {
		    $total +=$check->check_amt_total;
		    $dataCheck[] = (object)[
						        'check_class'=> $check->check_class,
							    'check_from'=> $check->check_from,
							    'check_amt_total'=>$check->check_amt_total
		                   ];
		 }
		 foreach($cpoPayment as $key => $cpl)
		 {
		 	$total +=$cpl->check_amount;
			$dataCheck[] = (object)[
				'check_class'=> 'CPO Payment for '.date("m/d/Y",strtotime($cpl->cpo_date)),
				'check_from'=> $cpl->check_no,
				'check_amt_total'=>$cpl->check_amount
			];
		 }
		 $dataCheck[] = (object)[
						     'check_class'=> 'Total',
							 'check_from'=> '',
							 'check_amt_total'=>$total
		                ];
		 return $dataCheck;
	}
	
	public function allSummary($cashSum)
	{
		$total   = 0;
		$sumData = Array();
		foreach($cashSum as $key => $c)
		{
			$total += $c[2];
			$sumData[] = (object)[
				'number'=>$c[0],
				'description'=>$c[1],
				'amount'=>$c[2]
			];
		}
		$sumData[] = (object)[
			'number'=>'',
			'description'=>'Total',
			'amount'=>$total
		];
		
		return $sumData;
	}
	
	public function mergeAllCheckAndSummary($allcheck,$summary)
	{
		$countCheck = count($allcheck);
		$countSum   = count($summary);
		$newArray = Array();
		if($countCheck > $countSum)
		{
			
			foreach($allcheck as $key => $check)
			{
				
				if(array_key_exists($key,$summary))
				{
						$newArray[] = (object)[
							'check_class'=>$check->check_class,
							'check_from'=>$check->check_from,
							'check_amt_total'=>$check->check_amt_total,
							'boundary'=>'',
							'type'=>$summary[$key]->description,
							'amount'=>$summary[$key]->amount
					];
				}
				else
				{
					$newArray[] = (object)[
						'check_class'=>$check->check_class,
						'check_from'=>$check->check_from,
						'check_amt_total'=>$check->check_amt_total,
						'boundary'=>'',
						'type'=>'',
						'amount'=>''
					];
				}

			}
		}
		else
		{
			foreach($summary as $key => $sum)
			{
				if(array_key_exists($key,$allcheck))
				{
					$newArray[] = (object)[
						'check_class'=>$allcheck[$key]->check_class,
						'check_from'=>$allcheck[$key]->check_from,
						'check_amt_total'=>$allcheck[$key]->check_amt_total,
						'boundary'=>'',
						'type'=>$sum->description,
						'amount'=>$sum->amount
					];
				}
				else
				{
					$newArray[] = (object)[
						'check_class'=>'',
						'check_from'=>'',
						'check_amt_total'=>'',
						'boundary'=>'',
						'type'=>$sum->description,
						'amount'=>$sum->amount
					];
				}
				
			}
		}
		
		return $newArray;
	}
}
