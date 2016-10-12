@extends('layouts.master')

@section('title', 'My Ocuhub - Audit Reports')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/reporting.css')}}">
<script type="text/javascript" src="{{elixir('js/audit.js')}}"></script>
@endsection

@section('sidebar')
   @include('admin.sidebar')
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
<span class="report_header">
    <span class="arial_bold title">Admin Reports</span>
    <span class="input_field">
    <select class="network_dropdown audit_report_network" id="select_report_type">
        <option value="audit_report">Audit Report</option>
        <option value="impersonation_report">Impersonation Report</option>
    </select>
    </span>
	<span><button class="btn" id ="export_report_btn" onclick="downloadASXSLS()">Export</button></span>
    <br>
    <span class="input_field">Start Date<input type="text" id="start_date"></span>
    <span class="input_field">End Date<input type="text" id="end_date"></span>
    <span class="input_field">Select Network
        <select class="network_dropdown audit_report_network" id="select_network">
            <option value="">All</option>
            @foreach($data['networks'] as $network)
            <option value="{{ $network['id'] }}">{{ $network['name'] }}</option>
            @endforeach
        </select>
    </span>
    <span>
        <button class="btn add-btn clickable" id="get_report">Get Report</button>
    </span>
</span>
<div class="row reporting_content">
    <div class="row arial_bold no-margin audit_report_header" style="display:none;">
        <div class="col-xs-3 info_col">
            <p>Date</p>
        </div>
        <div class="col-xs-2 info_col">
            <p>User</p>
        </div>
        <div class="col-xs-2 info_col">
            <p>Network</p>
        </div>
        <div class="col-xs-5 info_col">
            <p>Action</p>
        </div>
    </div>
    <div id="audit_reports">

    </div>
</div>
@endsection
