<?php

namespace myocuhub\Providers;

use Illuminate\Support\ServiceProvider;

class CCDAServiceProvider extends ServiceProvider
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
        $this->app->bind('ccda_service', function () {
            return new \myocuhub\Services\CCDAService;
        });
    }
}
