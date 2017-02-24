@extends('layouts.master')

@section('title', 'illuma - Select Practice')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/practice.css')}}">
<script type="text/javascript" src="{{elixir('js/practice.js')}}"></script>
@endsection

@section('sidebar')
    @include('admin.sidebar')
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

@if (Session::has('warning'))
<div class="alert alert-warning" id="flash-message">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>
            <i class="fa fa-check-circle fa-lg fa-fw"></i> Warning. &nbsp;
        </strong> {{ Session::pull('warning') }}
</div>
@endif

<div class="content-section active side_padding">
    @include('practice.search')
</div>

<div class="practice_section active auto_scroll side_padding" id="practice_listing">
    @include('practice.listing')
</div>

@endsection
@section('mobile_sidebar_content')
@include('admin.sidebar')
@endsection
