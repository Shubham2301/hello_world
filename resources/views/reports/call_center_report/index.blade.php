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
        <div class="row filter_row">
        </div>
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
        <div class="row graph_row">
            <div class="col-xs-1 overview_graph_control">
                <ul class="overview_controls">
                    <li class="arial_bold" id="all">All</li>
                    <li class="phone" id="phone">Phone</li>
                    <li class="email" id="email">Email</li>
                    <li class="sms" id="sms">SMS</li>
                </ul>
            </div>
            <div class="col-xs-11">
                <span class="historical_section">
                    <div class="row no_print" style="margin:0;">
                        <ul class="nav nav-pills">
                            <li class="chart_tab overview active"><a href="#">Overview</a></li>
                            <li class="chart_tab conversion"><a href="#">Comparison</a></li>
                        </ul>
                        <div class="col-xs-12 referral_graph">
                            <div  id="chart_div" class="chart referred_by"></div>
                        </div>
                    </div>
                </span>
            </div>
        </div>
        <span class="user_data">
            <div class="row arial_bold user_heading">
                <div class="col-xs-4">Name</div>
                <div class="col-xs-2 align-center">Phone</div>
                <div class="col-xs-2 align-center">Email</div>
                <div class="col-xs-2 align-center">SMS</div>
                <div class="col-xs-2 align-center">Total</div>
            </div>
            <div class="row">
                <ul class="user_listing"></ul>
            </div>
        </span>
</div>

@endsection
@section('mobile_sidebar_content')
    @include('reports.sidebar')
@endsection
