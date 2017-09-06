@extends('layouts.master')
@section('title', 'Network State Activity')
@section('imports')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/reports.css') }}">
    <script type="text/javascript" src="{{elixir('js/network_state_activity.js')}}"></script>
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
        <span class="arial_bold reports_header">Network State Activity</span>
        <span class="report_option_row timeline">
            <span class="arial_bold">Timeline:</span>
            <span>
                <input type="text" class="date_selector" id="start_date">
                <input type="text" class="date_selector" id="end_date">
            </span>
        </span>
        <span class="report_option_row">
            <span class="arial_bold">Network:</span>
            <select class="network_selector" id="network_id">
                @foreach($networkData as $networkID => $network)
                    <option value="{{ $networkID }}">{{ $network }}</option>
                @endforeach
            </select>
        </span>
        <span class="report_option_row">
            <span class="arial_bold">State:</span>
            <div class="state_list_container">
            @foreach($data['state-list'] as $state)
                @if($state['state'] != '')
                <span class="state_wraper"><input type="checkbox" value="{{ $state['state'] }}" name="state_list"> {{ $state['state'] }} </span>
                @endif
            @endforeach
            </div>
        </span>
        <span class="report_option_row">
            <button type="submit" class="btn" id="get_network_state_activity">Generate</button>
        </span>
    </div>
@endsection
@section('mobile_sidebar_content')
    @include('reports.sidebar')
@endsection
