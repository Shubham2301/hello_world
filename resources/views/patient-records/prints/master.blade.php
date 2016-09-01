<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="{{ asset('lib/css/bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ public_path('css/web_forms_print.css') }}">
        @yield('imports')
    </head>
    <body>
        <div class="">
            @yield('content')
        </div>
    </body>
</html>
