@extends('layouts.master')
@section('title', 'Performance Report')
@section('imports')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/reports.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/performance_report.css') }}">
    <script type="text/javascript" src="{{ elixir('js/performance_report.js') }}"></script>
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
            <span class="arial_bold reports_header">Care Console Performance Report</span>
            <span class="timeline">
                <span>Timeline:</span>
                <span>
                    <input type="text" class="date_selector" id="start_date">
                    <input type="text" class="date_selector" id="end_date">
                </span>
            </span>
            <span>
                <span>Network:</span>
                <select class="network_selector" id="network">
                    @foreach($networkData as $networkID => $network)
                        <option value="{{ $networkID }}">{{ $network }}</option>
                    @endforeach
                </select>
            </span>
        </div>
        <div class="row arial_bold drilldown_section" style="display:none;">
            <div class="filter_graph row" id=""></div>
            <div class="row drilldown_data"></div>
        </div>
        <div class="row arial_bold text-left performance_graph_row">
            <span class="graph_row">
                <span class="graph_column" data-toggle="tooltip" title="Total patient for the network that are not in contact status stage vs the patient that are in contact status stage of careconsole" data-placement="bottom">
                    <span class="overall_patient_text">Number of patients <span class="green_text">scheduled(<span class="completed_patient"></span>)</span> vs <span class="red_text">not scheduled(<span class="pending_patient"></span>)</span></span>
                    <span class="graph_section" id="overall_patient"></span>
                </span>
            </span>
            <span class="graph_row">
                <span class="graph_column clickable" data-title="Contacts per day" data-toggle="tooltip" title="Contacts made per day during the timeline" data-placement="top">
                    <span class="graph_header">Contacts per day</span>
                    <span class="graph_section no_filter" id="avgContact"></span>
                </span>
                <span class="graph_column clickable" data-title="Patients reached per day" data-toggle="tooltip" title="Total patients reached per day during the timeline" data-placement="top">
                    <span>Patients reached per day</span>
                    <span class="graph_section no_filter" id="avgReached"></span>
                </span>
                <span class="graph_column clickable" data-title="Scheduled appointments per day" data-toggle="tooltip" title="Total patients scheduled per day during the timeline" data-placement="top">
                    <span>Scheduled appointments per day</span>
                    <span class="graph_section no_filter" id="avgScheduled"></span>
                </span>
                <span class="graph_column clickable" data-title="Number of patients scheduled vs dropped" data-toggle="tooltip" title="Total patients scheduled vs total patient dropped per day during the timeline" data-placement="top">
                    <span>Number of patients <span class="green_text">scheduled</span> vs <span class="red_text">dropped</span></span>
                    <span class="graph_section no_filter" id="scheduled_vs_dropped"></span>
                </span>
                <span class="graph_column clickable" data-title="Number of patients kept appointment vs missed" data-toggle="tooltip" title="Total patients marked as kept appointment vs total patient marked missed/cancelled appointment per day during the timeline (this graph is based on the appointment date)" data-placement="top">
                    <span>Number of patients <span class="green_text">kept appointment</span> vs <span class="red_text">missed</span></span>
                    <span class="graph_section no_filter" id="keptAppointment_vs_missed"></span>
                </span>
                <span class="graph_column clickable" data-title="Number of patients received reports vs report pending" data-toggle="tooltip" title="Total patients who received report vs total patient who completed appointment and were waiting for report per day during the timeline" data-placement="top">
                    <span>Number of patients <span class="green_text">received reports</span> vs <span class="red_text">report pending</span></span>
                    <span class="graph_section no_filter" id="receivedReport_vs_pending"></span>
                </span>
            </span>
        </div>
        <div class="row no_data_received arial_bold" style="display:none;">
            <h4>No data received! Please try other option</h4>
        </div>
    </div>
@endsection
@section('mobile_sidebar_content')
    @include('reports.sidebar')
@endsection
