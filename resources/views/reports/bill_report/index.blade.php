@extends('layouts.master')
@section('title', 'Billing Report')
@section('imports')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/reports.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/bill_report.css') }}">
    <script type="text/javascript" src="{{ elixir('js/bill_report.js') }}"></script>
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
            <span class="arial_bold reports_header">Care Console Billing Report</span>
            <span class="timeline">
                <span>Timeline:</span>
                <span>
                    <input type="text" class="date_selector" id="start_date">
                    <input type="text" class="date_selector" id="end_date">
                </span>
            </span>
        </div>
        <div class="row arial_bold text-left">
            <span class="graph_row">
                <span class="graph_column">
                    <span class="overall_patient_text">% of patients <span class="green_text">scheduled</span> vs <span class="red_text">not scheduled</span></span>
                    <span class="graph_section" id="overall_patient"></span>
                </span>
            </span>
            <span class="graph_row">
                <span class="graph_column">
                    <span>Average Contact per day per user</span>
                    <span class="graph_section" id="overall_patient2"></span>
                </span>
                <span class="graph_column">
                    <span>Average patients reached per day per user</span>
                    <span class="graph_section" id="overall_patient3"></span>
                </span>
                <span class="graph_column">
                    <span>Average scheduled appointments per day per user</span>
                    <span class="graph_section" id="overall_patient4"></span>
                </span>
                <span class="graph_column">
                    <span>% of patients <span class="green_text">scheduled</span> vs <span class="red_text">dropped</span></span>
                    <span class="graph_section" id="overall_patient5"></span>
                </span>
                <span class="graph_column">
                    <span>% of patients <span class="green_text">kept appointment</span> vs <span class="red_text">missed</span></span>
                    <span class="graph_section" id="overall_patient6"></span>
                </span>
                <span class="graph_column">
                    <span>% of patients <span class="green_text">received reports</span> vs <span class="red_text">report pending</span></span>
                    <span class="graph_section" id="overall_patient7"></span>
                </span>
            </span>
        </div>
    </div>
@endsection
@section('mobile_sidebar_content')
    @include('reports.sidebar')
@endsection
