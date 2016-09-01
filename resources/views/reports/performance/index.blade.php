@extends('layouts.master')
@section('title', 'Performance Report')
@section('imports')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
        <div class="row arial_bold text-left bill_graph_row">
            <span class="graph_row">
                <span class="graph_column">
                    <span class="overall_patient_text">% of patients <span class="green_text">scheduled</span> vs <span class="red_text">not scheduled</span></span>
                    <span class="graph_section" id="overall_patient"></span>
                </span>
            </span>
            <span class="graph_row">
                <span class="graph_column">
                    <span>Average Contact per day per user</span>
                    <span class="graph_section" id="avgContact"></span>
                </span>
                <span class="graph_column">
                    <span>Average patients reached per day per user</span>
                    <span class="graph_section" id="avgReached"></span>
                </span>
                <span class="graph_column">
                    <span>Average scheduled appointments per day per user</span>
                    <span class="graph_section" id="avgScheduled"></span>
                </span>
                <span class="graph_column">
                    <span>% of patients <span class="green_text">scheduled</span> vs <span class="red_text">dropped</span></span>
                    <span class="graph_section" id="scheduled_vs_dropped"></span>
                </span>
                <span class="graph_column">
                    <span>% of patients <span class="green_text">kept appointment</span> vs <span class="red_text">missed</span></span>
                    <span class="graph_section" id="keptAppointment_vs_missed"></span>
                </span>
                <span class="graph_column">
                    <span>% of patients <span class="green_text">received reports</span> vs <span class="red_text">report pending</span></span>
                    <span class="graph_section" id="receivedReport_vs_pending"></span>
                </span>
            </span>
        </div>
        <div class="row no_data_received arial_bold" style="display:none;">
            <h3>No data received! Please try other option</h3>
        </div>
    </div>
@endsection
@section('mobile_sidebar_content')
    @include('reports.sidebar')
@endsection
