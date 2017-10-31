@extends('layouts.master')
@section('title', 'Call Center Report')
@section('imports')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/reports.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/call_center.css') }}">
    <script type="text/javascript" src="{{ elixir('js/call_center.js') }}"></script>
@endsection
@section('sidebar')
@include('reports.sidebar')
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
            @if(Auth::user()->isSuperAdmin())
            <span class="report_option_row">
                <span>Network:</span>
                <select class="network_selector" id="network_id">
                    @foreach($networkData as $networkID => $network)
                        <option value="{{ $networkID }}">{{ $network }}</option>
                    @endforeach
                </select>
            </span>
            @else
                <input type="hidden" id="network_id" value="{{ Auth::user()->network->network_id }}">
            @endif
        </div>
        <div class="row graph_row">
            <div class="col-xs-12">
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
        <div class="user_data">
            <h4 class="row arial_bold">User Contact Attempt Data</h4>
            <div class="row arial_bold user_heading">
                <div class="col-xs-6">Name</div>
                <div class="col-xs-2 align-center">Contact Attempts<br><span class="total_count" id="contact_attempts"></span></div>
                <div class="col-xs-2 align-center">Appointments <i>(Outgoing)</i><br><span class="total_count" id="appointment_scheduled_outgoing"></span></div>
                <div class="col-xs-2 align-center">Appointments <i>(Incoming)</i><br><span class="total_count" id="appointment_scheduled_incoming"></span></div>
            </div>
            <div class="row">
                <ul class="user_listing"></ul>
            </div>
        </div>
</div>

@endsection
@section('mobile_sidebar_content')
    @include('reports.sidebar')
@endsection
