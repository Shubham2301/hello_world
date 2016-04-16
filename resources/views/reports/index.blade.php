@extends('layouts.master')

@section('title', 'My Ocuhub - Reports')

@section('imports')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<link rel="stylesheet" type="text/css" href="{{elixir('css/reports.css')}}">
<script type="text/javascript" src="{{elixir('js/reports.js')}}"></script>
@endsection

@section('sidebar')
    @include('reports.sidebar')
@endsection

@section('content')

	@if (Session::has('success'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>
            <i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
        </strong>
        {{ Session::pull('success') }}
    </div>
    @endif
<!--
<span class="report_header">
    <span class="arial_bold title">Reports</span>
    <span class="input_field">Start Date<input type="text" id="start_date"></span>
    <span class="input_field">End Date<input type="text" id="end_date"></span>
</span>
-->
<div class="dashboard_container">
    <div class="dashboard_inner">

        <div class="row">
            <div class="col-xs-12">
                <p class="drilldown" id="drilldown_filters">

<!--
                               <span>Drilldown:</span><span>&nbsp;Female</span><span>&nbsp;> Diabetes</span> -->
                </p>
                <div class="col-xs-12">

                </div>
                <p class="realtime_section">
                    <span class="report_header">Population Report&nbsp;&nbsp;</span><br class="visible-xs">
                    <span class="report_time_type">Real Time</span>
                    <span class="report_time_badge"><?php echo date('j-M-y');?></span>

                </p>
            </div>
                          <div class="historical_section">
                          <div class="historical_subsection">
                                <span class="report_header">Population Report&nbsp;&nbsp;</span><br class="visible-xs">
                                <span class="report_time_type">Timeline:&nbsp;</span>
                          </div>

                             <div class="historical_subsection header_margin">
                                <p class="date_selector"><input type="text align-center" class="date_selector" id="start_date" style="width:80px;">
<!--                                <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>-->
                                </p>
                             </div>
                             <div class="historical_subsection header_margin align-center">
                                <span class="report_time_type align-center">to&nbsp;</span>
                              </div>
                             <div class="historical_subsection header_margin">
                                <p class="date_selector"><input type="text" class="date_selector align-center" id="end_date" style="width:80px;">
<!--                                <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>-->
                                </p>
                             </div>
                            </div>
            </div>



        <div class="row">
<!--           <div class="col-xs-12">-->
                          <div class="row historical_section">
                           <ul class="nav nav-pills">
                                <li class="active referred_by"><a href="#">Referred by</a></li>
                                <li class="referred_to"><a href="#">Referred To</a></li>
                           </ul>
                           <div class="col-xs-12 referral_graph">
                               <div  id="linechart_material" class="chart referred_by"></div>
                            </div>

                            </div>
<!--            </div>-->
            <div class="col-xs-12 col-sm-8">
                <div class="report_section">
                  <div class="realtime_section">
                   <p class="report_sub_header">Status of Patients</p>
                    <div class="separator"></div>
                        <div class="row">
                            <div id="status_of_patients" class="reporting_section"></div>
                        </div>
                    </div>
                    </div>


                <div class="section_separator"></div>
                <p class="report_sub_section_header">Patient Demography</p>
                <div class="report_sub_section">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="report_section">
                                <p class="report_sub_header">Gender</p>
                                <div class="row">

                                    <div class="col-xs-12 remove-padding drilldown_item" data-id="male" data-type="gender" data-meta="Male" >
                                        <div class="col-xs-8">
                                            <p class="report_content_label">Male</p>
                                        </div>
                                        <div class="col-xs-4" id="male_percent">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 remove-padding drilldown_item" data-id="female" data-type="gender" data-meta="Female">
                                        <div class="col-xs-8">
                                            <p class="report_content_label">Female</p>
                                        </div>
                                        <div class="col-xs-4" id="female_percent">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="separator-2"></div>
                            <div class="report_section">
                                <p class="report_sub_header">Insurance Type</p>
                                <div class="row" id="insurance_type"></div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <p class="report_sub_header align-center">Age Breakdown</p>
                            <div id="piechart"></div>
                        </div>
                    </div>
                </div>
                <div class="section_separator"></div>
                <div class="report_section">
                    <div class="row">
                        <div class="col-xs-6">
                            <p class="report_sub_header">Disease Type</p>
                        </div>
                        <div class="col-xs-6">
                            <p class="report_sub_header">Scale</p>
                        </div>
                    </div>
                    <div class="separator"></div>
                    <div class="row" id="disease_type">
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4">
               <div class="realtime_section">
                <p class="report_section_header">Referrals</p>
                <div class="report_section_inner">
                    <p class="report_sub_header">Referred By <span id="referred_by_meta"></span></p>
                    <div class="row" id="referred_by"></div>
                </div>
            </div>
                <div class="section_separator"></div>
                <div class="report_section">
                    <p class="report_sub_header">Appointment Type</p>
                    <div class="separator"></div>
                    <div class="row" id="appointment_type"></div>
                </div>
                <div class="section_separator"></div>

                <div class="report_section_inner realtime_section">
                    <p class="report_sub_header">Referred To <span id="referred_to_meta"> </span></p>
                    <div class="row" id="referred_to">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
