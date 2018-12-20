<?php

namespace App\Http\Controllers\Designex;

use App\Bank;
use App\Bankaccount;
use App\BankNo;
use App\Bankstatement;
use App\Businessunit;
use App\Company;
use App\DxSubsidiaryLedger;
use App\DxTransaction;
use App\Functions\UtilityClass;
use App\Http\Traits\FormatExcel;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FiledataController extends Controller
{
    use FormatExcel;

    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index(Request $request) {
        $title = "Designex File data";
        $ptitle = "File Data";

        $banks = DxSubsidiaryLedger::joinT()
            ->getCodes([$request->user()->bunitid])
            ->get(['dx_subsidiaryledgers.ledger_code', DB::raw('COUNT(dx_subsidiaryledgers.ledger_code) as data_count')]);

        if ($banks->count()) {
            $lists = DxSubsidiaryLedger::joinT()
            ->getDate([$banks[0]->ledger_code]);
        } else {
            $lists = [];
        }
//        dd($lists);
        if ($request->ajax()) {
            $lists = DxSubsidiaryLedger::joinT()
                ->getDate([$request->bank]);
            $data = view('designex.file-data.table', compact('lists'))->render();

            return response()->json(['data'=> $data]);
        }

        return view('designex.file-data.index', compact('title', 'ptitle', 'banks', 'lists'));
    }

    public function store(Request $request)
    {
        set_time_limit(0);
        $date = explode('-', date('m-Y', strtotime($request->doc_date)));

        $bs = Bankstatement::where('bank_account_no', $request->code)
            ->notMatch([$request->user()->bunitid])
            ->where('bank_check_no', '!=', '')
            ->orderBy(DB::raw('CAST(bank_check_no AS UNSIGNED)'), 'ASC')
            ->pluck('bank_check_no');
//            ->get(['bank_check_no']);

        $trs = DxSubsidiaryLedger::select('tr.id', 'tr.check_no', 'tr.check_bank', 'dx_subsidiaryledgers.ledger_code')
            ->joinT()
            ->where('dx_subsidiaryledgers.ledger_code', $request->code)
            ->whereMonth('dx_subsidiaryledgers.doc_date', $date[0])
            ->whereYear('dx_subsidiaryledgers.doc_date', $date[1])
            ->where('dx_subsidiaryledgers.buid', $request->user()->bunitid);

        $percents = UtilityClass::percents($trs->count()-1);

        //PDC
        $dxPdc = DxSubsidiaryLedger::select('tr.id')
            ->joinT()
            ->where('dx_subsidiaryledgers.ledger_code', $request->code)
            ->where('dx_subsidiaryledgers.buid', $request->user()->bunitid)
            ->whereMonth('dx_subsidiaryledgers.doc_date', $date[0])
            ->whereYear('dx_subsidiaryledgers.doc_date', $date[1])
            ->whereYear('tr.doc_date', '<=', DB::raw('YEAR(tr.check_date)'))
            ->whereRaw("
                IF(MONTH(tr.doc_date)=12,MONTH(tr.doc_date) > MONTH(tr.check_date),MONTH(tr.doc_date) < MONTH(tr.check_date))            
            ");
//            ->whereMonth('tr.doc_date', '<', DB::raw('MONTH(tr.check_date)'));

        if ($bs->count()) {
            try {

                DB::beginTransaction();

                foreach ($dxPdc->get() as $key => $pdc) {
                    DxTransaction::where('id', $pdc->id)
                        ->update(['is_pdc' => 1]);
                }

                foreach ($trs->get() as $key => $tr) {

                    if ($bs->contains($tr->check_no)) {
//                    if ($bs->contains('bank_check_no', $tr->check_no)) {
                        Bankstatement::where('bank_check_no', $tr->check_no)
                            ->where('bank_account_no', $request->code)
                            ->notMatch([$request->user()->bunitid])
                            ->update(['label_match' => 'match check']);
                        DxTransaction::where('id', $tr->id)
                            ->where('buid', $request->user()->bunitid)
                            ->where('label_match', '!=', 'match check')
                            ->where('check_no', $tr->check_no)
                            ->update(['label_match' => 'match check', 'code' => $tr->ledger_code]);
                    } else {
                    }
                    if (array_key_exists($key, $percents)) {
                        UtilityClass::notify_progress($percents[$key], 'test');
                    }
                }

                //Unmatch
                DxTransaction::where('label_match', '')
                    ->where('doc_type', 'CV')
                    ->where('check_bank', $request->bank)
                    ->whereMonth('doc_date', $date[0])
                    ->whereYear('doc_date', $date[1])
                    ->update(['is_oc' => 1]);

                DB::commit();
                return json_encode(['a' => 'Done Matching!', 'b' => 'success', 'c' => 'Success', 'd'=>$request->doc_date]);
            } catch (QueryException $exception) {
                DB::rollBack();
                return json_encode(['a' => $exception->errorInfo[2], 'b', 'error']);
            }
        }

        return json_encode(['a' => 'Bank statement not yet uploaded!', 'b' => 'error', 'c' =>'Error']);

    }

    public function create(Request $request) {

        set_time_limit(0);
        ob_implicit_flush(true);
        ob_end_flush();

        $dateT = explode('-', date('m-Y', strtotime($request->doc_date)));

        try {

            $bscolumns = [
                'Trans Description',
                'Check No',
                'Bank Statement Date',
                'Bank Amount'
            ];

            $dxcolumns = [
                'Document No',
                'Check No',
                'Document Date',
                'Check Date',
                'Check Amount',
                'Status',
                'Payee'
            ];

            $dxdata = DxTransaction::select('doc_no', DB::raw('CAST(check_no AS UNSIGNED) as check_no'),
                'doc_date',
                'check_date',
                'amount', 'status', 'payee')
                ->where('label_match', 'match check')
                ->where('doc_type', 'CV')
                ->whereMonth('doc_date', $dateT[0])
                ->whereYear('doc_date', $dateT[1])
                ->where('buid', $request->user()->bunitid)
                ->where('code', $request->code)
                ->orderBy('check_no');

//            $dxGET = $dxdata->get();
//
//            $dxmod = $dxGET->map(function ($item, $key) {
//
//                return [
//                    'doc_no' => $item->doc_no,
//                    'check_no'  => $item->check_no,
//                    'doc_date'  => $item->doc_date,
//                    'check_date' => $item->check_date,
//                    'amount' => $item->amount,
//                    'status' => $item->status,
//                    'payee' => $item->payee
//                ];
//
//            });

            $bsdata = Bankstatement::select('description', DB::raw('CAST(bank_check_no AS UNSIGNED) as bank_check_no'), 'bank_date', 'bank_amount')
                ->where('label_match', 'match check')
                ->whereMonth('bank_date', $dateT[0])
                ->whereYear('bank_date', $dateT[1])
                ->where('type', 'AP')
                ->where('bank_account_no', $request->code)
                ->where('bu_unit', $request->user()->bunitid)
                ->orderBy('bank_check_no');

            if (count($bsdata->get()->toArray()) == 0 || count($dxdata->get()->toArray()) == 0) {
                return json_encode(['a'=>"Looks like there is no match try matching it again!",'b'=>'error','c'=>'Opps!']);
            }

            $spreadsheet = new Spreadsheet();

            $spreadsheet->setActiveSheetIndex(0);

            $bsDuplicates = collect([]);
            $processedBSCheckno = collect([]);
            $bsSameMonth = collect([]);
            $dxSameMonth = collect([]);
            $bsCheckNo = $bsdata->pluck('bank_check_no');
            $bsAmount = $bsdata->pluck('bank_amount');

            $dxdataAll = $dxdata->get();
            $bsdataAll = $bsdata->get();
            $processedDXCheckno = collect([]);
            $dxDuplicates = collect([]);

            $pmax = $dxdata->count()+$bsdata->count();
            $pcount = 0;

            foreach ($dxdataAll as $key => $dx) {

                $docdate = explode("-", $dx['doc_date']->format('m-Y'));

                if ($dateT[0] === $docdate[0] && $dateT[1] === $docdate[1]) {

                    if ( $bsCheckNo->contains($dx['check_no']) && $bsAmount->contains($dx['amount']) ) {
                        //get bs key
                        $bsKey = $bsCheckNo->search($dx['check_no']);

                        $bankDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($bsdataAll[$bsKey]->bank_date));

                        if ( $bsdataAll[$bsKey]->bank_check_no == $dx['check_no'] && $bsdataAll[$bsKey]->bank_amount == $dx['amount'] ) {
                            $bsSameMonth->push([
                                $bsdataAll[$bsKey]->description,
                                $bsdataAll[$bsKey]->bank_check_no,
                                $bankDate,
                                $bsdataAll[$bsKey]->bank_amount,
                            ]);

                            $docDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($dx['doc_date']));
                            $checKDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($dx['check_date']));

                            $dxSameMonth->push([
                                'doc_no' => $dx['doc_no'],
                                'check_no' => $dx['check_no'],
                                'doc_date' => $docDate,
                                'check_date' => $checKDate,
                                'amount' => $dx['amount'],
                                'status' => $dx['status'],
                                'payee' => $dx['payee'],
                            ]);

                            unset($bsdataAll[$bsKey]);
                            unset($dxdataAll[$key]);

                            $percent = number_format(($pcount/$pmax)*100);

                            echo json_encode(['progress'=>$percent, 'url'=>'', 'd'=>$request->doc_date]);

                            $pcount++;

                        }

                    }

                }

            }

            $this->sheet1($spreadsheet, $request, $bscolumns, $dxcolumns, $bsSameMonth->toArray(), $dxSameMonth->toArray(), $dateT);

            $spreadsheet->createSheet();

            $spreadsheet->setActiveSheetIndex(1);

            $sheet2 = $spreadsheet->getActiveSheet();

            $this->sheetHead($sheet2, $request->doc_date, $bscolumns, $dxcolumns, 'DISBURSEMENT WITH MATCH CHECK NO AND AMOUNT - CV REFLECTED IN NEXT MONTH', 'CV reflected next Month');

            $this->setWidth($sheet2);

            $dxNextDate = collect([]);
            $bsNextDate = collect([]);

            $bsNextMonth = Bankstatement::select('description', 'bank_check_no', 'bank_date', 'bank_amount')
                ->where('bank_account_no', $request->code)
                ->whereMonth('bank_date', '>', $dateT[0])
                ->whereYear('bank_date', '>=', $dateT[1])
                ->where('label_match', 'match check')
                ->where('type', 'AP')
                ->where('bu_unit', $request->user()->bunitid);

            $bsNextMonthCheckNo = $bsNextMonth->pluck('bank_check_no');
            $bsNextMonthAll = $bsNextMonth->get();

            foreach ($dxdataAll as $key => $dx) {

                if ($bsNextMonthCheckNo->count() > 0) {

                    $bsKey = $bsNextMonthCheckNo->search(trim($dx->check_no));

                    $bankDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($bsNextMonthAll[$bsKey]->bank_date));

                    if ($bsKey || $bsKey === 0) {
                        if ($bsNextMonthAll[$bsKey]->bank_check_no == $dx->check_no && $bsNextMonthAll[$bsKey]->bank_amount == $dx->amount) {

                            $docDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($dx['doc_date']));
                            $checKDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($dx['check_date']));

                            $dxNextDate->push([
                                'doc_no' => $dx['doc_no'],
                                'check_no' => $dx['check_no'],
                                'doc_date' => $docDate,
                                'check_date' => $checKDate,
                                'amount' => $dx['amount'],
                                'status' => $dx['status'],
                                'payee' => $dx['payee'],
                            ]);
                            $bsNextDate->push([
                                $bsNextMonthAll[$bsKey]->description,
                                $bsNextMonthAll[$bsKey]->bank_check_no,
                                $bankDate,
                                $bsNextMonthAll[$bsKey]->bank_amount,
                            ]);

                            unset($dxdataAll[$key]);

                            $percent = number_format(($pcount / $pmax) * 100);

                            echo json_encode(['progress' => $percent, 'url' => '', 'd' => $request->doc_date]);

                            $pcount++;

                        }
                    }
                }
            }

            $sheet2->fromArray($bsNextDate->toArray(), NULL, 'A5')
                ->fromArray($dxNextDate->toArray(), NULL, 'F5');

            $this->getTotal($sheet2, $bsNextDate, $dxNextDate);

            $spreadsheet->createSheet();

            $spreadsheet->setActiveSheetIndex(2);

            $sheet3 = $spreadsheet->getActiveSheet();

            $this->sheetHead($sheet3, $request->doc_date, $bscolumns, $dxcolumns, 'DISBURSEMENT WITH MATCH CHECK NO AND AMOUNT - BANK CHECK NO REFLECTED IN PREVIOUS MONTH', 'BS reflected in prev Month');

            $this->setWidth($sheet3);

            $bsPrevDate = collect([]);
            $dxPrevDate = collect([]);

            $dxPrevMonth = DxTransaction::select('doc_no', 'check_no', 'doc_date', 'check_date', 'amount', 'status', 'payee', 'is_pdc')
                ->where('code', $request->code)
                ->whereMonth('doc_date', '<', $dateT[0])
                ->whereYear('doc_date', '<=', $dateT[1])
                ->where('label_match', 'match check')
                ->where('doc_type', 'CV')
                ->where('buid', $request->user()->bunitid);

            $dxPrevMonthAll = $dxPrevMonth->get();
            $dxPrevMonthCheckNo = $dxPrevMonth->pluck('check_no');

            foreach ($bsdataAll as $key => $bs) {

                $dxKey = $dxPrevMonthCheckNo->search($bs->bank_check_no);

                $bsBankDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($bs->bank_date));

                if ($dxKey || $bsKey === 0) {
                    if ($dxPrevMonthAll[$dxKey]->check_no == $bs->bank_check_no && $dxPrevMonthAll[$dxKey]->amount == $bs->bank_amount) {

                        $dxDocDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($dxPrevMonthAll[$dxKey]->doc_date));
                        $dxCheckDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($dxPrevMonthAll[$dxKey]->check_date));

                        $bsPrevDate->push([
                            $bs->description,
                            $bs->bank_check_no,
                            $bsBankDate,
                            $bs->bank_amount
                        ]);
                        $dxPrevDate->push([
                            $dxPrevMonthAll[$dxKey]->doc_no,
                            $dxPrevMonthAll[$dxKey]->check_no,
                            $dxDocDate,
                            $dxCheckDate,
                            $dxPrevMonthAll[$dxKey]->amount,
                            $dxPrevMonthAll[$dxKey]->status,
                            $dxPrevMonthAll[$dxKey]->payee,
                            $dxPrevMonthAll[$dxKey]->is_pdc,
                        ]);
                        unset($bsdataAll[$key]);
                    }
                    $percent = number_format(($pcount/$pmax)*100);

                    echo json_encode(['progress'=>$percent, 'url'=>'', 'd'=>$request->doc_date]);

                    $pcount++;
                }

            }

            $dxPrevDate = $dxPrevDate->map(function ($item, $key) {

                $pdc = ($item[7] > 0)?'PDC':'';
                $oc = ($pdc!='PDC')?'OC':'';

                return [
                    $item[0],
                    $item[1],
                    $item[2],
                    $item[3],
                    $item[4],
                    $pdc.$oc,
                    $item[6],
                ];

            });

            $sheet3->fromArray($bsPrevDate->toArray(), NULL, 'A5')
                ->fromArray($dxPrevDate->toArray(), NULL, 'F5');

            $this->getTotal($sheet3, $bsPrevDate, $dxPrevDate);

            $spreadsheet->createSheet();

            $spreadsheet->setActiveSheetIndex(3);

            $sheet4 = $spreadsheet->getActiveSheet();

            $this->sheetHead($sheet4, $request->doc_date, $bscolumns, $dxcolumns, 'DISBURSEMENT WITH MATCH CHECK NO ONLY', 'Match Check Num. Only');

            $this->setWidth($sheet4);

            $bscheckOnly = collect([]);
            $dxcheckOnly = collect([]);

            $checknoMerge = $bsdataAll->pluck('bank_check_no')->merge($dxdataAll->pluck('check_no'));

            $uniqueCheckNo = $checknoMerge->unique();

            $newbs = collect([]);
            $newdx = collect([]);

            foreach ($uniqueCheckNo as $key => $checkno) {

                $bs = $bsdataAll->where('bank_check_no', $checkno);
                $dx = $dxdataAll->where('check_no', $checkno);

                $countbs = $bs->count();
                $countdx = $dx->count();

                if ($countbs == 1 && $countdx == 1) {

                    $bsFirst = $bs->first();
                    $dxFirst = $dx->first();

                    $bsBankDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($bsFirst['bank_date']));

                    $dxDocDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($dxFirst['doc_date']));
                    $dxCheckDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($dxFirst['check_date']));

                    $bscheckOnly->push([
                        $bsFirst['description'],
                        $bsFirst['bank_check_no'],
                        $bsBankDate,
                        $bsFirst['bank_amount'],
                    ]);
                    $dxcheckOnly->push([
                        $dxFirst['doc_no'],
                        $dxFirst['check_no'],
                        $dxDocDate,
                        $dxCheckDate,
                        $dxFirst['amount'],
                        $dxFirst['status'],
                        $dxFirst['payee'],
                    ]);
                } else {
                    $newbs->push($bs->toArray());
                    $newdx->push($dx->toArray());
                }

                $percent = number_format(($pcount/$pmax)*100);

                echo json_encode(['progress'=>$percent, 'url'=>'', 'd'=>$request->doc_date]);

                $pcount++;

            }

            $collapsebs = $newbs->collapse();
            $collapsedx = $newdx->collapse();

            foreach ($bsdataAll as $key => $bs) {
                if ($processedBSCheckno->contains($bs->bank_check_no)) {
                    $bsDuplicates->push($bs->bank_check_no);
                }
                $processedBSCheckno->push($bs->bank_check_no);
            }

            foreach ($dxdataAll as $key => $dx) {
                if ($processedDXCheckno->contains($dx->check_no)) {
                    $dxDuplicates->push($dx->check_no);
                }
                $processedDXCheckno->push($dx->check_no);
            }

            $sheet4->fromArray($bscheckOnly->toArray(), NULL, 'A5')
                ->fromArray($dxcheckOnly->toArray(), NULL, 'F5');

            $this->getTotal($sheet4, $bscheckOnly, $dxcheckOnly);

            $spreadsheet->createSheet();

            $spreadsheet->setActiveSheetIndex(4);

            $sheet5 = $spreadsheet->getActiveSheet();

            $this->sheetHead($sheet5, $request->doc_date, $bscolumns, $dxcolumns, 'DISBURSEMENT WITH MATCH CHECK BUT DUPLICATE ENTRY', 'Match Check But Duplicate Entry');
            $this->setWidth($sheet5);

            $dxDupArr = collect([]);
            $bsDupArr = collect([]);

            $alldups = $dxDuplicates->merge($bsDuplicates);

            $allDupsClean = $alldups->unique();

            foreach ($allDupsClean as $key => $dxdup) {
                $dxDup = DxTransaction::select('doc_no', 'check_no', 'doc_date', 'check_date', 'amount', 'status', 'payee')
                    ->where('check_no', $dxdup);
//
                $bsDup = Bankstatement::select('description', 'bank_check_no', 'bank_date', 'bank_amount')
                    ->where('bank_check_no', $dxdup)
                    ->where('type', 'AP')
                    ->where('bank_account_no', $request->code)
                    ->where('bu_unit', $request->user()->bunitid);
//
                if ($dxDup->count() >= $bsDup->count()) {
//
                    foreach ($dxDup->get() as $dxkey => $dx2) {

                        $dxDocDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($dx2->doc_date));
                        $dxCheckDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($dx2->check_date));

                        $dxDupArr->push([
                            $dx2->doc_no,
                            $dx2->check_no,
                            $dxDocDate,
                            $dxCheckDate,
                            $dx2->amount,
                            $dx2->status,
                            $dx2->payee,
                        ]);
//
                        $bsd = $bsDup->get();

                        $bsBankDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($bsd[$dxkey]['bank_date']));
//
                        if (array_key_exists($dxkey, $bsd)) {
                            $bsDupArr->push([
                                $bsd[$dxkey]['description'],
                                $bsd[$dxkey]['bank_check_no'],
                                $bsBankDate,
                                $bsd[$dxkey]['bank_amount'],
                            ]);
                        } else {
                            $bsDupArr->push([
                                '',
                                '',
                                '',
                                '',
                            ]);
                        }
                    }
                } else {
                    foreach ($bsDup->get() as $bskey => $bs2) {

                        $bsBankDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($bs2->bank_date));

                        $bsDupArr->push([
                            $bs2->description,
                            $bs2->bank_check_no,
                            $bsBankDate,
                            $bs2->bank_amount,
                        ]);

                        $dxd = $dxDup->get()->toArray();
                        $dxdGET = $dxDup->get();

                        if (array_key_exists($bskey, $dxd)) {

                            $dxDocDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($dxdGET[$bskey]['doc_date']));
                            $dxCheckDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($dxdGET[$bskey]['check_date']));

                            $dxDupArr->push([
                                $dxdGET[$bskey]['doc_no'],
                                $dxdGET[$bskey]['check_no'],
                                $dxDocDate,
                                $dxCheckDate,
                                $dxdGET[$bskey]['amount'],
                                $dxdGET[$bskey]['status'],
                                $dxdGET[$bskey]['payee'],
                            ]);
                        } else {
                            $dxDupArr->push([
                                '',
                                '',
                                '',
                                '',
                            ]);
                        }
                    }
                }

                $percent = number_format(($pcount/$pmax)*100);

                echo json_encode(['progress'=>$percent, 'url'=>'', 'd'=>$request->doc_date]);

                $pcount++;
//
            }

            $sheet5->fromArray($bsDupArr->toArray(), NULL, 'A5')
                ->fromArray($dxDupArr->toArray(), NULL, 'F5');

            $this->getTotal($sheet5, $bsDupArr, $dxDupArr);

            $spreadsheet->createSheet();

            $spreadsheet->setActiveSheetIndex(5);

            $sheet6 = $spreadsheet->getActiveSheet();

            $this->sheetHead($sheet6, $request->doc_date, $bscolumns, $dxcolumns, 'DISBURSEMENT UNMATCH WITH CHECK NO', 'Unmatch With Check Num.');

            $this->setWidth($sheet6);

            $dxNoMatchwCheck = DxSubsidiaryLedger::select('tr.doc_no', 'tr.check_no', 'tr.doc_date', 'tr.check_date', 'tr.amount', 'tr.status', 'tr.is_pdc', 'tr.payee')
                ->joinT()
                ->where('dx_subsidiaryledgers.ledger_code', $request->code)
                ->where('dx_subsidiaryledgers.doc_type', 'CV')
                ->whereMonth('dx_subsidiaryledgers.doc_date', $dateT[0])
                ->whereYear('dx_subsidiaryledgers.doc_date', $dateT[1])
                ->where('tr.label_match', '!=','match check')
                ->where('tr.check_no', '!=', '')
                ->where('tr.buid', $request->user()->bunitid)
                ->get();

            $dxNoMatchwCheck = $dxNoMatchwCheck->map(function ($item, $key) {

                $docDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($item->doc_date));
                $checkDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($item->check_date));

                $pdc = ($item->is_pdc > 0)?'PDC':'';
                $oc = ($pdc!='PDC')?'OC':'';

                return [
                    'doc_no' => $item->doc_no,
                    'check_no'  => $item->check_no,
                    'doc_date'  => $docDate,
                    'check_date' => $checkDate,
                    'amount' => $item->amount,
                    'status' => $pdc.$oc,
                    'payee' => $item->payee
                ];

            });

//            dd($dxNoMatchwCheck->first());

            $bsNoMatchwCheck = Bankstatement::select('description', 'bank_check_no', 'bank_date', 'bank_amount')
                ->where('bank_account_no', $request->code)
                ->where('type', 'AP')
                ->whereMonth('bank_date', $dateT[0])
                ->whereYear('bank_date', $dateT[1])
                ->where('label_match', '!=', 'match check')
                ->where('bank_check_no', '!=', '')
                ->where('bu_unit', $request->user()->bunitid)
                ->get();

            $bsNoMatchwCheck = $bsNoMatchwCheck->map(function ($item, $key) {

                $bankDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($item->bank_date));

                return [
                    'doc_no' => $item->description,
                    'check_no'  => $item->bank_check_no,
                    'doc_date'  => $bankDate,
                    'check_date' => $item->bank_amount,
                ];

            });

            $sheet6->fromArray($bsNoMatchwCheck->toArray(), NULL, 'A5')
                ->fromArray($dxNoMatchwCheck->toArray(), NULL, 'F5');

            $this->getTotal($sheet6, $bsNoMatchwCheck, $dxNoMatchwCheck);

            $spreadsheet->createSheet();

            $spreadsheet->setActiveSheetIndex(6);

            $sheet7 = $spreadsheet->getActiveSheet();

            $this->sheetHead($sheet7, $request->doc_date, $bscolumns, $dxcolumns, 'DISBURSEMENT UNMATCH WITHOUT CHECK NO', 'Unmatch Without Check Num.');

            $this->setWidth($sheet7);

            $dxNoMatch = DxSubsidiaryLedger::select('tr.doc_no', 'tr.check_no', 'tr.doc_date', 'tr.check_date', 'tr.amount', 'tr.status', 'tr.payee')
                ->joinT()
                ->where('dx_subsidiaryledgers.ledger_code', $request->code)
                ->where('dx_subsidiaryledgers.doc_type', 'CV')
                ->whereMonth('dx_subsidiaryledgers.doc_date', $dateT[0])
                ->whereYear('dx_subsidiaryledgers.doc_date', $dateT[1])
                ->where('tr.label_match', '!=','match check')
                ->where('tr.check_no', '')
                ->where('tr.buid', $request->user()->bunitid)
                ->get();

            $bsNoMatch = Bankstatement::select('description', 'bank_check_no', 'bank_date', 'bank_amount')
                ->where('bank_account_no', $request->code)
                ->where('type', 'AP')
                ->whereMonth('bank_date', $dateT[0])
                ->whereYear('bank_date', $dateT[1])
                ->where('label_match', '!=', 'match check')
                ->where('bank_check_no', '')
                ->where('bu_unit', $request->user()->bunitid)
                ->get();

            $bsNoMatch = $bsNoMatch->map(function ($item, $key) {

                $bankDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($item->bank_date));

                return [
                    $item->description,
                    $item->bank_check_no,
                    $bankDate,
                    $item->bank_amount,
                ];

            });

            $dxNoMatch = $dxNoMatch->map(function ($item, $key) {

                $dxDocDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($item->doc_date));

                $dxCheckDate = \PHPExcel_Shared_Date::PHPToExcel(strtotime($item->check_date));

                $pdc = ($item->is_pdc > 0)?'PDC':'';

                return [
                    $item->doc_no,
                    $item->check_no,
                    $dxDocDate,
                    $dxCheckDate,
                    $item->amount,
                    $pdc.' OC',
                    $item->payee,
                ];

            });

            $sheet7->fromArray($bsNoMatch->toArray(), NULL, 'A5')
                ->fromArray($dxNoMatch->toArray(), NULL, 'F5');

            $this->getTotal($sheet7, $bsNoMatch, $dxNoMatch);

            $spreadsheet->setActiveSheetIndex(0);

            $writer = new Xlsx($spreadsheet);

            $file_info = $this->createFile($request, $dateT);

            if ($file_info == false) {
                return json_encode(['a'=>"Bank Account {$request->code} not added yet!",'b'=>'error','c'=>'error']);
            }

            $writer->save("storage/designex/reports/{$file_info['path']}/DISBURSEMENT-{$file_info['banko']}-{$file_info['accountno']}-{$file_info['accountname']}-{$dateT[0]}-{$dateT[1]}.xlsx");

            echo json_encode(['progress'=>'100', 'url'=>$file_info['url'], 'd'=>$request->doc_date]);

        } catch (\Exception $exception) {
            echo json_encode(['a'=>$exception->getMessage(),'b'=>'error','c'=>'error']);
        }

    }

    public function show(Request $request, $id) {

        $dateT = explode('-', date('m-Y', strtotime($request->doc_date)));

        DxTransaction::whereMonth('doc_date', $dateT[0])
            ->whereYear('doc_date', $dateT[1])
            ->where('code', $id)
            ->update(['label_match' => '']);

        Bankstatement::whereMonth('bank_date', $dateT[0])
            ->whereYear('bank_date', $dateT[1])
            ->where('bank_account_no', $id)
            ->where('type', 'AP')
            ->where('bu_unit', $request->user()->bunitid)
            ->update(['label_match' => '']);
        return json_encode(['a' => 'Done Clearing!', 'b' => 'success', 'c' => 'Success', 'd'=>$request->doc_date]);
    }
}