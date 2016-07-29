<?php

namespace myocuhub\Helpers;

use myocuhub\Services\MandrillService\MandrillService;

trait MandrillHelper{

	public static function mandrillTemplates(){  
        return (new MandrillService)->templates();
    }

}