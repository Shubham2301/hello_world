<?php

namespace myocuhub\Helpers;

use DateTime;
use myocuhub\Models\Timezone;

trait DateTimeHelper
{

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

    public function timezones()
    {
        $zones = Timezone::get(['id', 'name'])->toArray();
        $timezones = [];
        foreach ($zones as $zone) {
            $timezones[$zone['id']] = $zone['name'];
        }
        return $timezones;
    }

}
