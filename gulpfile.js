var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function (mix) {

    mix.less(['style.less'],'public/css/style.css');
    mix.less(['directmail.less'],'public/css/directmail.css');
    mix.less(['referral.less'],'public/css/referral.css');
    mix.less(['patient.less'],'public/css/patient.css');
    mix.less(['provider.less'],'public/css/provider.css');

    mix.babel(['main.js'],'public/js/main.js');
    mix.babel(['sesconnect.js'],'public/js/sesconnect.js');
    mix.babel(['referraltype.js'],'public/js/referraltype.js');
    mix.babel(['patient.js'],'public/js/patient.js');
    mix.babel(['provider.js'],'public/js/provider.js');

});
