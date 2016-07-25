<?php

namespace myocuhub\Helpers;

trait AppHelper{

	public static function ifExists($key, $array){
<<<<<<< HEAD
		return isset($key, $array) ? $array[$key] : null; 
=======
		return isset($array[$key]) ? $array[$key] : null;
>>>>>>> 97fcd2e... Helpers on ifExists
	}

	public static function invertConfig($expression){
		$config = array_flip(config($expression));
        foreach ($config as $key => $value) {
               $config[$key] = ucfirst($value);
        } 
        return $config;
	}

}