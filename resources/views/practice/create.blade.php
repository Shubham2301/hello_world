@extends('layouts.master')

@section('title', 'illuma - Practices')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/practice.css')}}">
<script type="text/javascript" src="{{elixir('js/practice.js')}}"></script>
@endsection

@section('sidebar')
@if(Auth::check())
@include('admin.sidebar')
@endif
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

<div class="practice_section active">
    @include('practice.create_form')
</div>
@endsection
