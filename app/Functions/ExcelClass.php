<?php
/**
 * Created by PhpStorm.
 * User: AGC-PC
 * Date: 3/3/2018
 * Time: 10:31 AM
 */

namespace App\Functions;


use App\Checkingaccounts;
use App\PdcLine;
use DateTime;
use Illuminate\Support\Facades\DB;
use PHPExcel_Shared_Date;

class ExcelClass
{
    public static function numberDate($datetimeformat) {
        if (is_numeric($datetimeformat)) {
            $numberToDate = PHPExcel_Shared_Date::ExcelToPHPObject($datetimeformat);
            $newdate = $numberToDate->format('m/d/y');
            return $newdate;
        }
        return $datetimeformat;
    }

    public static function saveCheckingAccounts($data, $login_user, $code, $bankaccid, $filename, $max) {

        DB::beginTransaction();
        $percent = 0;
        try {

            for ( $row = 0; $row < count($data); $row++ ) {

                $postdate = $data[$row][0];
                $effdate = $data[$row][1];
                $desc = $data[$row][2];
                $checkno = $data[$row][3];
                $withdrawal = $data[$row][4];
                $deposit = $data[$row][5];
                $balance = $data[$row][6];
                $filename = $data[$row][7];

                if ( $withdrawal != "" ) {
                    $trans_type = 'W';
                    $trans_amount = $withdrawal;
                }

                if ($deposit != "") {
                    $trans_type = 'D';
                    $trans_amount = $deposit;
                }

                $is_exist = Checkingaccounts::where('date_posted', $postdate)
                    ->where('effective_date', $effdate)
                    ->where('check_no', $checkno)
                    ->where('withdrawals', $withdrawal)
                    ->where('deposits', $deposit)
                    ->where('trans_amount', $trans_amount)
                    ->where('balance', $balance)
                    ->where('bu', $login_user->bunitid)
                    ->where('company', $login_user->company_id)
                    ->where('nav_setup_no', $code)
                    ->count('id');

                if ($is_exist <= 0) {

                    $checks = new Checkingaccounts;

                    $checks->date_posted = $postdate;
                    $checks->effective_date = $effdate;
                    $checks->transaction_desc = $desc;
                    $checks->check_no = $checkno;
                    $checks->withdrawals = $withdrawal;
                    $checks->deposits = $deposit;
                    $checks->trans_amount = $trans_amount;
                    $checks->balance = $balance;
                    $checks->trans_type = $trans_type;
                    $checks->bu = $login_user->bunitid;
                    $checks->company = $login_user->company_id;
                    $checks->nav_setup_no = $code;
                    $checks->bankaccount_id = $bankaccid;
                    $checks->uploaded_by = $login_user->user_id;
                    $checks->created_by = $login_user->user_id;
                    $checks->updated_by = $login_user->user_id;
                    $checks->file_name = $filename;
                    $checks->save();

                }

                $percent = (100/$max)*$row;
                $response = [
                    'progress'	=> number_format($percent),
                    'status'	=> 'Saving...'
                ];
                echo json_encode($response);

            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return [
            'percent'	=> $percent,
            'count'		=> $row
        ];

    }

    public function get_entries($ar = array()) {
        //var_dump($ar);,
        //var_dump($ar['book']['where']);
        $checkentry = array();

        /**
         * [a variable that return data from checking_account table]
         * @return [date_posted, transaction_desc, trans_amount, check_no, match_type, nav_setup_no, company, bu]
         * @param array $ar['check']['select'] ['select statement']
         * @param array $ar['check']['where'] ['where statement']
         */
        //$checks = $CI->checkingaccounts_model->get_match_checkno_amount( $ar['check']['select'], $ar['check']['where'] );

        //if ( $ar['selectcheck'] == 1 ) {
            $checks = Checkingaccounts::select('date_posted', 'transaction_desc', 'trans_amount', 'check_no', 'match_type', 'nav_setup_no', 'company', 'bu')
                ->where($ar['check']['where'])
                ->get();
        //}

        if($ar['type'] == "with checkno match check and amount") {
            $ar['progress']['max'] = count($checks);
            //push array to where
            //$ar['book']['where']['baccount_no'] = $ar['check']['where']['nav_setup_no'];
            $baccount_no = $ar['nav_setup_no'];

            foreach ($checks as $key => $check) {
                $checkno = $check->check_no;
                $checkamount = $check->trans_amount;
                $bankno      = $check->nav_setup_no;
                $company     = $check->company;
                $buunit      = $check->bu;

                //push array to where
                //$ar['book']['where']['check_amount'] = $checkamount;
                //$ar['book']['where']['check_no'] = $checkno;

                /**
                 * [$count_dis returns number of count array of book data]
                 * @return number count
                 */
                //$count_dis = $CI->disbursement_model->count_match_checkno_amount($ar['book']['select'], $ar['book']['where']);
                $count_dis = PdcLine::select(DB::raw('COUNT(cv_no) as count'))
                    ->where($ar['book']['where'])
                    ->where('baccount_no', $baccount_no)
                    ->where('check_amount', $checkamount)
                    ->where('check_no', $checkno)
                    ->first();

                $ar['check']['where']['check_no'] = $checkno;
                //$count_check = $CI->checkingaccounts_model->count_match_check('id', $ar['check']['where']);
                $count_check = Checkingaccounts::select(DB::raw('COUNT(id) as count'))
                    ->where($ar['check']['where'])
                    ->where('check_no', $checkno)
                    ->first();

                if ($count_dis->count == 1 && $count_check->count < 2) {

                    /**
                     * [$books description]
                     * @return array cv_date, check_date, check_no, check_amount, cv_no
                     * @param [type] $[name] [<description>]
                     */
                    //$books = $CI->disbursement_model->get_match_checkno_amount('cv_date, check_date, check_no, check_amount, cv_no', $ar['book']['where']);

                    $books = PdcLine::select('cv_date', 'check_date', 'check_no', 'check_amount', 'cv_no')
                        ->where($ar['book']['where'])
                        ->where('baccount_no', $ar['nav_setup_no'])
                        ->where('check_amount', $checkamount)
                        ->where('check_no', $checkno)
                        ->get();

                    $key = array('cv_date','check_date','check_no','check_amount','cv_no');

                    foreach ($books as $book) {
                        $values = array($book->cv_date, $book->check_date, $book->check_no, $book->check_amount, $book->cv_no);
                    }

                    $new_value = array_combine($key, $values);

                    //var_dump($new_value);

                    $checkentry[] = array(
                        'check'	=> array(
                            $check->transaction_desc,
                            $check->check_no,
                            date("m/d/Y", strtotime($check->date_posted)),
                            $check->trans_amount,
                        ),
                        'book'	=> array(
                            $new_value['cv_no'],
                            $new_value['check_no'],
                            date("m/d/Y", strtotime($new_value['cv_date'])),
                            date("m/d/Y", strtotime($new_value['check_date'])),
                            $new_value['check_amount']
                        )
                    );
                }
            }

            //var_dump($ar['progress']['count']);

        } elseif($ar['type'] == "with match check no only") {

            //foreach ($checks as $key => $check) {

            for ($i=0; $i < count($checks); $i++) {

                $checkno = $checks[$i]->check_no;
                $checkamount = $checks[$i]->trans_amount;

                //$ar['book']['where']['check_no'] = $checkno;
                //$ar['book']['where']['check_amount!='] = $checkamount;
                //$ar['book']['where']['baccount_no']	= $ar['nav_setup_no'];

                //$count_book = $CI->disbursement_model->count_match_checkno_amount($ar['book']['select'], $ar['book']['where']);

                $count_book = PdcLine::select(DB::raw('COUNT(cv_no) as count'))
                    ->where($ar['book']['where'])
                    ->where('check_no', $checkno)
                    ->where('check_amount', '!=', $checkamount)
                    ->where('baccount_no', $ar['nav_setup_no'])
                    ->first();

                //$count_check = $CI->checkingaccounts_model->count_match_check('id', $ar['check']['where']);

                $count_check = Checkingaccounts::select(DB::raw('COUNT(id) as count'))
                    ->where($ar['check']['where'])
                    ->where('check_no', $checkno)
                    ->first();

                //var_dump($count_check);
                if ($count_book->count == 1 && $count_check->count < 2) {

                    //$books = $CI->disbursement_model->get_match_checkno_amount($ar['book']['select'], $ar['book']['where']);

                    $books = PdcLine::select('cv_date', 'check_date', 'check_no', 'check_amount', 'cv_no')
                        ->where($ar['book']['where'])
                        ->where('baccount_no', $ar['nav_setup_no'])
                        ->where('check_amount','!=', $checkamount)
                        ->where('check_no', $checkno)
                        ->get();

                    //var_dump($checks[$i-1]->check_no."=>=+".$checks[$i]->check_no);

                    $key = array('cv_date','check_date','check_no','check_amount','cv_no');
                    $value = Array();

                    foreach ($books as $book) {

                        //$value = $this->compare_check_no($checks,$book, $i);
                        $value = Array(date("m/d/Y", strtotime($book->cv_date)), date("m/d/Y", strtotime($book->check_date)), $book->check_no,$book->check_amount, $book->cv_no);

                    }

                    $new_value = array_combine($key, $value);

                    $checkentry[] = array(
                        'check'	=> array(
                            $checks[$i]->transaction_desc,
                            $checks[$i]->check_no,
                            date("m/d/Y", strtotime($checks[$i]->date_posted)),
                            $checks[$i]->trans_amount,
                        ),
                        'book'	=> array(
                            $new_value['cv_no'],
                            $new_value['check_no'],
                            $new_value['cv_date'],
                            $new_value['check_date'],
                            $new_value['check_amount']
                        )
                    );
                }
            }

        } else {

            //$books = $CI->disbursement_model->get_match_checkno_amount($ar['book']['select'], $ar['book']['where']);
            //dd($ar['book']['where']);
            $books = PdcLine::select('cv_no', 'check_no', 'cv_date', 'check_date', 'check_amount', 'cv_status')
                ->where($ar['book']['where'])
                ->get();

            $checked = array();
            $booked = array();
            foreach ($checks as $key => $check) {

                $checkno = $check->check_no;

                if ($check->check_no == 0) {
                    $checkno = "";
                }

                $checked[] = [
                    $check->transaction_desc,
                    $checkno,
                    date("m/d/Y", strtotime($check->date_posted)),
                    $check->trans_amount,
                ];

            }

            foreach ($books as $book) {
                $cvdate = date("m/d/Y", strtotime($book->cv_date));
                $checkdate = date("m/d/Y", strtotime($book->check_date));
                $checkamount = $book->check_amount;

                $booked[] = array(
                    $book->cv_no,
                    $book->check_no,
                    $cvdate,
                    $checkdate,
                    $checkamount,
                    $book->cv_status

                );
            }

            $checkentry = [
                'check'	=> $checked,
                'book'	=> $booked
            ];
        }

        return $checkentry;

    }

    public function summary($args = array(), $ar = 0) {
        $total_check_amnt = 0;
        $total_book_amnt = 0;

        if (empty($args)) {
            return -1;
        }

        switch ($ar) {
            case 1:
                //var_dump($args);
                foreach ($args['check'] as $check) {

                    $checkamount = str_replace(",", "", $check[3]);

                    $total_check_amnt += $checkamount;
                    // if (isset($arg['book'])) {
                    // 	$book = $arg['book'];
                    // 	$bookamount = str_replace(",", "", $book[4]);
                    // 	$total_book_amnt += $bookamount;
                    // }
                }

                foreach ($args['book'] as $book) {
                    $bookamount = str_replace(",", "", $book[4]);
                    $total_book_amnt += $bookamount;
                }
                break;

            default:
                foreach ($args as $arg) {

                    if (isset($arg['check'])) {
                        $bank = $arg['check'];
                        $checkamount = str_replace(",", "", $bank[3]);
                        $total_check_amnt += $checkamount;
                    }
                    if (isset($arg['book'])) {
                        $book = $arg['book'];
                        //var_dump($book[4]);
                        $bookamount = str_replace(",", "", $book[4]);
                        $total_book_amnt += $bookamount;
                    }
                }
                break;
        }

        return array(
            'check_sum'	=> $total_check_amnt,
            'book_sum'	=> $total_book_amnt
        );
    }

    public function allentry($args = array()) {
        $allentry = array();


        // if ($countbook > $countbank) {
        // 	$dif  = $countbook - $countbank;

        // 	$bank[] = $args['bank'];

        // 	for($x=1;$x<$dif;$x++):
        // 		$bank[] = "blank|blank|blank|blank|blank";

        // 	endfor;
        // 	$entry = $args['book'];
        // } elseif ($countbook < $countbank) {
        // 	$dif  = $countbank - $countbook;

        // 		$book[] = $args['book'];

        // 		for($x=1;$x<$dif;$x++):
        // 			$book[] = "blank|blank|blank|blank|blank";
        // 		endfor;
        // 		$entry = $args['bank'];
        // } else {
        // 	$entry = $args['book'];
        // }

        foreach ($args as $key => $arg) {

            if (isset($arg['check'])) {
                if ($arg['check'][1] == -1) {
                    $checkno = '';
                } else {
                    $checkno = $arg['check'][1];
                }

                $allentry[] = array(
                    $arg['check'][0],
                    $checkno,
                    $arg['check'][2],
                    $arg['check'][3],
                );
            }
            if (isset($arg['book'])) {
                $allentry[$key][4] = '';
                $allentry[$key][5] = $arg['book'][0];
                $allentry[$key][6] = $arg['book'][1];
                $allentry[$key][7] = $arg['book'][2];
                $allentry[$key][8] = $arg['book'][3];
                $allentry[$key][9] = $arg['book'][4];
            }

            // $allentry[] = array(
            // 	$arg['check'][0],
            // 	$arg['check'][1],
            // 	$arg['check'][2],
            // 	$arg['check'][3],
            // 	$arg['book'][0],
            // 	$arg['book'][1],
            // 	$arg['book'][2],
            // 	$arg['book'][3],
            // 	$arg['book'][4],
            // );
        }
        //var_dump($allentry[0][0]);

        return $allentry;
    }

    public function get_duplicate_entries($args) {

        /**
         * add where clause to the array of where statement
         * @var [type]
         */
        //$count_check_where = $args['check']['where'];
        //$count_check_where['match_type'] = 'match check';
        $type = 'match check';

        /**
         * Contains checks data
         * @var Array
         */
        //$checks = $CI->checkingaccounts_model->get_match_checkno_amount($args['check']['select'], $count_check_where);

        $checks = Checkingaccounts::select('date_posted', 'transaction_desc', 'trans_amount', 'check_no', 'match_type', 'nav_setup_no', 'company', 'bu')
            ->where($args['check']['where'])
            ->where('match_type', $type)
            ->get();

        $count_where_book = $args['book']['where'];
        $count_check_where = $args['check']['where'];

        /**
         * Stores the final duplicate entries from the check
         * @var array
         */
        $final_checks = [];

        foreach ($checks as $check) {
            $amount = $check->trans_amount;
            $checkno = $check->check_no;
            $checkdate = $check->date_posted;
            $desc = $check->transaction_desc;

            /**
             * Supply additional where clause to book
             * @var array
             */
            $count_where_book['check_no'] = $checkno;

            /**
             * Supply additional where clause to check
             * @var array
             */
            $count_check_where['check_no'] = $checkno;

            /**
             * Countains count data from book and check
             * @var [type]
             */
            //$count_book = $CI->disbursement_model->count_match_checkno_amount("check_no", $count_where_book);

            $count_book = PdcLine::select(DB::raw('COUNT(check_no)'))
                ->where($args['book']['where'])
                ->where('check_no', $checkno)
                ->first();

            //$count_check = $CI->checkingaccounts_model->count_match_check("check_no", $count_check_where);

            $count_check = Checkingaccounts::select(DB::raw('COUNT(check_no)'))
                ->where($args['check']['where'])
                ->where('check_no', $checkno)
                ->where('match_type', $type)
                ->first();

            if ($count_check->count > $count_book->count) {

                $final_checks['check1'][] = [
                    $desc,
                    $checkno,
                    date("m/d/Y", strtotime($checkdate)),
                    $amount,
                ];

            } elseif ($count_book->count > $count_check->count) {

                $final_checks['check2'][] = [
                    $desc,
                    $checkno,
                    date("m/d/Y", strtotime($checkdate)),
                    $amount,
                ];
            }
        }

        $data_container = [];

        if (!empty($final_checks['check1'])) {

            $count_check = count($final_checks['check1']);
            $check = $final_checks['check1'];

            $book_where = $args['book']['where'];

            $prev_check = null;
            for ($i=0; $i < $count_check; $i++) {
                $cur_check = $check[$i][1];
                $book_where['check_no'] = $check[$i][1];

                //var_dump($book_where);

                //$book = $CI->disbursement_model->get_data_row('cv_no, check_no, cv_date, check_date, check_amount', $book_where);
                $book = PdcLine::select('cv_no', 'check_no', 'cv_date', 'check_date', 'check_amount')
                    ->where($args['book']['where'])
                    ->where('check_no', $check[$i][1])
                    ->get();

                //$count_book = $CI->disbursement_model->count_match_checkno_amount("check_no", $book_where);
//                $count_book = PdcLine::select(DB::raw('COUNT(check_no)'))
//                    ->where($args['book']['where'])
//                    ->where('check_no', $check[$i][1])
//                    ->first();


                if ( $cur_check == $prev_check ) {
                    $data_container['book'][] = [
                        '',
                        '',
                        '',
                        '',
                        '0',
                    ];
                } else {
                    $data_container['book'][] = [
                        $book->cv_no,
                        $book->check_no,
                        $book->cv_date,
                        $book->check_date,
                        $book->check_amount,
                    ];
                }

                $prev_check = $cur_check;

                $data_container['check'][] = $check[$i];
            }
        }

        if (!empty($final_checks['check2'])) {

            $count_check2 = count($final_checks['check2']);
            $check2 = $final_checks['check2'];
            $prev_check = null;

            for ($i=0; $i < $count_check2; $i++) {

                $book_where['check_no'] = $check2[$i][1];

                //$count_book = $CI->disbursement_model->count_match_checkno_amount("check_no", $book_where);

                $count_book = PdcLine::select(DB::raw('COUNT(check_no)'))
                    ->where($args['book']['where'])
                    ->where('check_no', $check2[$i][1])
                    ->first();

                //$book = $CI->disbursement_model->get_data_row_array('cv_no, check_no, cv_date, check_date, check_amount', $book_where);
                $book = PdcLine::select('cv_no', 'check_no', 'cv_date', 'check_date', 'check_amount')
                    ->where($args['book']['where'])
                    ->where('check_no', $check2[$i][1])
                    ->get();

                for ($j=0; $j < $count_book->count; $j++) {

                    $num_index_book = array_values($book[$j]);

                    $cur_check = $num_index_book[1];

                    if ( $cur_check == $prev_check ) {

                        $data_container['check'][] = [
                            '',
                            '',
                            '',
                            '0',
                        ];

                    } else {
                        $data_container['check'][] = $check2[$i];
                    }

                    $data_container['book'][] = $num_index_book;

                    $prev_check = $cur_check;

                }
            }
        }

        //var_dump($data_container);

        return $data_container;
    }

    public function merge_assoc_array($args, $c = 0) {

        $merge = array();

        if (empty($args['check'][0]) && empty($args['book'][0])) {
            return [
                0	=> [
                    0	=> '',
                    1	=> '',
                    2	=> '',
                    3	=> '',
                    4	=> '',
                    5	=> '',
                    6	=> '',
                    7	=> '',
                    8	=> '',
                ]
            ];
        }

        if (empty($args['book'][0])) {
            $ret = $this->merge_check_book(count($args['check'][0]), 0, $args, $c);

            return $ret;
        }

        if (empty($args['check'][0])) {
            $ret = $this->merge_check_book(0, count($args['book'][0]),$args, $c);

            return $ret;
        }


        $ret = $this->merge_check_book(count($args['check'][0]), count($args['book'][0]), $args, $c);

        return $ret;
    }

    public function merge_check_book($count1, $count2, $args, $c) {
        $column_count_one = $count1;
        $column_count_two = $count2;
        $total_column_count = $column_count_one + $column_count_two;

        //$count = 0;
        foreach ($args['check'] as $check) {
            $merge[] = array(
                $check[0],
                $check[1],
                $check[2],
                $check[3],
            );
        }

        switch ($c) {
            case 1:
                foreach ($args['book'] as $key => $book) {
                    if (!isset($merge[$key][0])) {
                        $merge[$key][0] = '';
                    }
                    if (!isset($merge[$key][1])) {
                        $merge[$key][1] = '';
                    }
                    if (!isset($merge[$key][2])) {
                        $merge[$key][2] = '';
                    }
                    if (!isset($merge[$key][3])) {
                        $merge[$key][3] = '';
                    }
                    $merge[$key][4] = '';
                    $merge[$key][5] = $book[0];
                    $merge[$key][6] = $book[1];
                    $merge[$key][7] = $book[2];
                    $merge[$key][8] = $book[3];
                    $merge[$key][9] = $book[4];
                    $merge[$key][10] = $book[5];
                }
                break;

            default:
                foreach ($args['book'] as $key => $book) {
                    if (!isset($merge[$key][0])) {
                        $merge[$key][0] = '';
                    }
                    if (!isset($merge[$key][1])) {
                        $merge[$key][1] = '';
                    }
                    if (!isset($merge[$key][2])) {
                        $merge[$key][2] = '';
                    }
                    if (!isset($merge[$key][3])) {
                        $merge[$key][3] = '';
                    }
                    $merge[$key][4] = $book[0];
                    $merge[$key][5] = $book[1];
                    $merge[$key][6] = $book[2];
                    $merge[$key][7] = $book[3];
                    $merge[$key][8] = $book[4];
                }
                break;
        }



        return $merge;
    }

}