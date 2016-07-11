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

    mix.less(['style.less', 'announcements.less'], 'public/css/style.css');
    mix.less(['directmail.less'], 'public/css/directmail.css');
    mix.less(['referral.less'], 'public/css/referral.css');
    mix.less(['patient.less'], 'public/css/patient.css');
    mix.less(['practice.less'], 'public/css/practice.css');
    mix.less(['provider.less'], 'public/css/provider.css');
    mix.less(['appointment.less'], 'public/css/appointment.css');
    mix.less(['careconsole.less'], 'public/css/careconsole.css');
    mix.less(['networks.less'], 'public/css/networks.css');
    mix.less(['file_exchange.less'], 'public/css/file_exchange.css');
    mix.less(['users.less'], 'public/css/users.css');
    mix.less(['reporting.less'], 'public/css/reporting.css');
    mix.less(['reports.less'], 'public/css/reports.css');
    mix.less(['web_forms.less'], 'public/css/web_forms.css');
    mix.less(['web_forms_print.less'], 'public/css/web_forms_print.css');
    mix.less(['patient_records.less'], 'public/css/patient_records.css');

    mix.babel(['main.js', 'announcements.js'], 'public/js/main.js');
    mix.babel(['sesconnect.js'], 'public/js/sesconnect.js');
    mix.babel(['referraltype.js'], 'public/js/referraltype.js');
    mix.babel(['patient.js', 'referred_by.js'], 'public/js/patient.js');
    mix.babel(['provider.js'], 'public/js/provider.js');
    mix.babel(['practice.js'], 'public/js/practice.js');
    mix.babel(['appointment.js'], 'public/js/appointment.js');
    mix.babel(['import.js'], 'public/js/import.js');
    mix.babel(['careconsole.js', 'referred_by.js'], 'public/js/careconsole.js');
    mix.babel(['networks.js'], 'public/js/networks.js');
    mix.babel(['audit.js'], 'public/js/audit.js');
    mix.babel(['tojson.js'], 'public/js/tojson.js');
    mix.babel(['toxml.js'], 'public/js/toxml.js');
    mix.babel(['file_exchange.js'], 'public/js/file_exchange.js');
    mix.babel(['users.js'], 'public/js/users.js');
    mix.babel(['reporting.js'], 'public/js/reporting.js');
    mix.babel(['reports.js'], 'public/js/reports.js');
    mix.babel(['session_timeout.js'], 'public/js/session_timeout.js');
    mix.babel(['web_forms.js'], 'public/js/web_forms.js');
    mix.babel(['patient_records.js'], 'public/js/patient_records.js');

    mix.version(['css/style.css',
                'css/directmail.css',
                'css/referral.css',
                'css/patient.css',
                'css/practice.css',
                'css/provider.css',
                'css/appointment.css',
                'css/careconsole.css',
                'css/networks.css',
                'css/file_exchange.css',
                'css/announcements.css',
                'css/users.css',
                'css/reporting.css',
                'css/reports.css',
                'css/web_forms.css',
                'css/web_forms_print.css',
                'css/patient_records.css',
                'js/main.js',
                'js/sesconnect.js',
                'js/referraltype.js',
                'js/patient.js',
                'js/provider.js',
                'js/practice.js',
                'js/appointment.js',
                'js/import.js',
                'js/careconsole.js',
                'js/networks.js',
                'js/file_exchange.js',
                'js/users.js',
                'js/reporting.js',
                'js/reports.js',
                'js/session_timeout.js',
                'js/audit.js',
                'js/web_forms.js',
                'js/patient_records.js',
                'images/*'
                ]);
});
