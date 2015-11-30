<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    <title>@yield('title')</title>
    <!--[if lt IE 9]!>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
            <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
		<![end if]-->
    <link rel="stylesheet" href="{{asset('css/lib/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    
    <script type="text/javascript" src="{{asset('js/lib/jquery-1.11.3.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/lib/bootstrap.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/main.js')}}"></script>
    
</head>

<body>
    <div class="container">
        @section('header')
            @include('layouts.header')
        @show
        <div class="row height">
            <div class="hidden-xs col-sm-3 content-left">
                @yield('sidebar')
            </div>
            <div class="col-xs-12 col-sm-9 content-right">
                @yield('content')
            </div>
        </div>
        <div class="row height">
            @section('footer')
                @include('layouts.footer')
            @show
        </div>
    </div>
</body>

</html>