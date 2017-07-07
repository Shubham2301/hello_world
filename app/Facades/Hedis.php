<?php

namespace myocuhub\Facades;

use Illuminate\Support\Facades\Facade;

class Hedis extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'hedis';
    }
}
