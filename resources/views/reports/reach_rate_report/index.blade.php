@extends('layouts.master') @section('title', 'Reach Report') @section('imports')
<link rel="stylesheet" type="text/css" href="{{ elixir('css/reports.css') }}">
<link rel="stylesheet" type="text/css" href="{{ elixir('css/reach_rate_report.css') }}">
<script type="text/javascript" src="{{ elixir('js/reach_rate_report.js') }}"></script>
@endsection @section('sidebar') @include('reports.sidebar') @endsection @section('content') @if (Session::has('success'))
<div class="alert alert-success" id="flash-message">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>
            <i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
        </strong> {{ Session::pull('success') }}
</div>
@endif
<div class="reports_container arial arial">
    <div class="row report_header_row">
        <span class="arial_bold reports_header">Care Console Reach Report</span>
        <span class="timeline">
            <span>Timeline:</span>
            <span>
                <input type="text" class="date_selector" id="start_date">
                <input type="text" class="date_selector" id="end_date">
            </span>
        </span>
        <br>
        <span class="referred_by_dropdown">
            <span>Referred By</span>
            <select class="referred_by_practice_list">
            </select>
        </span>
    </div>
    <div class="report_summary row">
        <div class="col-sm-6 col-md-3 flex_col">
            <span data-toggle="tooltip" title="Total number of patients that were present in the careconsole during this timeline. (Repeat patients are counted as a different patient)" data-placement="top">
                <span class="arial_bold flex_row summary_header" >
                    <span class="patient_list" id="patient_count" data-name="Total Patients">
                        Total Patients
                    </span>
                </span>
                <span class="flex_row summary_header category_count patient_count patient_list" id="patient_count" data-name="Total Patients"></span>
            </span>
            <span class="section_separater"></span>
            <span class="flex_row">
                <span class="flex_col" data-toggle="tooltip" title="Number of patients added to the system during this time timeline" data-placement="bottom">
                    <span class="arial_bold">
                        <span class="patient_list" id="new_patient" data-name="New">
                            New
                        </span>
                    </span>
                    <span class="category_count new_patient patient_list" id="new_patient" data-name="New"></span>
                </span>
                <span class="flex_col" data-toggle="tooltip" title="Number of patients already present in the system before this timeline" data-placement="bottom">
                    <span class="arial_bold">
                        <span class="patient_list" id="existing_patients" data-name="Existing">
                            Existing
                        </span>
                    </span>
                    <span class="category_count existing_patients patient_list" id="existing_patients" data-name="Existing"></span>
                </span>
            </span>
        </div>
        <div class="col-sm-6 col-md-3 flex_col">
            <span class="" data-toggle="tooltip" title="Number of patients that moved out of console as successful or dropout" data-placement="top">
                <span class="arial_bold flex_row summary_header">
                    <span class="patient_list" id="completed" data-name="Completed">
                        Completed
                    </span>
                </span>
                <span class="flex_row summary_header category_count completed patient_list" id="completed" data-name="Completed"></span>
            </span>
            <span class="section_separater"></span>
            <span class="flex_row">
                <span class="flex_col" data-toggle="tooltip" title="Number of patients marked successful in any stage of careconsole during this timeline" data-placement="bottom">
                    <span class="arial_bold">
                        <span class="patient_list" id="success" data-name="Success">
                            Success
                        </span>
                    </span>
                    <span class="category_count success patient_list" id="success" data-name="Success"></span>
                </span>
                <span class="flex_col" data-toggle="tooltip" title="Number of patients marked successful in any stage of careconsole or marked as 'already seen by outside dr', 'patient declined services', 'other reasons for declining', 'no need to schedule' or as 'no insurance' during this time timeline" data-placement="bottom">
                    <span class="arial_bold">
                        <span class="patient_list" id="dropout" data-name="Dropouts">
                            Dropouts
                        </span>
                    </span>
                    <span class="category_count dropout patient_list" id="dropout" data-name="Dropouts"></span>
                </span>
            </span>
        </div>
        <div class="col-sm-6 col-md-3 flex_col">
            <span class="" data-toggle="tooltip" title="Number of patients that did not get archived at the end of this timeline" data-placement="top">
                <span class="arial_bold flex_row summary_header">
                    <span class="patient_list" id="active_patient" data-name="Active Patients">
                        Active Patients
                    </span>
                </span>
                <span class="flex_row category_count summary_header active_patient patient_list" id="active_patient" data-name="Active Patients"></span>
            </span>
        </div>
        <div class="col-sm-6 col-md-3 flex_col white_bg">
            <span class="arial_bold flex_row">
                <span class="patient_list" id="pending_patient" data-name="Pending Contact">
                    Pending Contact
                </span>
            </span>
            <span class="flex_row category_count pending_patient patient_list" id="pending_patient" data-name="Pending Contact"></span>
            <span class="section_separater"></span>
            <span class="arial_bold flex_row">Repeat Patients</span>
            <span class="flex_row category_count repeat_count"></span>
        </div>
    </div>
    <div class="report_stage_data">
        <span class="stage_header stage_row">
            <span class="left arial_bold">
                <span class="heading">
                    <span class="patient_list" id="contact_attempted" data-name="Contact Attempted">
                        Contact Attempted
                    </span>
                </span>
                <span class="count">
                    <span  class="category_count contact_attempted patient_list" id="contact_attempted" data-name="Contact Attempted"></span>
                </span>
            </span>
            <span class="right arial_italic">
                <span class="days_in_stage" data-toggle="tooltip" title="Average number of days taken make the first contact attempt with patient" data-placement="left">
                    Avg. Time: <span class="contact_attempted_days"></span> days
                </span>
            </span>
        </span>
        <span class="stage_content stage_row">
            <span class="left">
                <span class="title">
                    <span class="patient_list" id="reached" data-name="Reached">
                        <img src="{{ elixir('images/sidebar/green-dot.png') }}"> Reached
                    </span>
                </span>
                <span class="count">
                    <span class="category_count reached patient_list" id="reached" data-name="Reached"></span>
                </span>
            </span>
            <span class="right">
                <span class="right_row">
                    <span class="right_row_section">
                    </span>
                    <span class="right_row_section right_section_count">
                        #contact
                    </span>
                    <span class="right_row_section right_section_count">
                        #patients
                    </span>
                </span>
                <span class="right_row section_break">
                    <span class="right_row_section">
                        <span class="patient_list" id="not_reached" data-name="Not Reached">
                            <img src="{{ elixir('images/sidebar/red-dot.png') }}"> Not Reached
                        </span>
                    </span>
                    <span class="right_row_section not_reached_attempts right_section_count category_count">
                    </span>
                    <span class="right_row_section not_reached right_section_count category_count patient_list"  id="not_reached" data-name="Not Reached">
                    </span>
                </span>
                <span class="right_row">
                    <span class="right_row_section">
                        <span class="title">Unable to reach</span>
                    </span>
                    <span class="right_row_section unable_to_reach_attempts right_section_count category_count">
                    </span>
                    <span class="right_row_section unable_to_reach right_section_count category_count">
                    </span>
                </span>
                <span class="right_row">
                    <span class="right_row_section">
                        <span class="title">Hold for future</span>
                    </span>
                    <span class="right_row_section hold_for_future_attempts right_section_count category_count">
                    </span>
                    <span class="right_row_section hold_for_future right_section_count category_count">
                    </span>
                </span>
                <span class="right_row">
                    <span class="right_row_section">
                        <span class="title">Incorrect Data</span>
                    </span>
                    <span class="right_row_section incorrect_data_attempts right_section_count category_count">
                    </span>
                    <span class="right_row_section incorrect_data right_section_count category_count">
                    </span>
                </span>
            </span>
        </span>
    </div>
    <div class="report_stage_data">
        <span class="stage_header stage_row">
            <span class="left arial_bold">
                <span class="heading">
                    <span class="patient_list" id="reached" data-name="Reached">
                        Reached
                    </span>
                </span>
                <span class="count">
                    <span class="category_count reached patient_list" id="reached" data-name="Reached"></span>
                </span>
            </span>
            <span class="right arial_italic">
                <span class="days_in_stage" data-toggle="tooltip" title="Average number of days taken for the patient to get scheduled after the first contact attempt" data-placement="left">
                    Avg. Time: <span class="reached_days"></span> days
                </span>
            </span>
        </span>
        <span class="stage_content stage_row">
            <span class="left">
                <span class="title">
                    <span class="patient_list" id="appointment_scheduled" data-name="Appointment Scheduled">
                        <img src="{{ elixir('images/sidebar/green-dot.png') }}"> Appointment Scheduled
                    </span>
                </span>
                <span class="count">
                    <span class="category_count appointment_scheduled patient_list" id="appointment_scheduled" data-name="Appointment Scheduled">
                    </span>
                </span>
            </span>
            <span class="right">
                <span class="right_row section_break">
                    <span class="right_row_section title_section">
                        <span class="patient_list" id="not_scheduled" data-name="Not Scheduled">
                            <img src="{{ elixir('images/sidebar/red-dot.png') }}"> Not Scheduled
                        </span>
                    </span>
                    <span class="right_row_section not_scheduled right_section_count category_count patient_list" id="not_scheduled" data-name="Not Scheduled">
                    </span>
                </span>
                <span class="right_row">
                    <span class="right_row_section title_section">
                        <span class="title">No need to schedule</span>
                    </span>
                    <span class="right_row_section no_need_to_schedule right_section_count category_count number_section">
                    </span>
                </span>
                <span class="right_row">
                    <span class="right_row_section title_section">
                        <span class="title">Patient Declined Service</span>
                    </span>
                    <span class="right_row_section patient_declined_service right_section_count category_count">
                    </span>
                </span>
                <span class="right_row">
                    <span class="right_row_section title_section">
                        <span class="title">Already Seen by an outside Doctor</span>
                    </span>
                    <span class="right_row_section already_seen_by_outside_dr right_section_count category_count">
                    </span>
                </span>
                <span class="right_row">
                    <span class="right_row_section title_section">
                        <span class="title">No insurance</span>
                    </span>
                    <span class="right_row_section no_insurance right_section_count category_count">
                    </span>
                </span>
                <span class="right_row">
                    <span class="right_row_section title_section">
                        <span class="title">Other Reason for declining</span>
                    </span>
                    <span class="right_row_section other_reason_for_declining right_section_count category_count">
                    </span>
                </span>
            </span>
        </span>
    </div>
    <div class="report_stage_data">
        <span class="stage_header stage_row">
            <span class="left arial_bold">
                <span class="heading">
                    <span class="patient_list" id="appointment_completed" data-name="Appointment Completed">
                        Appointment Completed
                    </span>
                </span>
                <span class="count">
                    <span class="category_count appointment_completed patient_list" id="appointment_completed" data-name="Appointment Completed"></span>
                </span>
            </span>
            <span class="right arial_italic">
                <span class="days_in_stage" data-toggle="tooltip" title="Average number of days taken to update the appointment status as 'kept-appointment' after the completion of appointment" data-placement="left">
                    Avg. Time: <span class="appointment_completed_days"></span> days
                </span>
            </span>
        </span>
        <span class="stage_content stage_row">
            <span class="left">
                <span class="title">
                    <span class="patient_list" id="show" data-name="Show">
                        <img src="{{ elixir('images/sidebar/green-dot.png') }}"> Show
                    </span>
                </span>
                <span class="count">
                    <span class="category_count show patient_list" id="show" data-name="Show"></span>
                </span>
            </span>
            <span class="right">
                <span class="right_row">
                    <span class="right_row_section title_section">
                        <span class="patient_list" id="no_show" data-name="No Show">
                            <img src="{{ elixir('images/sidebar/red-dot.png') }}"> No Show
                        </span>
                    </span>
                    <span class="right_row_section no_show right_section_count category_count patient_list" id="no_show" data-name="No Show">
                    </span>
                </span>
            </span>
        </span>
    </div>
    <div class="report_stage_data">
        <span class="stage_header stage_row">
            <span class="left arial_bold">
                <span class="heading">
                    <span class="patient_list" id="exam_report" data-name="Exam Report">
                        Exam Report
                    </span>
                </span>
                <span class="count">
                    <span class="category_count exam_report patient_list" id="exam_report" data-name="Exam Report"></span>
                </span>
            </span>
            <span class="right arial_italic">
                <span class="days_in_stage" data-toggle="tooltip" title="Average number of days taken to mark patient as 'successful' after receiving their reports" data-placement="left">
                    Avg. Time: <span class="show_days"></span> days
                </span>
            </span>
        </span>
        <span class="stage_content stage_row">
            <span class="left">
                <span class="title">
                    <span class="patient_list" id="reports" data-name="Report">
                        <img src="{{ elixir('images/sidebar/green-dot.png') }}"> Reports
                    </span>
                </span>
                <span class="count">
                    <span class="category_count reports patient_list" id="reports" data-name="Report"></span>
                </span>
            </span>
            <span class="right">
                <span class="right_row">
                    <span class="right_row_section title_section">
                        <span class="patient_list" id="no_reports" data-name="No Report">
                            <img src="{{ elixir('images/sidebar/red-dot.png') }}"> No Reports
                        </span>
                    </span>
                    <span class="right_row_section no_reports right_section_count category_count patient_list" id="no_reports" data-name="No Report">
                    </span>
                </span>
            </span>
        </span>
    </div>
</div>


<div class="modal fade" id="patientList" role="dialog">
    <div class="modal-dialog alert">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="action_modal_title reach_report_patient_list arial_bold" id="action_header">Patient List</h4>
                <span class="report_patient_list_header"></span>
            </div>
            <div class="modal-body">
                <ul class="patient_listing">
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('mobile_sidebar_content')
@include('reports.sidebar')
@endsection
