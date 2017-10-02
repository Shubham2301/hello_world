@extends('layouts.master') 
@section('title', 'Accounting Reports')
@section('imports')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="stylesheet" type="text/css" href="{{ elixir('css/reports.css') }}">
<link rel="stylesheet" type="text/css" href="{{ elixir('css/accounting_report.css') }}">
<script type="text/javascript" src="{{ elixir('js/accounting_report.js') }}"></script>
@endsection 
@section('sidebar') @include('reports.sidebar') @endsection 
@section('content') 
@if (Session::has('success'))
<div class="alert alert-success" id="flash-message">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>
        <i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
    </strong> {{ Session::pull('success') }}
</div>
@endif
<div class="reports_container arial" id="record_report">
    <span class="arial_bold reports_header">Accounting Reports</span>


    <div class="panel-group accounting_reports" id="accordion">
        @include('reports.accounting_reports.provider_billing')
        @include('reports.accounting_reports.payer_billing')
    </div>
</div>

@endsection @section('mobile_sidebar_content') @include('reports.sidebar') @endsection