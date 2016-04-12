@extends('layouts.master')

@section('title', 'My Ocuhub - Reports')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/reports.css')}}">
<script type="text/javascript" src="{{elixir('js/reports.js')}}"></script>
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
        </strong>
        {{ Session::pull('success') }}
    </div>
    @endif
<span class="report_header">
    <span class="arial_bold title">Reports</span>
    <span class="input_field">Start Date<input type="text" id="start_date"></span>
    <span class="input_field">End Date<input type="text" id="end_date"></span>
</span>
@endsection
