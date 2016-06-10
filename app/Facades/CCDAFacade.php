<?php

namespace myocuhub\Facades;

use Illuminate\Support\Facades\Facade;

class CCDAFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ccda_service';
    }
}
