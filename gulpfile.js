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

    mix.babel(['main.js'],'public/js/main.js');
    mix.babel(['sesconnect.js'],'public/js/sesconnect.js');

});
