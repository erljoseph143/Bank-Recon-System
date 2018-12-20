<?php

namespace App\Http\Controllers\Admin;

use App\BankAccount;
use App\Businessunit;
use App\Dtrbankstatement;
use App\Company;
use App\Deposit;
use App\PdcLine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Shared_Date;

class DtrUpload extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function uploadUI() {

        $title = 'Uploading';

        $accounts = BankAccount::select('id', 'bank', 'accountno', 'accountname', 'buid', 'company_code')->get();
        $companies = Company::select('company_code', 'company')->get();
        $bus = Businessunit::select('unitid', 'bname', 'company_code')->get();

        return view('test.upload', compact('title', 'accounts', 'companies', 'bus'));

    }

    public function uploadProcess(Request $request) {

        //echo $request->file('excelfile');

//        $account = BankAccount::select('id', 'company_code', 'buid')
//            ->where('accountno', $request->account)
//            ->first();

        set_time_limit(0);
        ob_implicit_flush(true);
        ob_end_flush();

        $login_user = Auth::user();

        $this->loguserid = $login_user->user_id;

        $this->accountid = $request->acc;
        $this->company = $request->company;
        $this->bu = $request->bu;

        if ($request->hasFile('excelfile')) {



            $file = $request->file('excelfile');

            foreach ($file as $files) {

                $fileName = $files->getClientOriginalName();
                $allowed_extensions = array('.xls','.XLS','.xlsx','.XLSX');
                $extension = strrchr($fileName, '.');

                if (!in_array($extension, $allowed_extensions))
                {
                    echo 'not an excel file!';
                }

            }

            $this->countfile = 1;

            foreach ($file as $files) {



                DB::transaction(function ()use($files) {
                    $path = $files->getPathName();

                    Excel::load(/**
                     * @param $reader
                     */
                        $path, function ($reader) {
                        $objWorksheet = $reader->getActiveSheet();
                        $highestRow = $objWorksheet->getHighestRow();
                        $highestColumn = $objWorksheet->getHighestColumn();

                        $date  = strtolower($objWorksheet->getCellByColumnAndRow(0,1));
                        $desc  = strtolower($objWorksheet->getCellByColumnAndRow(1,1));
                        $ref  = strtolower($objWorksheet->getCellByColumnAndRow(2,1));
                        $details  = strtolower($objWorksheet->getCellByColumnAndRow(3,1));
                        $debit  = strtolower($objWorksheet->getCellByColumnAndRow(4,1));
                        $credit  = strtolower($objWorksheet->getCellByColumnAndRow(5,1));
                        $balance  = strtolower($objWorksheet->getCellByColumnAndRow(6,1));

                        if(
                            $date != 'date'
                            or
                            $desc != 'description'
                            or
                            $ref != 'ref'
                            or
                            $details != 'details'
                            or
                            $debit != 'debit amount'
                            or
                            $credit  != 'credit amount'
                            or
                            $balance !='balance'
                        ) {
                            echo 'data not organized';
                        }

                        $index = 2;
                        $counter = 0;

                        while($index<=$highestRow)
                        {
                            echo response()->json([
                                'progress'      => number_format((100/$highestRow)*$counter, 2),
                                'filecount'     => $this->countfile,
                                'details'       => $details
                            ]);
                            $date       = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(0, $index)->getValue()));
                            $desc       = $objWorksheet->getCellByColumnAndRow(1,$index)->getValue();
                            $ref        = $objWorksheet->getCellByColumnAndRow(2,$index)->getValue();
                            $details    = $objWorksheet->getCellByColumnAndRow(3,$index)->getValue();
                            //$details    = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(2, $index)->getValue()));
                            $debit      = $objWorksheet->getCellByColumnAndRow(4,$index)->getValue();
                            $credit     = $objWorksheet->getCellByColumnAndRow(5,$index)->getValue();
                            $balance    = $objWorksheet->getCellByColumnAndRow(6,$index)->getValue();

                            if ($credit != null) {
                                $cd_amount  = $credit;
                                $stats = 0;
                                //echo 'credit not null';
                            }

                            if ($debit != null) {
                                $cd_amount = $debit;
                                $stats = 1;
                                //echo 'debit not null';
                            }

                            $count = Dtrbankstatement::where('description', $desc)
                                ->where('ref', $ref)
                                ->where('details', $details)
                                ->where('debit_amount', $debit)
                                ->where('credit_amount', $credit)
                                ->where('balance', $balance)
                                ->count('id');

                            if ($count <= 0) {

                                if (!is_null($balance)) {

                                    Dtrbankstatement::updateOrCreate([
                                        'check_date'    => $date,
                                        'description'   => $desc,
                                        'ref'           => $ref,
                                        'details'       => $details,
                                        'debit_amount'  => $debit,
                                        'credit_amount' => $credit,
                                        'cd_amount'     => $cd_amount,
                                        'stats'         => $stats,
                                        'balance'       => $balance,
                                        'accountid'     => $this->accountid,
                                        'company'       => $this->company,
                                        'bu'            => $this->bu,
                                        'created_by'    => $this->loguserid,
                                    ]);

                                }

                            }

                            $counter++;

                            /** @var TYPE_NAME $index */
                            $index++;

                            usleep(80000);

                        }


                    });
                });

                $this->countfile++;

            }

            echo response()->json(['progress'   => '100','status'   => 'done']);



        } else {
            echo response()->json(['no files']);
        }

    }

    public function listBanks() {

        $accounts = BankAccount::select('bank', 'accountno', 'accountname')->get();

        //dd($accounts);

        return $accounts;

    }

    public function viewDailyReportTable(Request $request) {

        $date = $request->date;
        $account_id = $request->account;

        $crack_date = explode("-", $date);



        $newdate = $crack_date[0] . '-' . $crack_date[2] . '-' . $crack_date[1];

        $account = BankAccount::select('bank', 'accountno', 'accountname')
            ->where('id', $account_id)
            ->first();

        $stringdate = Carbon::parse($newdate)->format('Y F d');

        $title = $account->bank . ' - ' . $account->accountno . ' - ' . $account->accountname . ' <span class="data-basis badge bg-1" data-account="'.$account_id.'" data-date="'.$date.'">'.$stringdate.'</span>';

        $view = view("test.buttons", compact('deposits', 'checks', 'matches_dp'))->render();

        return response()->json(['html' => $view, 'title' => $title]);
    }

    public function viewSamedateamount(Request $request, $data) {

        $data = json_decode($data, true);

        $crack_date = explode("-", $data['date']);

        list($year, $day, $month) = $crack_date;

        $deposits = Deposit::select('id', 'posting_date', 'doc_no', 'ext_doc_no', 'amount')
            ->where(DB::raw('MONTH(posting_date)'), $month)
            ->where(DB::raw('YEAR(posting_date)'), $year)
            ->where(DB::raw('DAY(posting_date)'), $day)
            ->where('company', '2')
            ->where('bu_unit', '4')
            ->orderBy('amount')
            ->get();

        $checks = Dtrbankstatement::select('check_date', 'cd_amount')
            ->where(DB::raw('MONTH(check_date)'), $month)
            ->where(DB::raw('YEAR(check_date)'), $year)
            ->where(DB::raw('DAY(check_date)'), $day)
            ->where('company', '2')
            ->where('bu', '4')
            ->orderBy('cd_amount')
            ->get();

        $countchecks = count($checks)-1;
//
        $matches_dp = [];
//
        foreach ($deposits as $deposit) {
            $search_result = exponentialSearch($checks, $countchecks, $deposit->amount);
            if ($search_result != -1) {
                $matches_dp[] = $search_result;
                //$amount_dp_matches[] = $search_result[1];
            }
        }

        if (empty($matches_dp)) {
            return response()->json(['html'=>'no match data']);
        }

        return $matches_dp;

    }

    public function viewPlus5days() {

    }

    public function viewMinus5days() {

    }

}
