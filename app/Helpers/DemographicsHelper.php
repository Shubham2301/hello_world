<?php

namespace myocuhub\Helpers;

trait DemographicsHelper{

	public static function languages(){
		return self::invertConfig('constants.language');
	}

	public static function genders(){
		return self::invertConfig('constants.gender');
	}
    
    public static function getGenderIndex($gender) {
        $gender = strtolower(trim($gender));
        return config("constants.gender_variations.$gender");

	}
    
}
