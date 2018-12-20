<?php
/**
 * Created by PhpStorm.
 * User: AGC-PC
 * Date: 3/3/2018
 * Time: 3:43 PM
 */

namespace App\Functions;


use App\Checkingaccounts;
use App\PdcLine;
use Illuminate\Support\Facades\DB;

class SearchClass
{
    public function match_check_no( $xcelarr, $dbarr, $maxx, $percent, $counter ) {

        $max = count( $xcelarr );
        $dbmax = count( $dbarr )-1;
        $end = count( $dbarr )-1;
        $temp = array();
        $percent = $percent;

        if ( is_array( $xcelarr ) && is_array( $dbarr ) ) {
            for ($row=0; $row < $max; $row++) {
                $key = $xcelarr[$row][3];
                if ($key != -1) {
                    $result = $this->exponentialSearch( $dbarr, $dbmax, $key);

                    if ( $result != -1 ) {
                        $temp[] = $result;

                    } else {

                    }

                }

                 $percent = (100/$maxx)*$counter;

                 $response = array(
                 	'progress'	=> number_format($percent, 2),
                 	'status'	=> 'Matching...'
                 );
                 echo json_encode($response);

                $counter++;
            }

            return array('match' => $temp, 'search' => $dbarr);
        }

    }

    private function exponentialSearch($arr, $length, $key) {

        if ($length == 0) {
            return -1;
        }

        $bound = 1;

        while ($bound < $length && $arr[$bound]['check_no'] < $key) {
            $bound *= 2;
        }

        return $this->binarySearch( $arr, $key, intval($bound / 2), min($bound, $length) );
    }

    private function binarySearch($array, $search, $start, $end) {
        if ($start > $end) {
            return -1;
        }

        // var_dump($start);
        // var_dump($end);

        while ($start <= $end) {

            $mid = (int) floor(($start + $end)/2);

            if ( $array[$mid]['check_no'] ==  $search) {
                return array(
                    'index'		=> $mid,
                    'value'		=> $array[$mid]['check_no'],
                    'date'		=> $array[$mid]['check_date'],
                );
            } elseif ( $search < $array[$mid]['check_no'] ) {
                $end = $mid-1;
            } else {
                $start = $mid+1;
            }

        }

        return -1;
    }

    public function search_with_date($arr) {

        $newarray = array();

        if (!empty($arr['match'])) {

            foreach ($arr['match'] as $key => $value) {

                foreach ($arr['search'] as $key => $data) {

                    if ($data['check_no'] === $value['value']) {
                        $startkey = $key;

                        if (count($arr['search']) < $key) {
                            $nextkey = $key+1;
                            $nextvalue = $arr['search'][$key+1]['check_no'];
                        } else {
                            $nextkey = $key;
                            $nextvalue = $arr['search'][$key]['check_no'];
                        }

                        $newarray[] = array(
                            'key'	=> $key,
                            'value'	=> $data['check_no'],
                            'date'	=> $data['check_date']
                        );
                    }
                }

            }

        }

        return $newarray;

    }

    public function update_OC($arr, $arr2) {

        if (!empty($arr2)) {

            $count = 0;
            $max = count($arr2);

            DB::beginTransaction();
            try {
                foreach ($arr2 as $key => $value) {
                    $pdcmonth = date('n',strtotime($value['date']));
                    $pdcyear = date('Y',strtotime($value['date']));
                    foreach ($arr as $key => $value2) {
                        $bankmonth = date('n',strtotime($value2[0]));
                        $bankyear = date('Y',strtotime($value2[0]));

                        if ($value['value'] == $value2[3]) {
                            if(($pdcmonth < $bankmonth and $bankyear == $pdcyear) or ($pdcyear < $bankyear and $pdcmonth > $bankmonth) ){
                                //$this->update_status(array('status'=>'OC', 'oc_cleared'=>'cleared'), array('check_no' => $value['value']));
//                                Checkingaccounts::where('check_no', $value['value'])
//                                    ->update(['status' => 'OC', 'oc_cleared'  => 'cleared']);
                                PdcLine::where('check_no', $value['value'])
                                    ->update(['status' => 'OC', 'oc_cleared'  => 'cleared']);
                            } else {
                                //$this->update_status(array('status'=>'OC'), array('check_no' => $value['value']));
//                                Checkingaccounts::where('check_no', $value['value'])
//                                    ->update(['status' => 'OC']);
                                PdcLine::where('check_no', $value['value'])
                                    ->update(['status' => 'OC']);

                            }
                        }

                        $response = array(
                            'progress'	=> number_format( (100/$max)*$count ),
                            'status'	=> 'Updating OC...'
                        );

                        echo json_encode($response);

                        $count++;

                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
            }
        }

    }

    public function update_matches($match, $month_exported, $year_exported, $bankcode, $company, $bu) {

        if (!empty($match['match'])) {

            $count = 0;
            $max = count($match['match']);

            DB::beginTransaction();

            try {

                foreach ($match['match'] as $key => $value) {
                    /**
                     * update and put status match checking accounts
                     */
                    Checkingaccounts::where('check_no', $value['value'])
                        ->where(DB::raw('MONTH(date_posted)'), $month_exported)
                        ->where(DB::raw('YEAR(date_posted)'), $year_exported)
                        ->where('nav_setup_no', $bankcode)
                        ->where('company', $company)
                        ->where('bu', $bu)
                        ->update(['match_type' => 'match check']);

                    /**
                     * update and put status match disbursement
                     */
//                    $this->disbursement_model->update_status(array(
//                        'label_match'	=> 'match check'
//                    ), array(
//                        'check_no'		=> $value['value'],
//                        'MONTH(cv_date)'	=> $month_exported,
//                        'YEAR(cv_date)'		=> $year_exported,
//                        'baccount_no'		=> $accountid->bankno,
//                        'company'			=> $company,
//                        'bu_unit'			=> $bu
//                    ));
                    PdcLine::where('check_no', $value['value'])
                        ->where(DB::raw('MONTH(cv_date)'), $month_exported)
                        ->where(DB::raw('YEAR(cv_date)'), $year_exported)
                        ->where('baccount_no', $bankcode)
                        ->where('company', $company)
                        ->where('bu_unit', $bu)
                        ->update(['label_match'	=> 'match check']);

                    $response = array(
                        'progress'	=> number_format( (100/$max)*$count ),
                        'status'	=> 'Updating OC...'
                    );

                    echo json_encode($response);

                    $count++;

                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
            }

        }

    }

}