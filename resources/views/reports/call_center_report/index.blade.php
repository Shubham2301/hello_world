@extends('layouts.master')
@section('title', 'Call Center Report')
@section('imports')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/reports.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/call_center.css') }}">
    <script type="text/javascript" src="{{ elixir('js/call_center.js') }}"></script>
@endsection
@section('sidebar')
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
    <div class="reports_container arial arial">
        <div class="row report_header_row">
            <span class="arial_bold reports_header">Care Console Call Center Report</span>
            <span class="timeline">
                <span>Timeline:</span>
                <span>
                    <input type="text" class="date_selector" id="start_date">
                    <input type="text" class="date_selector" id="end_date">
                </span>
            </span>
        </div>
        <div id="chart_div" style="margin:2em 0;"></div>
        <span class="user_data">
            <div class="row arial_bold user_heading">
                <div class="col-xs-4">Name</div>
                <div class="col-xs-2 align-center">Phone</div>
                <div class="col-xs-2 align-center">Email</div>
                <div class="col-xs-2 align-center">Mail</div>
                <div class="col-xs-2 align-center">Other</div>
            </div>
            <div class="row user_listing">
            </div>
        </span>
</div>

@endsection
@section('mobile_sidebar_content')
    @include('reports.sidebar')
@endsection
