@extends('layouts.master')

@section('title', 'Care Console')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/careconsole.css')}}">
<script type="text/javascript" src="{{elixir('js/careconsole.js')}}"></script>
@endsection

@section('sidebar')
    @include('careconsole.sidebar')
@endsection

@section('content')

 <div class="content-row-margin">
<div class="row">
    <div class="col-xs-12">
        <div class="section-header">
            Overview
            <div class="search" id="care_console_search">
                <input type="text" id="search_data">
                <span class="glyphicon glyphicon-chevron-left active" aria-hidden="true" id="search_bar_open"></span>
                <span class="glyphicon glyphicon-search" aria-hidden="true" id="search_do"></span>
                <div class="search_result">
                    <div class="search_result_row row">
                        <div class="col-xs-2 circle red"></div>
                        <div class="col-xs-10 search_result_row_text">
                            <p class="result_title">Past Appointments</p>
                        </div>
                    </div>
                    <div class="search_result_row row">
                        <div class="col-xs-10 col-xs-offset-2 search_result_row_text">
                            <p class="result_title">Scheduled To</p>
                            <p class="result_text">Daniel Garibaldi</p>
                        </div>
                    </div>
                    <div class="search_result_row row">
                        <div class="col-xs-10 col-xs-offset-2 search_result_row_text">
                            <p class="result_title">Appointment Date</p>
                            <p class="result_text">January 8, 2016</p>
                        </div>
                    </div>
                    <div class="search_result_row row">
                        <div class="col-xs-10 col-xs-offset-2 search_result_row_text">
                            <p class="result_title">Take action
                            <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('careconsole.overview')

</div>
@endsection
