<?php

namespace myocuhub\Helpers;

trait DemographicsHelper{

	public static function languages(){
		return self::invertConfig('constants.language');
	}

	public static function genders(){
		return self::invertConfig('constants.gender');
	}

}