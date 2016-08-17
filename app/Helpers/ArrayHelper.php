<?php

namespace myocuhub\Helpers;

trait ArrayHelper
{

    public function arrayCountCaseInsensitiveValues($array)
    {
        $ret_array = array();
        foreach ($array as $value) {
            foreach ($ret_array as $key2 => $value2) {
                if (strtolower($key2) == strtolower($value)) {
                    $ret_array[$key2]++;
                    continue 2;
                }
            }
            $ret_array[$value] = 1;
        }
        return $ret_array;
    }
}
