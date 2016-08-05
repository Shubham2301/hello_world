<?php

namespace myocuhub\Helpers;
use DateTime;

trait DateTimeHelper{

    public function validateDate($date, $format = null)
    {
        $format = ($format) ?: config('constants.date_time_format.date_time');
        $dateInFormat = DateTime::createFromFormat($format, $date);
        return $dateInFormat && $dateInFormat->format($format) == $date;
    }


    public function formatDate($date, $format = null)
    {
        $format = ($format) ?: config('constants.date_time_format.date_time');
    	$date = new DateTime($date);
        return $date->format($format);
    }

}
