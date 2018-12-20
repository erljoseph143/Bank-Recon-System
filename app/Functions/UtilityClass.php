<?php
/**
 * Created by PhpStorm.
 * User: AGC-PC
 * Date: 5/29/2018
 * Time: 1:53 PM
 */

namespace App\Functions;


class UtilityClass
{
    public static function offsets($header, $fields)
    {
        $offs = array();
        foreach ($fields as $key => $value)
        {
            preg_match_all('/' . $value . '/i', $header, $match, PREG_OFFSET_CAPTURE);
            $offs[$key] = $match[0][0];
        }
        return $offs;
    }

    public static function percents($count)
    {
        $per = array();
        for ($i = 1; $i <= 100; $i++)
        {
            $per[round($count * $i / 100)] = $i;
        }
        return $per;
    }

    public static function parse_amount($str)
    {
        $str = str_replace(',', '', $str);
        if (preg_match_all('/-/', $str, $matches) > 0)
        {
            return -doubleval(str_replace('-', '', $str));
        }
        else
        {
            return doubleval($str);
        }
    }

    public static function notify_progress($percent, $filename)
    {
        echo json_encode(["progress" => $percent, "filename" => str_replace(" ", "", str_replace(".", "", $filename))]);
        flush();
        ob_flush();
    }

    public static function is_date($date) {

        $date_now = date('m/d/Y');
        $sliced_cur_date = explode('/', $date_now);
        $sliced_date = explode('/', $date);
        $yearnow = $sliced_cur_date[2];
        $year = $sliced_date[2];

        if ($year < 1990 OR $year > $yearnow) {
            return false;
        }
    }

}