<?php

namespace myocuhub\Helpers;

trait AppHelper{

	public static function ifExists($key, $array){
		return isset($array[$key]) ? $array[$key] : null; 
	}

	public static function invertConfig($expression){
		$config = array_flip(config($expression));
        foreach ($config as $key => $value) {
               $config[$key] = ucfirst($value);
        } 
        return $config;
	}

}