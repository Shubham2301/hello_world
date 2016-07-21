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
        <div class="col-sm-6 col-md-3 flex_col">
            <span class="arial_bold flex_row summary_header">Total Patients</span>
            <span class="flex_row summary_header category_count patient_count" id="patient_count"></span>
            <span class="section_separater"></span>
            <span class="flex_row hide">
                <span class="flex_col">
                    <span class="arial_bold">Imported</span>
                    <span class="category_count imported_patient" id="imported_patient"></span>
                </span>
                <span class="flex_col">
                    <span class="arial_bold">Existing</span>
                    <span class="category_count existing_patients" id="existing_patients"></span>
                </span>
            </span>
        </div>
        <div class="col-sm-6 col-md-3 flex_col">
            <span class="arial_bold flex_row summary_header">Completed</span>
            <span class="flex_row summary_header category_count completed" id="completed"></span>
            <span class="section_separater"></span>
            <span class="flex_row">
                <span class="flex_col">
                    <span class="arial_bold">Success</span>
                    <span class="category_count success" id="success"></span>
                </span>
                <span class="flex_col">
                    <span class="arial_bold">Dropouts</span>
                    <span class="category_count dropout" id="dropout"></span>
                </span>
            </span>
        </div>
        <div class="col-sm-6 col-md-3 flex_col">
            <span class="arial_bold flex_row summary_header">Active Patients</span>
            <span class="flex_row category_count summary_header active_patient" id="active_patient"></span>
        </div>
        <div class="col-sm-6 col-md-3 flex_col white_bg">
            <span class="arial_bold flex_row">Pending Patients</span>
            <span class="flex_row category_count pending_patient" id="pending_patient"></span>
            <span class="section_separater"></span>
            <span class="arial_bold flex_row">Repeat Patients</span>
            <span class="flex_row category_count repeat_count" id="repeat_count"></span>
        </div>
    </div>
    <div class="report_stage_data">
        <span class="report_stage_header">
            <span class="stage_header_title">
                <span class="arial_bold">Contact Attempted</span>
                <span class="arial_bold category_count contact_attempted" id="contact_attempted"></span>
            </span>
            <span>
                <span class="arial_italic">Average days spent in this stage</span>
                <span class="arial_italic category_count contact-status"></span>
            </span>
        </span>
        <span class="report_stage_content">
            <span class=" stage_content_subsection passed_stage">
                <div class="row">
                    <div class="col-xs-8 col-sm-6 col-md-5 col-lg-4"><img src="{{elixir('images/sidebar/green-dot.png')}}"> Reached</div>
                    <div class="col-xs-4 category_count reached" id="reached"></div>
                </div>
            </span>
            <span class=" stage_content_subsection still_in_stage">
                <div>
                    <div class="col-md-offset-4 col-sm-offset-2 col-sm-4 col-md-4 col-xs-6"></div>
                    <div class="col-sm-3 col-md-2 col-xs-3 arial_italic">#patients</div>
<!--                    <div class="col-sm-3 col-md-2 col-xs-3 arial_italic">#contacts</div>-->
                </div>
                <div>
                    <div class="col-md-offset-4 col-sm-offset-2 col-sm-4 col-md-4 col-xs-6"><img src="{{elixir('images/sidebar/red-dot.png')}}"> Not Reached</div>
                    <div class="col-sm-3 col-md-2 col-xs-3 category_count not_reached" id="not_reached"></div>
<!--                    <div class="col-sm-3 col-md-2 col-xs-3 category_count">1000</div>-->
                </div>
<!--
                <div>
                    <div class="col-md-offset-4 col-sm-offset-2 col-sm-10 col-md-8 col-xs-12">
                        <hr>
                    </div>
                </div>
                <div>
                    <div class="col-md-offset-4 col-sm-offset-2 col-sm-4 col-md-4 col-xs-6">Answering Machine</div>
                    <div class="col-sm-3 col-md-2 col-xs-3 category_count">25</div>
                    <div class="col-sm-3 col-md-2 col-xs-3 category_count">50</div>
                </div>
                <div>
                    <div class="col-md-offset-4 col-sm-offset-2 col-sm-4 col-md-4 col-xs-6">No Response</div>
                    <div class="col-sm-3 col-md-2 col-xs-3 category_count">300</div>
                    <div class="col-sm-3 col-md-2 col-xs-3 category_count">600</div>
                </div>
                <div>
                    <div class="col-md-offset-4 col-sm-offset-2 col-sm-4 col-md-4 col-xs-6">Others</div>
                    <div class="col-sm-3 col-md-2 col-xs-3 category_count">175</div>
                    <div class="col-sm-3 col-md-2 col-xs-3 category_count">350</div>
                </div>
-->
            </span>
        </span>
    </div>
    <div class="report_stage_data">
        <span class="report_stage_header">
            <span class="stage_header_title">
                <span class="arial_bold">Reached</span>
                <span class="arial_bold category_count reached" id="reached"></span>
            </span>
            <span>
                <span class="arial_italic">Average days spent in this stage</span>
                <span class="arial_italic category_count scheduled-for-appointment"></span>
            </span>
        </span>
        <span class="report_stage_content">
            <span class=" stage_content_subsection passed_stage">
                <div class="row">
                    <div class="col-xs-8 col-sm-7 col-lg-6"><img src="{{elixir('images/sidebar/green-dot.png')}}"> Appointment Scheduled</div>
                    <div class="col-xs-4 category_count appointment_scheduled" id="appointment_scheduled"></div>
                </div>
            </span>
            <span class=" stage_content_subsection still_in_stage">
                <div>
                    <div class="col-md-offset-6 col-sm-offset-2 col-sm-4 col-md-4 col-xs-6 text_align_end"><img src="{{elixir('images/sidebar/red-dot.png')}}"> Not Scheduled</div>
                    <div class="col-sm-3 col-md-2 col-xs-3 category_count not_scheduled text_align_end" id="not_scheduled">500</div>
                </div>
            </span>
        </span>
    </div>
    <div class="report_stage_data">
        <span class="report_stage_header">
            <span class="stage_header_title">
                <span class="arial_bold">Appointment Completed</span>
                <span class="arial_bold category_count appointment_completed" id="appointment_completed"></span>
            </span>
            <span>
                <span class="arial_italic">Average days spent in this stage</span>
                <span class="arial_italic category_count exam-report"></span>
            </span>
        </span>
        <span class="report_stage_content">
            <span class=" stage_content_subsection passed_stage">
                <div class="row">
                    <div class="col-xs-8 col-sm-7 col-lg-6"><img src="{{elixir('images/sidebar/green-dot.png')}}"> Show</div>
                    <div class="col-xs-4 category_count show" id="show"></div>
                </div>
            </span>
            <span class=" stage_content_subsection still_in_stage">
                <div>
                    <div class="col-md-offset-6 col-sm-offset-2 col-sm-4 col-md-4 col-xs-6 text_align_end"><img src="{{elixir('images/sidebar/red-dot.png')}}"> No Show</div>
                    <div class="col-sm-3 col-md-2 col-xs-3 category_count no_show text_align_end" id="no_show"></div>
                </div>
            </span>
        </span>
    </div>
    <div class="report_stage_data">
        <span class="report_stage_header">
            <span class="stage_header_title">
                <span class="arial_bold">Show</span>
                <span class="arial_bold category_count show" id="show"></span>
            </span>
            <span>
                <span class="arial_italic">Average days spent in this stage</span>
                <span class="arial_italic category_count finalization"></span>
            </span>
        </span>
        <span class="report_stage_content">
            <span class=" stage_content_subsection passed_stage">
                <div class="row">
                    <div class="col-xs-8 col-sm-7 col-lg-6"><img src="{{elixir('images/sidebar/green-dot.png')}}"> Reports</div>
                    <div class="col-xs-4 category_count reports" id="reports"></div>
                </div>
            </span>
            <span class=" stage_content_subsection still_in_stage">
                <div>
                    <div class="col-md-offset-6 col-sm-offset-2 col-sm-4 col-md-4 col-xs-6 text_align_end"><img src="{{elixir('images/sidebar/red-dot.png')}}"> No Reports</div>
                    <div class="col-sm-3 col-md-2 col-xs-3 category_count no_reports text_align_end" id="no_reports"></div>
                </div>
            </span>
        </span>
    </div>
</div>

@endsection
@section('mobile_sidebar_content')
@include('reports.sidebar')
@endsection
