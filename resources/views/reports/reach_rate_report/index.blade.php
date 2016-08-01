@extends('layouts.master') @section('title', 'My Ocuhub - Reach Rate Report') @section('imports')
<link rel="stylesheet" type="text/css" href="{{ elixir('css/reports.css') }}">
<link rel="stylesheet" type="text/css" href="{{ elixir('css/reach_rate_report.css') }}">
<script type="text/javascript" src="{{ elixir('js/reach_rate_report.js') }}"></script>
@endsection @section('sidebar') @endsection @section('content') @if (Session::has('success'))
<div class="alert alert-success" id="flash-message">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>
            <i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
        </strong> {{ Session::pull('success') }}
</div>
@endif
<div class="reports_container arial arial">
    <div class="row report_filter_row">
        <span class="filter no_filter"><span class="filter_name"></span><span class="glyphicon glyphicon-remove-circle    remove_filter"></span></span>
    </div>
    <div class="row report_header_row">
        <span class="arial_bold reports_header">Careconsole Reach Rate Report</span>
        <span class="timeline">
            <span>Timeline:</span>
            <span>
                <input type="text" class="date_selector" id="start_date">
                <input type="text" class="date_selector" id="end_date">
            </span>
        </span>
    </div>
    <div class="report_summary row">
        <div class="col-sm-6 col-md-3 flex_col">
            <span data-toggle="tooltip" title="Total number of patients that were present in the careconsole during this timeline. (Repeat patients are counted as a different patient)" data-placement="top">
                <span class="arial_bold flex_row summary_header" >Total Patients</span>
                <span class="flex_row summary_header category_count patient_count" id="patient_count"></span>
            </span>
            <span class="section_separater"></span>
            <span class="flex_row">
                <span class="flex_col drilldown_item" data-toggle="tooltip" title="Number of patients added to the system during this time timeline" data-placement="bottom">
                    <span class="arial_bold">New</span>
                    <span class="category_count new_patient" id="new_patient"></span>
                </span>
                <span class="flex_col drilldown_item" data-toggle="tooltip" title="Number of patients already present in the system before this timeline" data-placement="bottom">
                    <span class="arial_bold">Existing</span>
                    <span class="category_count existing_patients" id="existing_patients"></span>
                </span>
            </span>
        </div>
        <div class="col-sm-6 col-md-3 flex_col">
            <span class="drilldown_item" data-toggle="tooltip" title="Number of patients that moved out of console as successful or dropout" data-placement="top">
                <span class="arial_bold flex_row summary_header">Completed</span>
                <span class="flex_row summary_header category_count completed" id="completed"></span>
            </span>
            <span class="section_separater"></span>
            <span class="flex_row">
                <span class="flex_col drilldown_item" data-toggle="tooltip" title="Number of patients marked successful in any stage of careconsole during this timeline" data-placement="bottom">
                    <span class="arial_bold">Success</span>
                    <span class="category_count success" id="success"></span>
                </span>
                <span class="flex_col drilldown_item" data-toggle="tooltip" title="Number of patients marked successful in any stage of careconsole or marked as 'already seen by outside dr', 'patient declined services', 'other reasons for declining', 'no need to schedule' or as 'no insurance' during this time timeline" data-placement="bottom">
                    <span class="arial_bold">Dropouts</span>
                    <span class="category_count dropout" id="dropout"></span>
                </span>
            </span>
        </div>
        <div class="col-sm-6 col-md-3 flex_col">
            <span class="drilldown_item" data-toggle="tooltip" title="Number of patients that did not get archived at the end of this timeline" data-placement="top">
                <span class="arial_bold flex_row summary_header">Active Patients</span>
                <span class="flex_row category_count summary_header active_patient" id="active_patient"></span>
            </span>
        </div>
        <div class="col-sm-6 col-md-3 flex_col white_bg">
            <span class="arial_bold flex_row">Pending Contact</span>
            <span class="flex_row category_count pending_patient" id="pending_patient"></span>
            <span class="section_separater"></span>
            <span class="arial_bold flex_row">Repeat Patients</span>
            <span class="flex_row category_count repeat_count" id="repeat_count"></span>
        </div>
    </div>
    <div class="report_stage_data">
        <span class="stage_header stage_row">
            <span class="left arial_bold">
                <span class="heading">
                    Contact Attempted
                </span>
                <span class="count category_count contact_attempted" id="contact_attempted">
                </span>
            </span>
            <span class="right arial_italic">
                <span class="days_in_stage">
                    Avg. Time: <span class="contact_attempted_days"></span> days
                </span>
            </span>
        </span>
        <span class="stage_content stage_row">
            <span class="left">
                <span class="title">
                    <img src="{{ elixir('images/sidebar/green-dot.png') }}"> Reached
                </span>
                <span class="count category_count reached" id="reached">
                </span>
            </span>
            <span class="right">
                <span class="right_row">
                    <span class="right_row_section">
                    </span>
                    <span class="right_row_section right_section_count">
                        #patients
                    </span>
                    <span class="right_row_section right_section_count">
                        #contact
                    </span>
                </span>
                <span class="right_row section_break">
                    <span class="right_row_section">
                        <img src="{{ elixir('images/sidebar/red-dot.png') }}"> Not Reached
                    </span>
                    <span class="right_row_section not_reached right_section_count category_count" id="not_reached">
                    </span>
                    <span class="right_row_section not_reached_attempts right_section_count category_count" id="not_reached_attempts">
                    </span>
                </span>
                <span class="right_row">
                    <span class="right_row_section">
                        Unable to reach
                    </span>
                    <span class="right_row_section unable_to_reach right_section_count category_count" id="">
                    </span>
                    <span class="right_row_section unable_to_reach_attempts right_section_count category_count" id="">
                    </span>
                </span>
                <span class="right_row">
                    <span class="right_row_section">
                        Hold for future
                    </span>
                    <span class="right_row_section hold_for_future right_section_count category_count" id="">
                    </span>
                    <span class="right_row_section hold_for_future_attempts right_section_count category_count" id="">
                    </span>
                </span>
            </span>
        </span>
    </div>
    <div class="report_stage_data">
        <span class="stage_header stage_row">
            <span class="left arial_bold">
                <span class="heading">
                    Reached
                </span>
                <span class="count category_count reached" id="reached">
                </span>
            </span>
            <span class="right arial_italic">
                <span class="days_in_stage">
                    Avg. Time: <span class="reached_days"></span> days
                </span>
            </span>
        </span>
        <span class="stage_content stage_row">
            <span class="left">
                <span class="title">
                    <img src="{{ elixir('images/sidebar/green-dot.png') }}"> Appointment Scheduled
                </span>
                <span class="count category_count appointment_scheduled" id="appointment_scheduled">
                </span>
            </span>
            <span class="right">
                <span class="right_row">
                    <span class="right_row_section">
                        <img src="{{ elixir('images/sidebar/red-dot.png') }}"> Not Scheduled
                    </span>
                    <span class="right_row_section not_scheduled right_section_count category_count"  id="not_scheduled">
                    </span>
                    <span class="right_row_section">
                    </span>
                </span>
            </span>
        </span>
    </div>
    <div class="report_stage_data">
        <span class="stage_header stage_row">
            <span class="left arial_bold">
                <span class="heading">
                    Appointment Completed
                </span>
                <span class="count category_count appointment_completed" id="appointment_completed">
                </span>
            </span>
            <span class="right arial_italic">
                <span class="days_in_stage">
                    Avg. Time: <span class="appointment_completed_days"></span> days
                </span>
            </span>
        </span>
        <span class="stage_content stage_row">
            <span class="left">
                <span class="title">
                    <img src="{{ elixir('images/sidebar/green-dot.png') }}"> Show
                </span>
                <span class="count category_count show" id="show">
                </span>
            </span>
            <span class="right">
                <span class="right_row">
                    <span class="right_row_section">
                        <img src="{{ elixir('images/sidebar/red-dot.png') }}"> No Show
                    </span>
                    <span class="right_row_section no_show right_section_count category_count"  id="no_show">
                    </span>
                    <span class="right_row_section">
                    </span>
                </span>
            </span>
        </span>
    </div>
    <div class="report_stage_data">
        <span class="stage_header stage_row">
            <span class="left arial_bold">
                <span class="heading">
                    Show
                </span>
                <span class="count category_count show" id="show">
                </span>
            </span>
            <span class="right arial_italic">
                <span class="days_in_stage">
                    Avg. Time: <span class="show_days"></span> days
                </span>
            </span>
        </span>
        <span class="stage_content stage_row">
            <span class="left">
                <span class="title">
                    <img src="{{ elixir('images/sidebar/green-dot.png') }}"> Reports
                </span>
                <span class="count category_count reports" id="reports">
                </span>
            </span>
            <span class="right">
                <span class="right_row">
                    <span class="right_row_section">
                        <img src="{{ elixir('images/sidebar/red-dot.png') }}"> No Reports
                    </span>
                    <span class="right_row_section no_reports right_section_count category_count"  id="no_reports">
                    </span>
                    <span class="right_row_section">
                    </span>
                </span>
            </span>
        </span>
    </div>
</div>

@endsection
@section('mobile_sidebar_content')
@include('reports.sidebar')
@endsection
