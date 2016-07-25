<?php

namespace myocuhub\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('ifexists', function($expression) {
            $expression = str_replace(array( '(', ')' ), '', $expression);
            $params = explode(",", $expression);
            $key = trim($params[0]);
            $array = trim($params[1]);
            $value = $array. "[$key]";
            return "<?php echo array_key_exists($key, $array) ? $value : null ; ?>";
        });
        Blade::directive('break', function() {
            return "<?php break; ?>";
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
