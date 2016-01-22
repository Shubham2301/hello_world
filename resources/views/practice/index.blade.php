@extends('layouts.master')

@section('title', 'My Ocuhub - Select Provider')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/practice.css')}}">
<script type="text/javascript" src="{{elixir('js/practice.js')}}"></script>
@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')

@if (Session::has('success'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>
            <i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
        </strong> {{ Session::get('success') }}
</div>
@endif

<div class="content-section active">
    @include('practice.search')
</div>

<div class="practice_section active" id="practice_listing">
    @include('practice.listing')
</div>
<div class="practice_section active">
    @include('practice.create')
</div>
@endsection
