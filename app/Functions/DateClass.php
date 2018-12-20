<?php
/**
 * Created by PhpStorm.
 * User: AGC-PC
 * Date: 3/7/2018
 * Time: 1:52 PM
 */

namespace App\Functions;


use DateTime;

class DateClass
{
    public static function formatDate($date, $format = 'Y-m-d') {
        $newDatePosted = new DateTime($date);
        return $newDatePosted->format($format);
    }

    public function validateDate($date) {
        $test_arr  = explode('/', $date);
        if (count($test_arr) == 3) {
            if (checkdate($test_arr[0], $test_arr[1], $test_arr[2])) {
                return 1;
            } else {
                return -1;
            }
        } else {
            return -1;
        }

    }
}