<?php

namespace myocuhub\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{

    public function boot()
    {
        //
    }

    public function register()
    {
        App::bind('helper', function()
        {
            return new \myocuhub\Helpers\Helper;
        });
    }
}
