<?php

namespace App\Http\Controllers\ColAcct;

use App\BankAccount;
use App\Businessunit;
use App\Checkingaccounts;
use App\Company;
use App\Functions\DateClass;
use App\Functions\ExcelClass;
use App\Usertype;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    //
    public function listReports() {

        $title = 'Accounting Colonnade | View Disbursements Reports';
        $login_user = Auth::user();
        $login_user_type = Usertype::select('user_type_name')
            ->where('user_type_id', $login_user->privilege)
            ->first();

        $accounts = BankAccount::select('bankaccount.id', 'bankaccount.bank', 'bankaccount.accountno', 'bankaccount.accountname', 'bankno.bankno')
            ->join('bankno', 'bankaccount.bankno', 'bankno.id')
            ->whereIn('bankaccount.buid', [$login_user->bunitid])
            ->get();

        return view('colacct.reports.index', compact('title', 'login_user', 'login_user_type', 'accounts'));

    }

    public function reportCategories( $id, $bankno, $bank, $accountno, $accountname ) {

        $login_user = Auth::user();

        $login_user_type = Usertype::select('user_type_name')
            ->where('user_type_id', $login_user->privilege)
            ->first();

        $months = Checkingaccounts::select(DB::raw('DATE_FORMAT(checking_account.date_posted,"%M %Y") as datein'), 'checking_account.nav_setup_no', 'checking_account.date_uploaded', DB::raw('CONCAT(users.firstname, " ", users.lastname) as name'))
            ->where('company', $login_user->company_id)
            ->where('bankaccount_id', $id)
            ->whereIn('bu', [$login_user->bunitid])
            ->join('users', 'users.user_id', 'checking_account.uploaded_by')
            ->groupBy(DB::raw('DATE_FORMAT(date_posted, "%M %Y")'), 'checking_account.nav_setup_no')
            ->get();

        $title = "Accounting Colonnade | Date Reports";

        return view('colacct.reports.cat', compact('months','title', 'login_user', 'login_user_type', 'bankno', 'bank', 'accountno', 'accountname'));

    }

    public function generateExcel( $nav_setup_no, $datein, $bank, $accountno, $accountname ) {

        set_time_limit(0);
        ob_implicit_flush(true);
        ob_end_flush();

        $login_user = Auth::user();

        $company = Company::select('company')
            ->where('company_code', $login_user->company_id)
            ->first();

        $bu = Businessunit::select('bname')
            ->where('unitid', $login_user->bunitid)
            ->first();

        $response = array(  'message' => 'Writing match check number and amount...');

        $paymentsArray = [];

        // Define the Excel spreadsheet headers
        $paymentsArray[] = ['id', 'customer','email','total','created_at'];

        Excel::create('Sample', function ($excel) use ($paymentsArray,$company,$bu,$bank,$accountno, $datein, $nav_setup_no, $login_user) {

            $excel->setTitle('Our new awesome title');

            $excel->setCreator('BRS Programmers')
                ->setCompany('Alturas Group of Companies');

            $excel->setDescription('A demonstration to change the file properties');

            /**
             * Sheet 1
             */

            $excel->sheet('Match check no and amount', function ($sheet) use ($paymentsArray,$company,$bu,$bank,$accountno, $datein, $nav_setup_no, $login_user) {

                $sheet->setOrientation('landscape');

                $sheet->mergeCells('A1:J1');

                $sheet->cell('A1', function ($cell) use ($company,$bu, $bank, $accountno) {

                    $cell->setValue($company->company . " : " . $bu->bname . " : " . $bank . " - " . $accountno);
                    $cell->setFontSize(16);
                    $cell->setBackground('#17BAB8');
                    $cell->setFontColor('#ffffff');

                });

                $sheet->cell('A2', function ($cell) use ($datein) {

                    $cell->setValue($datein);

                });

                $sheet->mergeCells('A4:J4');

                $sheet->cell('A4', function ($cell) use ($datein) {

                    $cell->setValue('DISBURSEMENT WITH MATCH CHECK NO AND AMOUNT');

                });

                $sheet->prependRow(5, ['Trans Description', 'Check No.','Bank Date','Bank Amount','','CV No','Check No','Posting Date','Check Date','Check Amount']);

                $month = DateClass::formatDate($datein, 'm');
                $year = DateClass::formatDate($datein, 'Y');

                $ar = array(
                    'check'	=> [
                        'where'		=> [
                            [DB::raw('MONTH(date_posted)'), $month],
                            [DB::raw('YEAR(date_posted)'), $year],
                            ['nav_setup_no', $nav_setup_no],
                            ['match_type', 'match check'],
                            ['bu', $login_user->bunitid],
                            ['company', $login_user->company_id]
                        ],
                    ],
                    'book'	=> [
                        'where'		=> [
                            ['label_match', 'match check'],
                            ['bu_unit', $login_user->bunitid],
                            ['company', $login_user->company_id]
                        ],
                    ],
                    'nav_setup_no'  => $nav_setup_no,
                    'type'	=> "with checkno match check and amount",
                    'selectcheck'    => 1,
                    'selectbook'    => 1,
                );

                $readexcel = new ExcelClass();

                $entries = $readexcel->get_entries($ar);

                $this->summary_match_check_and_amount = $readexcel->summary($entries);

                $allentry = $readexcel->allentry($entries);

                //dd($allentry);

                $sheet->fromArray($allentry, null, 'A6', false, false);

            });

            /**
             * Sheet 2
             */

            $excel->sheet('Match check no only', function ($sheet) use ($paymentsArray,$company,$bu,$bank,$accountno, $datein, $nav_setup_no, $login_user) {

                $sheet->setOrientation('landscape');

                $sheet->mergeCells('A1:J1');

                $sheet->cell('A1', function ($cell) use ($company,$bu, $bank, $accountno) {

                    $cell->setValue($company->company . " : " . $bu->bname . " : " . $bank . " - " . $accountno);
                    $cell->setFontSize(16);
                    $cell->setBackground('#17BAB8');
                    $cell->setFontColor('#ffffff');

                });

                $sheet->cell('A2', function ($cell) use ($datein) {

                    $cell->setValue($datein);

                });

                $sheet->mergeCells('A4:J4');

                $sheet->cell('A4', function ($cell) use ($datein) {

                    $cell->setValue('DISBURSEMENT WITH MATCH CHECK NO ONLY');

                });

                $sheet->prependRow(5, ['Trans Description', 'Check No.','Bank Date','Bank Amount','','CV No','Check No','Posting Date','Check Date','Check Amount']);

                $month = DateClass::formatDate($datein, 'm');
                $year = DateClass::formatDate($datein, 'Y');

                $ar = array(
                    'check'	=> [
                        'where'		=> [
                            [DB::raw('MONTH(date_posted)'), $month],
                            [DB::raw('YEAR(date_posted)'), $year],
                            ['nav_setup_no', $nav_setup_no],
                            ['match_type', 'match check'],
                            ['bu', $login_user->bunitid],
                            ['company', $login_user->company_id]
                        ],
                    ],
                    'book'	=> [
                        'where'		=> [
                            ['label_match', 'match check'],
                            ['bu_unit', $login_user->bunitid],
                            ['company', $login_user->company_id],
                            [DB::raw('MONTH(cv_date)'), $month],
                            [DB::raw('YEAR(cv_date)'), $year],
                        ],
                    ],
                    'nav_setup_no'  => $nav_setup_no,
                    'type'	=> "with match check no only",
                    'selectcheck'    => 1,
                    'selectbook'    => 1,
                );

                $readexcel = new ExcelClass();

                $entries = $readexcel->get_entries($ar);

                $this->summary_match_check_only = $readexcel->summary($entries);

                $allentry = $readexcel->allentry($entries);

                //dd($allentry);

                $sheet->fromArray($allentry, null, 'A6', false, false);

            });

            /**
             * Sheet 3
             */

            $excel->sheet('Match check but duplicate entry', function ($sheet) use ($paymentsArray,$company,$bu,$bank,$accountno, $datein, $nav_setup_no, $login_user) {

                $sheet->setOrientation('landscape');

                $sheet->mergeCells('A1:J1');

                $sheet->cell('A1', function ($cell) use ($company,$bu, $bank, $accountno) {

                    $cell->setValue($company->company . " : " . $bu->bname . " : " . $bank . " - " . $accountno);
                    $cell->setFontSize(16);
                    $cell->setBackground('#17BAB8');
                    $cell->setFontColor('#ffffff');

                });

                $sheet->cell('A2', function ($cell) use ($datein) {

                    $cell->setValue($datein);

                });

                $sheet->mergeCells('A4:J4');

                $sheet->cell('A4', function ($cell) use ($datein) {

                    $cell->setValue('DISBURSEMENT WITH MATCH CHECK BUT DUPLICATE IN ENTRY');

                });

                $sheet->prependRow(5, ['Trans Description', 'Check No.','Bank Date','Bank Amount','','CV No','Check No','Posting Date','Check Date','Check Amount']);

                $month = DateClass::formatDate($datein, 'm');
                $year = DateClass::formatDate($datein, 'Y');

                $ar = array(
                    'check'	=> [
                        'where'		=> [
                            [DB::raw('MONTH(date_posted)'), $month],
                            [DB::raw('YEAR(date_posted)'), $year],
                            ['nav_setup_no', $nav_setup_no],
                            ['bu', $login_user->bunitid],
                            ['company', $login_user->company_id]
                        ],
                    ],
                    'book'	=> [
                        'where'		=> [
                            ['baccount_no', $nav_setup_no],
                            ['bu_unit', $login_user->bunitid],
                            ['company', $login_user->company_id],
                            [DB::raw('MONTH(cv_date)'), $month],
                            [DB::raw('YEAR(cv_date)'), $year],
                        ],
                    ],
                    'nav_setup_no'  => $nav_setup_no,
                    'type'	=> "with match check no only",
                    'selectcheck'    => 1,
                    'selectbook'    => 1,
                );

                $readexcel = new ExcelClass();

                $entries = $readexcel->get_duplicate_entries($ar);

                $this->summary_duplicate_entry = $readexcel->summary($entries, 1);

                $fuse_check_book = $readexcel->merge_assoc_array($entries);

                $sheet->fromArray($fuse_check_book, null, 'A6', false, false);

            });

            /**
             * Sheet 4
             */

            $excel->sheet('Unmatch with check no', function ($sheet) use ($paymentsArray,$company,$bu,$bank,$accountno, $datein, $nav_setup_no, $login_user) {

                $sheet->setOrientation('landscape');

                $sheet->mergeCells('A1:J1');

                $sheet->cell('A1', function ($cell) use ($company,$bu, $bank, $accountno) {

                    $cell->setValue($company->company . " : " . $bu->bname . " : " . $bank . " - " . $accountno);
                    $cell->setFontSize(16);
                    $cell->setBackground('#17BAB8');
                    $cell->setFontColor('#ffffff');

                });

                $sheet->cell('A2', function ($cell) use ($datein) {

                    $cell->setValue($datein);

                });

                $sheet->mergeCells('A4:J4');

                $sheet->cell('A4', function ($cell) use ($datein) {

                    $cell->setValue('DISBURSEMENT WITH UNMATCH CHECK NO');

                });

                $sheet->prependRow(5, ['Trans Description', 'Check No.','Bank Date','Bank Amount','','CV No','Check No','Posting Date','Check Date','Check Amount','Status']);

                $month = DateClass::formatDate($datein, 'm');
                $year = DateClass::formatDate($datein, 'Y');

                $args = array(
                    'check'	=> [
                        'where'		=> [
                            ['check_no', '!=', 0],
                            [DB::raw('MONTH(date_posted)'), $month],
                            [DB::raw('YEAR(date_posted)'), $year],
                            ['match_type', ''],
                            ['nav_setup_no', $nav_setup_no],
                            ['bu', $login_user->bunitid],
                            ['company', $login_user->company_id]
                        ],
                    ],
                    'book'	=> [
                        'where'		=> [
                            ['baccount_no', $nav_setup_no],
                            ['bu_unit', $login_user->bunitid],
                            ['company', $login_user->company_id],
                            ['check_no', '!=', 0],
                            ['label_match', ''],
                            [DB::raw('MONTH(cv_date)'), $month],
                            [DB::raw('YEAR(cv_date)'), $year],
                        ],
                    ],
                    'nav_setup_no'  => $nav_setup_no,
                    'type'	=> "unmatch check no only",
                    'selectcheck'    => 1,
                    'selectbook'    => 1,
                );

                $readexcel = new ExcelClass();

                $entries = $readexcel->get_entries($args);

                $this->summary_unmatch_checkno = $readexcel->summary($entries, 1);

                $fuse_check_book = $readexcel->merge_assoc_array($entries,1);

                $sheet->fromArray($fuse_check_book, null, 'A6', false, false);

            });


            /**
             * Sheet 5
             */

            $excel->sheet('Unmatch without check no', function ($sheet) use ($paymentsArray,$company,$bu,$bank,$accountno, $datein, $nav_setup_no, $login_user) {

                $sheet->setOrientation('landscape');

                $sheet->mergeCells('A1:J1');

                $sheet->cell('A1', function ($cell) use ($company,$bu, $bank, $accountno) {

                    $cell->setValue($company->company . " : " . $bu->bname . " : " . $bank . " - " . $accountno);
                    $cell->setFontSize(16);
                    $cell->setBackground('#17BAB8');
                    $cell->setFontColor('#ffffff');

                });

                $sheet->cell('A2', function ($cell) use ($datein) {

                    $cell->setValue($datein);

                });

                $sheet->mergeCells('A4:J4');

                $sheet->cell('A4', function ($cell) use ($datein) {

                    $cell->setValue('DISBURSEMENT WITH UNMATCH CHECK NO');

                });

                $sheet->prependRow(5, ['Trans Description', 'Check No.','Bank Date','Bank Amount','','CV No','Check No','Posting Date','Check Date','Check Amount']);

                $month = DateClass::formatDate($datein, 'm');
                $year = DateClass::formatDate($datein, 'Y');

                $args = array(
                    'check'	=> [
                        'where'		=> [
                            ['check_no', 0],
                            [DB::raw('MONTH(date_posted)'), $month],
                            [DB::raw('YEAR(date_posted)'), $year],
                            ['match_type', ''],
                            ['nav_setup_no', $nav_setup_no],
                            ['bu', $login_user->bunitid],
                            ['company', $login_user->company_id]
                        ],
                    ],
                    'book'	=> [
                        'where'		=> [
                            ['baccount_no', $nav_setup_no],
                            ['bu_unit', $login_user->bunitid],
                            ['company', $login_user->company_id],
                            ['check_no', 0],
                            ['label_match', ''],
                            [DB::raw('MONTH(cv_date)'), $month],
                            [DB::raw('YEAR(cv_date)'), $year],
                        ],
                    ],
                    'nav_setup_no'  => $nav_setup_no,
                    'type'	=> "unmatch check no only",
                    'selectcheck'    => 1,
                    'selectbook'    => 1,
                );

                $readexcel = new ExcelClass();

                $entries = $readexcel->get_entries($args);

                $this->summary_unmatch_no_checkno = $readexcel->summary($entries, 1);

                $fuse_check_book = $readexcel->merge_assoc_array($entries);

                $sheet->fromArray($fuse_check_book, null, 'A6', false, false);

            });

            /**
             * Sheet 6
             */

            $excel->sheet('Summary', function ($sheet) use ($paymentsArray,$company,$bu,$bank,$accountno, $datein, $nav_setup_no, $login_user) {

                $sheet->setOrientation('landscape');

                $sheet->mergeCells('A1:J1');

                $sheet->cell('A1', function ($cell) use ($company,$bu, $bank, $accountno) {

                    $cell->setValue($company->company . " : " . $bu->bname . " : " . $bank . " - " . $accountno);
                    $cell->setFontSize(16);
                    $cell->setBackground('#17BAB8');
                    $cell->setFontColor('#ffffff');

                });

                $sheet->cell('A2', function ($cell) use ($datein) {

                    $cell->setValue($datein);

                });

                $sheet->mergeCells('A4:J4');

                $sheet->cell('A4', function ($cell) use ($datein) {

                    $cell->setValue('TOTAL SUMMARY');

                });

                $month = DateClass::formatDate($datein, 'm');
                $year = DateClass::formatDate($datein, 'Y');

                $summary = [];

                $header = [
                    ['Check Total Matched Check No and Amount', number_format($this->summary_match_check_and_amount['check_sum'],2)],
                    ['Book Total Matched Check No and Amount', number_format($this->summary_match_check_and_amount['book_sum'],2)],
                    ['Check Total Matched Check No Only', number_format($this->summary_match_check_only['check_sum'],2)],
                    ['Book Total Matched Check No Only', number_format($this->summary_match_check_only['book_sum'],2)],
                    ['Check Total Matched Check No But Duplicate Entry', number_format($this->summary_duplicate_entry['check_sum'],2)],
                    ['Book Total Matched Check No But Duplicate Entry', number_format($this->summary_duplicate_entry['book_sum'],2)],
                    ['Check Total Unmatched with Check No', number_format($this->summary_unmatch_checkno['check_sum'],2)],
                    ['Book Total Unmatched with Check No', number_format($this->summary_unmatch_checkno['book_sum'],2)],
                    ['Check Total Unmatched without Check No', number_format($this->summary_unmatch_no_checkno['check_sum'],2)],
                    ['Book Total Unmatched without Check No', number_format($this->summary_unmatch_no_checkno['book_sum'],2)]

                ];

                //dd($header);

                for ($i=0;$i<5;$i++) {

                    $summary[$i][] = "Check Total Matched Check No and Amount";

                }

                $sheet->fromArray($header, null, 'A5', false, false);

            });


            $excel->setActiveSheetIndex(0);

        })->download('xlsx');



    }

}
