@extends('layouts.master')
@section('title', 'Care Console')
@section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/careconsole.css')}}">
<script type="text/javascript" src="{{elixir('js/careconsole.js')}}"></script>
<script type="text/javascript" src="{{elixir('js/import.js')}}"></script>
@endsection
@section('sidebar')
@include('careconsole.sidebar')
@endsection
@section('content')
<div class="content-row-margin">
    <div class="row before_drilldown">
        <div class="col-xs-12">
            <div class="section-header">
                <button type="button" class="btn import-btn open_import" data-toggle="modal" data-target="#importModal" id="import_patients">Import</button>
                <span class="overview_header">Overview</span>
                <div class="search" id="care_console_search">
                    <input type="text" id="search_data">
                    <span class="glyphicon glyphicon-chevron-left active" aria-hidden="true" id="search_bar_open"></span>
                    <span class="glyphicon glyphicon-search" aria-hidden="true" id="search_do"></span>
                    <div class="search_result">
                        <div class="search_result_row row">
                            <div class="col-xs-2 circle "></div>
                            <div class="col-xs-10 search_result_row_text">
                                <p class="result_title stage_name">Past Appointments</p>
                            </div>
                        </div>
                        <div class="search_result_row row">
                            <div class="col-xs-10 col-xs-offset-2 search_result_row_text">
                                <p class="result_title">Scheduled To</p>
                                <p class="result_text scheduled_to">-</p>
                            </div>
                        </div>
                        <div class="search_result_row row">
                            <div class="col-xs-10 col-xs-offset-2 search_result_row_text">
                                <p class="result_title">Appointment Date</p>
                                <p class="result_text appointment_date">-</p>
                            </div>
                        </div>
                        {{-- <div class="search_result_row row">
                            <div class="col-xs-10 col-xs-offset-2 search_result_row_text">
                                <p class="result_title">Take action
                                    <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
                                </p>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('careconsole.overview')
    @include('careconsole.drilldown')
</div>
@endsection