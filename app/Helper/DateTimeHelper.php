<?php
/**
 * Created by PhpStorm.
 * User: Saleh
 * Date: 9/6/2018
 * Time: 12:41 PM
 */

namespace App\Helper;


use Illuminate\Support\Facades\Lang;

class DateTimeHelper
{

    public  static  function getdifDateTime($to){
        $first_date = new \DateTime("NOW");
        $second_date = new \DateTime($to);
        $difference = $first_date->diff($second_date);
        $result = Lang::get('lang.Few_Second');
        if ($difference->y) {
            return $difference->format("%y years ago");
        }
        if ($difference->m) {
            return $difference->format("%m month ago");
        }
        if ($difference->d) {
            return $difference->format("%d Days ago");
        }
        if ($difference->h) {
            return $difference->format("%h Hours ago");
        }
        if ($difference->i) {
            return $difference->format("%i Minutes ago ");
        }
        return $result;
    }

}