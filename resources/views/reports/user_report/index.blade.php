@extends('layouts.master')
@section('title', 'User Report')
@section('imports')
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/reports.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/user_report.css') }}">
    <script type="text/javascript" src="{{ elixir('js/user_report.js') }}"></script>
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
        <div class="row report_header_row">
            <span class="arial_bold reports_header">User Report</span>
        </div>
        <div class="row report_content">
            <div class="report_table">
            </div>
        </div>
    </div>

@endsection
@section('mobile_sidebar_content')
    @include('reports.sidebar')
@endsection
