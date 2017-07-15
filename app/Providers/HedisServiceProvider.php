<?php

namespace myocuhub\Providers;

use Illuminate\Support\ServiceProvider;

class HedisServiceProvider extends ServiceProvider
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
        $this->app->bind('hedis', 'myocuhub\Services\CustomFileExport\HedisSupplementary\HedisExport');
    }
}
