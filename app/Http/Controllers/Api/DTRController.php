<?php

namespace App\Http\Controllers\Api;

use App\BankAccount;
use App\BankStatement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DTRController extends Controller
{
    //
	public function getbankAcct($bankId)
	{
		$bankAct = BankAccount::findOrfail($bankId);
		
		return "$bankAct->bank - $bankAct->accountno - $bankAct->accountname";
	}
	
	public function getListYear()
	{
		$y  = date("Y");
		$yearData  = Array();
		$yearData[$y] = $y;
		for($x=1;$x<=5;$x++)
		{
			$index      = $y-$x;
			$yearData[$index] =$index;
		}
		
		krsort($yearData);
		//dd($yearData);
		return $yearData;

	}
	
	public function sampleXHR()
	{
		ob_implicit_flush(true);
		ob_flush();
//		for($x=1;$x<=1000;$x++)
//		{
//			echo $x."</br>";
//			usleep(60000);
//		}
		$bank = BankStatement::select('bank_id')->limit(100000)->get();
		foreach($bank as $bs)
		{
			echo $bs->bank_id . "</br>";
		}
	}
	
	public function DTRsaving(Request $request)
	{
//		$this->dtrExcel     = new DTRUploadingExcel();
//		$this->dtrCSV       = new DTRUploadingCSV();
//		$this->dtrExcel2005 = new DTRUploadingExcel2005();
		
		
		$file = $request->file('dtr');

//		foreach($file as $key => $files)
//		{
		
		$filepath = $file->getPathName();
		echo		$filename = $file->getClientOriginalName();
		$extension = \File::extension($filename);


//			DB::transaction(function()use($filepath,$filename,$extension,$request)
//			{
////				if(strtolower($extension)=='xlsx')
////				{
////					$this->dtrExcel->excel($filepath,$filename,$extension,$request);
////				}
//////				elseif(strtolower($extension)=="csv")
//////				{
//////					$this->dtrCSV->CSV($filepath,$filename,$extension,$request);
//////				}
////				else
////				{
//					$this->dtrExcel2005->excel($filepath,$filename,$extension,$request);
////				}
//			});

//		}
//		$bankName = BankAccount::find($request->bankAcct);
//		$bank     = $bankName->bank;
//		$banknoid = $bankName->bankno;
//		$bankno   = BankNo::find($banknoid);
//		$bank_no  = $bankno->bankno;
//
//		$dtr    = DTR::where('bank_account_no',$bank_no)
//			->where('company',$request->com)
//			->where('bu_unit',$request->bu)
//			->orderBy('id','DESC');
//		if($dtr->count('id')>0)
//		{
//			$arrayDTR = ["date"=>date("m/d/Y",strtotime($dtr->first()->bank_date)),"bank_balance"=>number_format($dtr->first()->bank_balance,2)];
//			echo json_encode($arrayDTR);
//		}
		
		
	}
}
