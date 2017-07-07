@extends('layouts.master')
@section('title', 'HEDIS Supplementary Export')
@section('imports')
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/reports.css') }}">
    <script type="text/javascript" src="{{elixir('js/hedis_export.js')}}"></script>
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
    <div class="reports_container arial" id="record_report">
        <span class="arial_bold reports_header">HEDIS Supplementary Export</span>
        <span class="report_option_row">
            <span>Network:</span>
            <select class="network_selector" id="network_id">
                @foreach($networkData as $networkID => $network)
                    <option value="{{ $networkID }}">{{ $network }}</option>
                @endforeach
            </select>
        </span>
        <span class="report_option_row">
            <button type="submit" class="btn" id="export_hedis_supplementary">Generate CSV</button>
        </span>
        <span class="export_status" style="margin-top:1em;">
            
        </span>
    </div>
@endsection
@section('mobile_sidebar_content')
    @include('reports.sidebar')
@endsection
