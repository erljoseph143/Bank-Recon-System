<?php

namespace App\Http\Controllers\Designex;

use App\Charts\TransactionChart;
use App\DxAccount;
use App\DxLedger;
use App\DxSubsidiaryLedger;
use App\DxTransaction;
use App\DxTransactionType;
use App\Functions\FiletypeClass;
use App\Functions\UploadAccountClass;
use App\Functions\UploadLedgerClass;
use App\Functions\UploadProofListClass;
use App\Functions\UploadSLClass;
use App\User;
use function Couchbase\defaultDecoder;
use function foo\func;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {

        $login_user = Auth::user();

        $transtypesnow = DxTransactionType::whereDate('updated_at', DB::raw('CURDATE()'))->count();
        $prooflistsnow = DxTransaction::whereDate('updated_at', DB::raw('CURDATE()'))->count();
        $slsnow = DxSubsidiaryLedger::whereDate('updated_at', DB::raw('CURDATE()'))->count();
        $ledgersnow = DxLedger::whereDate('updated_at', DB::raw('CURDATE()'))->count();
        $accountsnow = DxAccount::whereDate('updated_at', DB::raw('CURDATE()'))->count();

        $prooflistcount = DxTransaction::count();
        $slcount = DxSubsidiaryLedger::count();

        $banks = DxSubsidiaryLedger::joinT()
            ->getCodes([$request->user()->bunitid])
            ->count();

//        $pryears = DesignexTransaction::select(DB::raw('YEAR(doc_date) as year'))
//            ->groupBy(DB::raw('year'))
//            ->orderBy(DB::raw('year'), 'DESC')
//            ->get();
//
//        if ($pryears->count() > 0) {
//            $prbanks = DesignexTransaction::whereNotNull('check_bank')
//                ->whereYear('doc_date', $pryears[0]->year)
//                ->groupBy('check_bank')
//                ->get(['check_bank']);
////
//            $prlabels = DesignexTransaction::select(DB::raw('DATE_FORMAT(doc_date, "%M %Y") as month'))
//                ->whereYear('doc_date', $pryears[0]->year)
//                ->where('check_bank', $prbanks[0]->check_bank)
//                ->groupBy(DB::raw('month'))
//                ->orderBy(DB::raw('MONTH(doc_date)'))
//                ->pluck('month');
////
//            $prdata = DesignexTransaction::selectRaw('SUM(amount) as amount')
//                ->whereYear('doc_date', $pryears[0]->year)
//                ->where('check_bank', $prbanks[0]->check_bank)
//                ->groupBy(DB::raw('MONTH(doc_date)'))
//                ->orderBy(DB::raw('MONTH(doc_date)'))
//                ->pluck('amount');
//        } else {
//            $prbanks = [];
//            $prlabels = "";
//            $prdata = "";
//        }

//        $ucpb_count = DesignexTransaction::selectRaw('count(id) as yearly_count')
//            ->groupBy(DB::raw('YEAR(doc_date)'))
//            ->pluck('yearly_count');
//
//        $ucpb_check_year = DesignexTransaction::selectRaw('DATE_FORMAT(doc_date, "%Y") as check_dates')
//            ->whereNotNull('doc_date')
//            ->groupBy('check_dates')
//            ->orderBy('check_dates')
//            ->pluck('check_dates');

        $title = "BRS - Designex Accounting Dashboard";
        $ptitle = "dashboard";
        return view('designex.dashboard', compact('title', 'login_user', 'ptitle', 'transtypesnow', 'prooflistsnow', 'slsnow', 'ledgersnow', 'accountsnow', 'transtypesall', 'prooflistsall', 'slsall', 'ledgersall', 'accountsall', 'ucpb_count', 'ucpb_check_year', 'prbanks', 'pryears', 'prlabels', 'prdata', 'prooflistcount', 'slcount', 'banks'));
    }

    public function postReq(Request $request) {
        set_time_limit(0);
        $validator = Validator::make($request->all(), [
            'files' => 'required',
            'files.*' => 'mimes:bin'
        ]);
        if ($validator->fails()) {
            return json_encode(['a' => $validator->errors()->first(), 'b' => 'error']);
        }

        if ($request->hasFile('files')) {

            $files = $request->file('files');
            $uploadcv = new UploadProofListClass();
            $uploadsl = new UploadSLClass();
            $uploadacc = new UploadAccountClass();
            $uploadledger = new UploadLedgerClass();
            $types = DxTransactionType::get(['code', 'name']);
            try {
                DB::beginTransaction();
                foreach ($files as $file) {
                    $file_name = $file->getClientOriginalName();
                    $content = File::get($file);
                    switch (FiletypeClass::identify($content)) {
                        case "proof list":
                            if (TRUE !== $result = $uploadcv->upload($content, $file_name, $types)) {
                                return $result;
                            }
                            break;
                        case "subsidiary ledger":

                            if (TRUE !== $result = $uploadsl->upload($content, $file_name, $types)) {
                                return $result;
                            }
                            break;
                        case "accounts":
                            if (TRUE !== $result = $uploadacc->upload($content, $file_name)) {
                                return $result;
                            }
                            break;
                        case "ledgers":
                            if (TRUE !== $result = $uploadledger->upload($content, $file_name, $types)) {
                                return $result;
                            }
                            break;
                        default:
                            return json_encode(['a' => 'Unable to detect what file type is \'' . $file_name . '\'!', 'b' => 'error']);
                            break;
                    }
                }
                DB::commit();
            } catch (QueryException $exception) {
                DB::rollBack();
                return json_encode(['a' => $exception->errorInfo[2], 'b' => 'error']);

            }
        }
    }
}