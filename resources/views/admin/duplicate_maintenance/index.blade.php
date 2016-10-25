@extends('layouts.master')

@section('title', 'My Ocuhub - Duplicate Maintenance')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/duplicate_maintenance.css')}}">
<script type="text/javascript" src="{{elixir('js/duplicate_maintenance.js')}}"></script>
@endsection

@section('sidebar')
    @include('admin.sidebar')
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

<div class="content-row-margin side_padding ">
    <div class="row">
        <p class="page_title arial_bold">
            Duplicate Maintenance
        </p>
    </div>
    <div class="search_row">
        <input type="text" class="search_box arial_italic" placeholder="search" id="clean_up_text">
        <span class="input_field">
            <select class="cleanup_dropdown" id="clean_up_option">
                <option value="referred_by_practice">Referred By Practice</option>
                <option value="referred_by_provider">Referred By Provider</option>
                <option value="disease_type">Disease Types</option>
                <option value="appointment_types">Appointment Types</option>
                <option value="manual_appointment_types">Manual Appointment Types</option>
                <option value="insurance_details">Insurance Details</option>
            </select>
        </span>
        <input type="text" class="search_box arial_italic" placeholder="Correct Value" id="update_value">
        <button type="button" class="btn add-btn" id="update_button">Update Value</button>
    </div>
    <div class="list_row arial">
    </div>
</div>

@endsection
@section('mobile_sidebar_content')
@include('admin.sidebar')
@endsection
