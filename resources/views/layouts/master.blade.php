<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
        @if(Auth::check())
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @endif
        <title>@yield('title')</title>
        <!--[if lt IE 9]!>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
        <![end if]-->
        <link rel="shortcut icon" type="image/jpg" href="{{URL::asset('images/favicon.jpg')}}"/>
        <link rel="stylesheet" href="{{asset('lib/css/bootstrap.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{elixir('css/style.css')}}">
        <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="{{asset('lib/js/jquery-1.11.3.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('lib/js/bootstrap.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('lib/js/moment.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('lib/js/bootstrap-datetimepicker.min.js')}}"></script>
        <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
		<script type="text/javascript" src="{{elixir('js/session_timeout.js')}}"></script>
        <script type="text/javascript" src="{{elixir('js/main.js')}}"></script>

        @yield('imports')
    </head>
    <body>
        <div class="container">
            @section('header')
            @include('layouts.header')
            @show
            <div class="row height content @if(!Auth::check()) @endif">
                <div class="hidden-xs col-sm-3 content-left @if(!Auth::check()) {{'ocuhub_logo_grey'}} @endif no_print">
                    @yield('sidebar')
                </div>
                <div class="col-xs-12 col-sm-9 content-right @if(!Auth::check()) {{'ocuhub_logo_blue'}} @endif print_col_width">
                    <span class="main_content">
                        @yield('content')
                    </span>
                    <span class="mobile_sidebar_content no_print">
                        @yield('mobile_sidebar_content')
                    </span>
                    @include('announcements')
                </div>
                @include('layouts.alert')
                @include('layouts.timeout')
                @include('layouts.confirm')
            </div>
            <div class="row height footer no_print hidden-xs">
                @section('footer')
                @include('layouts.footer')
                @show
            </div>
        </div>
    </body>
</html>
