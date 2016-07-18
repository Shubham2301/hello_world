@extends('layouts.master') @section('title', 'My Ocuhub - Reach Rate Report') @section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/reports.css')}}">
<link rel="stylesheet" type="text/css" href="{{elixir('css/reach_rate_report.css')}}">
<script type="text/javascript" src="{{elixir('js/reach_rate_report.js')}}"></script>
@endsection @section('sidebar')  @endsection @section('content') @if (Session::has('success'))
<div class="alert alert-success" id="flash-message">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>
            <i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
        </strong> {{ Session::pull('success') }}
</div>
@endif
<div class="reports_container arial">
    <div class="row">
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
        <div class="col-xs-6 col-sm-3 flex_col">
            <span class="arial_bold flex_row summary_header">Total Patients</span>
            <span class="flex_row summary_header category_count">5000</span>
            <span class="section_separater"></span>
            <span class="flex_row">
                <span class="flex_col">
                    <span class="arial_bold">Imported</span>
                    <span class="category_count">1000</span>
                </span>
                <span class="flex_col">
                    <span class="arial_bold">Existing</span>
                    <span class="category_count">4000</span>
                </span>
            </span>
        </div>
        <div class="col-xs-6 col-sm-3 flex_col">
            <span class="arial_bold flex_row summary_header">Completed</span>
            <span class="flex_row summary_header category_count">2500</span>
            <span class="section_separater"></span>
            <span class="flex_row">
                <span class="flex_col">
                    <span class="arial_bold">Success</span>
                    <span class="category_count">2000</span>
                </span>
                <span class="flex_col">
                    <span class="arial_bold">Dropouts</span>
                    <span class="category_count">500</span>
                </span>
            </span>
        </div>
        <div class="col-xs-6 col-sm-3 flex_col">
            <span class="arial_bold flex_row summary_header">Active Patients</span>
            <span class="flex_row category_count summary_header">1500</span>
        </div>
        <div class="col-xs-6 col-sm-3 flex_col white_bg">
            <span class="arial_bold flex_row">Pending Patients</span>
            <span class="flex_row category_count">500</span>
            <span class="section_separater"></span>
            <span class="arial_bold flex_row">Repeat Patients</span>
            <span class="flex_row category_count">130</span>
        </div>
    </div>
    <div class="report_stage_data">
        <span class="report_stage_header">
            <span class="stage_header_title">
                <span class="arial_bold">Contact Attempted</span>
                <span class="arial_bold category_count">5000</span>
            </span>
            <span>
                <span class="arial_italic">Average time spent in this stage</span>
                <span class="arial_italic category_count">1 day</span>
            </span>
        </span>
        <span class="report_stage_content row">
            <span class="col-xs-6 stage_content_subsection passed_stage">
                <div class="row">
                    <div class="col-xs-3"><img src="{{elixir('images/sidebar/green-dot.png')}}"> Reached</div>
                    <div class="col-xs-4 category_count">4500</div>
                </div>
            </span>
            <span class="col-xs-6 stage_content_subsection still_in_stage">
                <div class="row">
                    <div class="col-xs-offset-4 col-xs-4 "></div>
                    <div class="col-xs-2 arial_italic">#patients</div>
                    <div class="col-xs-2 arial_italic">#contacts</div>
                </div>
                <div class="row">
                    <div class="col-xs-offset-4 col-xs-4 "><img src="{{elixir('images/sidebar/red-dot.png')}}"> Not Reached</div>
                    <div class="col-xs-2 category_count">500</div>
                    <div class="col-xs-2 category_count">1000</div>
                </div>
                <div class="row">
                    <div class="col-xs-offset-4 col-xs-8 ">
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-offset-4 col-xs-4 ">Answering Machine</div>
                    <div class="col-xs-2 category_count">25</div>
                    <div class="col-xs-2 category_count">50</div>
                </div>
                <div class="row">
                    <div class="col-xs-offset-4 col-xs-4 ">No Response</div>
                    <div class="col-xs-2 category_count">300</div>
                    <div class="col-xs-2 category_count">600</div>
                </div>
                <div class="row">
                    <div class="col-xs-offset-4 col-xs-4 ">Others</div>
                    <div class="col-xs-2 category_count">175</div>
                    <div class="col-xs-2 category_count">350</div>
                </div>
            </span>
        </span>
    </div>
    <div class="report_stage_data">
        <span class="report_stage_header">
            <span class="stage_header_title">
                <span class="arial_bold">Reached</span>
                <span class="arial_bold category_count">4500</span>
            </span>
            <span>
                <span class="arial_italic">Average time spent in this stage</span>
                <span class="arial_italic category_count">3 days</span>
            </span>
        </span>
        <span class="report_stage_content row">
            <span class="col-xs-6 stage_content_subsection passed_stage">
                <div class="row">
                    <div class="col-xs-5"><img src="{{elixir('images/sidebar/green-dot.png')}}"> Appointment Scheduled</div>
                    <div class="col-xs-4 category_count">4000</div>
                </div>
            </span>
            <span class="col-xs-6 stage_content_subsection still_in_stage">
                <div class="row">
                    <div class="col-xs-offset-4 col-xs-6 "><img src="{{elixir('images/sidebar/red-dot.png')}}"> Not scheduled</div>
                    <div class="col-xs-2 category_count">500</div>
                </div>
                <div class="row">
                   <div class="col-xs-offset-4 col-xs-8">
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-offset-4 col-xs-6 ">Appointment Not Needed</div>
                    <div class="col-xs-2 category_count">200</div>
                </div>
                <div class="row">
                    <div class="col-xs-offset-4 col-xs-6 ">Patient Declined Service</div>
                    <div class="col-xs-2 category_count">100</div>
                </div>
                <div class="row">
                    <div class="col-xs-offset-4 col-xs-6 ">Already seen by outside doctor</div>
                    <div class="col-xs-2 category_count">200</div>
                </div>
            </span>
        </span>
    </div>
    <div class="report_stage_data">
        <span class="report_stage_header">
            <span class="stage_header_title">
                <span class="arial_bold">Appointment Completed</span>
                <span class="arial_bold category_count">4000</span>
            </span>
            <span>
                <span class="arial_italic">Average time spent in this stage</span>
                <span class="arial_italic category_count">2 days</span>
            </span>
        </span>
        <span class="report_stage_content row">
            <span class="col-xs-6 stage_content_subsection passed_stage">
                <div class="row">
                    <div class="col-xs-2"><img src="{{elixir('images/sidebar/green-dot.png')}}"> Show</div>
                    <div class="col-xs-4 category_count">3500</div>
                </div>
            </span>
            <span class="col-xs-6 stage_content_subsection still_in_stage">
                <div class="row">
                    <div class="col-xs-offset-6 col-xs-4 "><img src="{{elixir('images/sidebar/red-dot.png')}}"> No Show</div>
                    <div class="col-xs-2 category_count">500</div>
                </div>
            </span>
        </span>
    </div>
    <div class="report_stage_data">
        <span class="report_stage_header">
            <span class="stage_header_title">
                <span class="arial_bold">Show</span>
                <span class="arial_bold category_count">3500</span>
            </span>
            <span>
                <span class="arial_italic">Average time spent in this stage</span>
                <span class="arial_italic category_count">5 days</span>
            </span>
        </span>
    </div>
</div>

@endsection
@section('mobile_sidebar_content')
@include('reports.sidebar')
@endsection
