<?php

namespace myocuhub\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class SESServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('ses', function()
        {
            return new \myocuhub\Services\SES\SESMessaging;
        });
    }
}
