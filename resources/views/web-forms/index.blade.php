@extends('layouts.master')

@section('title', 'My Ocuhub - Web Forms')

@section('imports')

<link rel="stylesheet" type="text/css" href="{{elixir('css/users.css')}}">

<link rel="stylesheet" type="text/css" href="{{elixir('css/web_forms.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('lib/signature/jquery.signaturepad.css')}}">

<script type="text/javascript" src="{{elixir('js/web_forms.js')}}"></script>

<script type="text/javascript" src="{{asset('lib/signature/flashcanvas.js')}}"></script>

<script src="{{asset('lib/signature/jquery.signaturepad.js')}}"></script>

    @section('sidebar')
        @include('patient-records.sidebar')
    @endsection
@endsection

@section('content')

@if (Session::has('success'))
<div class="alert alert-success" id="flash-message">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>
		<i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
	</strong> {{ Session::pull('success') }}
</div>
@endif
<div>
    @include('web-forms.header')
</div>

<div class="content-section active">

    <div class="search_section section active" id="search_listing">

    </div>

    <div class="form_section section" id="form_view">

    </div>

</div>

@endsection
