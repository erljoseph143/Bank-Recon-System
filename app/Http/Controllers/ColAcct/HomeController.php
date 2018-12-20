<?php

namespace App\Http\Controllers\ColAcct;

use App\BankAccount;
use App\Checkingaccounts;
use App\Functions\DateClass;
use App\Functions\ExcelClass;
use App\Functions\SearchClass;
use App\PdcLine;
use App\Usertype;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Shared_Date;

class HomeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');

        $this->error_data = Array();
    }

    public function upload() {

        $login_user = Auth::user();

        $login_user_id = $login_user->user_id;
        $created = $login_user->created_at;

        $title = "Bank Reconciliation System - Colonnade Accounting";

        $userid = 1;

        $login_user_type = Usertype::select('user_type_name')
            ->where('user_type_id', $login_user->privilege)
            ->first();

        $bankaccounts = BankAccount::select('*')
            ->where('company_code', 7)
            ->where('buid', 40)
            ->get();

        return view('colacct.checks.upload', compact('bankaccounts', 'title', 'login_user', 'login_user_type', 'created', 'userid', 'login_user_id'));

    }

    public function uploadProgress(Request $request) {

        set_time_limit(0);
        ob_implicit_flush(true);
        ob_end_flush();

        $login_user = Auth::user();

        $title = "Bank Reconciliation System - Colonnade Accounting";
        $pagetitle = "Dashboard";
        $userid = 1;
        $this->error_data = [];
        $this->count_file = 0;

        if ($request->hasFile('excelfile')) {

            $files = $request->file('excelfile');

            foreach ($files as $file) {

                $fileName = $file->getClientOriginalName();
                $allowed_extensions = array('.xls','.XLS','.xlsx','.XLSX');
                $extension = strrchr($fileName, '.');

                if (!in_array($extension, $allowed_extensions))
                {
                    echo 'not an excel file!';
                }

            }

            $response = [
                'progress'	=> 0,
                'error_info'    => '',
                'status'	=> 'Crunching...'
            ];
            echo json_encode($response);
            //dd($files);

            $this->is_blank_dandw = false;
            $this->is_blank_bal = false;
            $this->is_check_not_number = false;
            $this->is_eff_date_not_valid = false;
            $this->is_post_date_not_valid = false;
            $this->is_fill_dandw = false;

            $max = count($files);

            foreach ($files as $key => $file) {
                $this->filename = $file->getClientOriginalName();

                $this->error_data[] = [
                    'File Name: ',
                    $this->filename,
                    '',
                    '',
                    '',
                    '',
                    ''
                ];

                //dd($file);

                //echo json_encode($response);

                DB::transaction(function ()use($file) {

                    $path = $file->getPathName();

                    Excel::load(
                        $path, function ($reader) {

                            $objWorksheet = $reader->getActiveSheet();
                            $highestRow = $objWorksheet->getHighestRow();
                            $highestColumn = $objWorksheet->getHighestColumn();

                            $date  = strtolower($objWorksheet->getCellByColumnAndRow(0,1));



                            for ( $row = 1; $row <= $highestRow; $row++ ) {

//                                $objWorksheet->setColumnFormat(array(
//                                    'A'
//                                ));

//                                $postdate = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(0, $row)->getValue()));
//
//                                dd($postdate);

                                $postdate_string = $objWorksheet->getCellByColumnAndRow(0,$row)->getValue();
                                $effdate_string = $objWorksheet->getCellByColumnAndRow(1,$row)->getValue();
                                $desc = $objWorksheet->getCellByColumnAndRow(2,$row)->getValue();
                                $checkno = $objWorksheet->getCellByColumnAndRow(3,$row)->getValue();
                                $withdrawals = $objWorksheet->getCellByColumnAndRow(4,$row)->getValue();
                                $deposit = $objWorksheet->getCellByColumnAndRow(5,$row)->getValue();
                                $balance = $objWorksheet->getCellByColumnAndRow(6,$row)->getValue();

                                if (strtotime($postdate_string)) {

                                    $effdate_str = trim($effdate_string);

                                    if (is_numeric($effdate_str)) {
                                        $UNIX_DATE = ($effdate_str - 25569) * 86400;
                                        $effdate = gmdate("Y-m-d", $UNIX_DATE);
                                    } else {
                                        $effdate = DateClass::formatDate($effdate_str);
                                    }

                                    //dd($effdate_string);

                                    $postdate = DateClass::formatDate($postdate_string);

                                    $postdate_str = trim($postdate_string);
                                    $checkno_str = trim($checkno);

                                    if ( is_null($deposit) AND is_null($withdrawals) ) {

                                        $this->error_data[] = [
                                            $postdate_str,
                                            $checkno_str,
                                            $withdrawals,
                                            $deposit,
                                            $balance,
                                            'Blank cell deposit and withdrawals',
                                            $row
                                        ];

                                        $this->is_blank_dandw = true;

                                    }
                                    if ( !is_null($deposit) AND !is_null($withdrawals) ) {

                                        $this->error_data[] = [
                                            $postdate_str,
                                            $checkno_str,
                                            $withdrawals,
                                            $deposit,
                                            $balance,
                                            'Deposit and withdrawals Occupied',
                                            $row
                                        ];
                                        $this->is_fill_dandw = true;

                                    }
                                    if ( is_null($balance) ) {

                                        $this->error_data[] = [

                                            $postdate_str,
                                            $checkno_str,
                                            $withdrawals,
                                            $deposit,
                                            $balance,
                                            'Blank cell balance',
                                            $row

                                        ];

                                        $this->is_blank_bal = true;

                                    }
                                    if (!is_numeric($checkno_str) && $checkno_str != 0) {

                                        $this->error_data[] = [

                                            $postdate_str,
                                            $checkno_str,
                                            $withdrawals,
                                            $deposit,
                                            $balance,
                                            'Check # is not numeric',
                                            $row

                                        ];

                                        //echo $postdate_string;

                                        $this->is_check_not_number = true;

                                    }
                                    if (!is_numeric($withdrawals) && !is_null($withdrawals)) {

                                        $this->error_data[] = [

                                            $postdate_str,
                                            $checkno_str,
                                            $withdrawals,
                                            $deposit,
                                            $balance,
                                            'Withdrawal is not numeric',
                                            $row

                                        ];

                                        $this->is_check_not_number = true;

                                    }
                                    if (!is_numeric($deposit) && !is_null($deposit)) {

                                        $this->error_data[] = [

                                            $postdate_str,
                                            $checkno_str,
                                            $withdrawals,
                                            $deposit,
                                            $balance,
                                            'Deposit is not numeric',
                                            $row

                                        ];

                                        $this->is_check_not_number = true;

                                    }
                                    if (!is_numeric($balance) && !is_null($balance)) {

                                        $this->error_data[] = [

                                            $postdate_str,
                                            $checkno_str,
                                            $withdrawals,
                                            $deposit,
                                            $balance,
                                            'Balance is not numeric',
                                            $row

                                        ];

                                        $this->is_check_not_number = true;

                                    }
                                    if (!strtotime($effdate)) {

                                        $this->error_data[] = [

                                            $postdate_str,
                                            $checkno_str,
                                            $withdrawals,
                                            $deposit,
                                            $balance,
                                            'Invalid effective date',
                                            $row

                                        ];

                                        $this->is_eff_date_not_valid = true;

                                    }
                                    if (!strtotime($postdate)) {

                                        $this->error_data[] = [

                                            $postdate_str,
                                            $checkno_str,
                                            $withdrawals,
                                            $deposit,
                                            $balance,
                                            'Invalid date posted',
                                            $row

                                        ];

                                        $this->is_post_date_not_valid = true;

                                    }
                                    else {

                                        $this->data[$this->count_file][] = [
                                            $postdate,
                                            $effdate,
                                            $desc,
                                            $checkno_str,
                                            $withdrawals,
                                            $deposit,
                                            $balance,
                                            $this->filename
                                            //$file->getClientOriginalName()
                                        ];

                                        $this->data_combine[] = [

                                            $postdate,
                                            $effdate,
                                            $desc,
                                            $checkno_str,
                                            $withdrawals,
                                            $deposit,
                                            $balance,
                                            $this->filename

                                        ];

                                    }

                                }

                            }

                            //switch (strtotime($date))
                    });

                });

                $percent = (100/$max)*$this->count_file;

                $response = [
                    'progress'	=> number_format($percent),
                    'error_info'    => '',
                    'status'	=> 'Crunching...'
                ];
                echo json_encode($response);



            }

            $response = [
                'progress'	=> 100,
                'error_info'    => '',
                'status'	=> 'Crunching...'
            ];
            echo json_encode($response);

            //session(['errors' => $this->error_data]);

            //$request->session()->put('errors', $this->error_data);


            if ($this->is_blank_bal == true || $this->is_blank_dandw == true || $this->is_check_not_number == true || $this->is_eff_date_not_valid == true || $this->is_post_date_not_valid == true || $this->is_fill_dandw == true) {
                usleep(80000);
                $error_arr = [
                    'progress'	=> 0,
                    'status'    => 'error',
                    'error_info'	=> $this->error_data,
                    //'text'			=> 'excel error',
                    //'url'			=> url('welcome/error_message')
                ];
                //dd($this->error_data);
                session(['upload_errors' => $this->error_data]);
                //$request->session()->flash('upload_errors', $this->error_data);

                echo json_encode($error_arr);

                //dd(session()->get('upload_errors',''));
                exit();
            }



        }

        $response = [
            'progress'	=> 0,
            'status'	=> 'Saving...'
        ];
        echo json_encode($response);

        $bankaccid = $request->bankno;

        $accountid = BankAccount::select('bankno.bankno', 'bankaccount.buid', 'bankaccount.company_code')
            ->join('bankno', 'bankaccount.bankno', 'bankno.id')
            ->where('bankaccount.id', $bankaccid)
            ->first();

        $company = $accountid->company_code;
        $bu = $accountid->buid;
        $bankcode = $accountid->bankno;

        $max = count($this->data_combine);

        /**
         * Save data
         */
        $save_data = ExcelClass::saveCheckingAccounts($this->data_combine, $login_user, $bankcode, $bankaccid, $this->filename, $max);

//        $percent = $save_data['percent'];
//
//        $counter = $save_data['count'];

        $datamax = count($this->data);
        $counter = 0;

        for ( $i=0; $i<$datamax; $i++ ) {

            $percent = number_format( (100/$datamax)*$counter );

            $response = array(
                'progress'	=> $percent,
                'status'	=> 'Matching...'
            );
            echo json_encode($response);

            $postdate = $this->data[$i][0][0];

            $month_postdate = DateClass::formatDate($postdate, 'm');
            $year_postdate = DateClass::formatDate($postdate, 'Y');

            $book_dis = PdcLine::select(DB::raw('CAST(check_no AS UNSIGNED) as check_no'), 'check_date')
                ->where('baccount_no', $bankcode)
                ->where('check_no', '!=', '')
                ->where(DB::raw('MONTH(cv_date)'), $month_postdate)
                ->where(DB::raw('YEAR(cv_date)'), $year_postdate)
                ->where('company', $company)
                ->where('bu_unit', $bu)
                ->orderBy('check_no', 'ASC')
                ->get()
                ->toArray();

            $check_data = $this->data[$i];

            if ($book_dis != null) {

                $response = array(
                    'progress'	=> $percent,
                    'status'	=> 'Matching...'
                );
                echo json_encode($response);

                $searchClass = new SearchClass();

                $match = $searchClass->match_check_no( $check_data, $book_dis, $datamax, $percent, $counter );

                $response = array(
                    'progress'	=> $percent,
                    'status'	=> 'Searching...'
                );
                echo json_encode($response);

                $matchpdc = $searchClass->search_with_date($match);

                $response = array(
                    'progress'	=> $percent,
                    'status'	=> 'Updating OC...'
                );
                echo json_encode($response);

                $searchClass->update_OC($check_data, $matchpdc);

                $response = array(
                    'progress'	=> $percent,
                    'status'	=> 'Updating Matches...'
                );
                echo json_encode($response);

                $searchClass->update_matches($match, $month_postdate, $year_postdate, $bankcode, $company, $bu);

                    //$this->disbursement_model->search_with_date($match);

            }

            $counter++;

        }

        $response = array(
            'progress'	=> 100,
            'status'	=> 'Done'
        );

        echo json_encode($response);

//        $bankaccounts = BankAccount::select('bank', 'accountno', 'accountname')
//            ->where('id', $bankaccid)
//            ->get();
//
//        $arr = [
//            'a' => $bankcode,
//            'b' => $bankaccounts->bank,
//            'c' => $bankaccounts->accountno,
//            'd' => $bankaccounts->accountname,
//        ];

        echo json_encode(array(
            //'url'	=> base_url('checking_accounts/date_categories?data='.urlencode(json_encode($arr, JSON_FORCE_OBJECT)).''),
            'url'	=> url("colacct/checking_accounts/$bankcode"),
            'progress'	=> 100,
        ));

    }

    public function uploadError(Request $request) {



        //$upload_errors = session()->get('errors');

        //$upload_errors = session()->get('upload_errors','');
        $upload_errors = $request->data;

        return view('colacct.upload.error', compact('upload_errors'));

    }

}
