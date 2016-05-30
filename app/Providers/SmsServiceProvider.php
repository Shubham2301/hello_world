<?php

namespace myocuhub\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
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
        App::bind('sms', function()
        {
            return new \myocuhub\Services\Twilio\TwilioMessaging;
        });
    }
}
