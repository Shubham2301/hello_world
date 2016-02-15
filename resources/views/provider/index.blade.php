@extends('layouts.master') @section('title', 'My Ocuhub - Select Provider') @section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/provider.css')}}">
<script type="text/javascript" src="{{elixir('js/provider.js')}}"></script>
@endsection @section('sidebar') @include('layouts.sidebar') @endsection @section('content') @if (Session::has('success'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>
    <i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
    </strong>
    {{ Session::get('success') }}
</div>
@endif
<input type="hidden" id="from_admin" value="{{$data['admin']}}" >
<div class="content-section content-section-scheduling active" id="practice_section">
    {!! Form::open(array('url' => '/appointments', 'method' => 'GET', 'id' => 'form_select_provider')) !!}
    @if(array_key_exists('referraltype_id', $data))
    {!! Form::hidden('referraltype_id', $data['referraltype_id'] , array('id' => 'form_referraltype_id')) !!}
    @endif
    @if(array_key_exists('action', $data))
    {!! Form::hidden('action', $data['action'], array('id' => 'form_action')) !!}
    @endif
    @if(array_key_exists('patient_id', $data))
    {!! Form::hidden('patient_id', $data['patient_id'], array('id' => 'form_patient_id')) !!}
    @endif
    {!! Form::hidden('provider_id', '', array('id' => 'form_provider_id')) !!}
    {!! Form::hidden('practice_id', '', array('id' => 'form_practice_id')) !!}
    {!! Form::hidden('appointment_date', '', array('id' => 'form_appointment_date')) !!}
    {!! Form::hidden('appointment_time', '', array('id' => 'form_appointment_time')) !!}
    {!! Form::hidden('appointment_type_name', '', array('id' => 'form_appointment_type_name')) !!}
    {!! Form::hidden('appointment_type_id', '', array('id' => 'form_appointment_type_id')) !!}
    {!! Form::hidden('location', '', array('id' => 'form_location')) !!}
    {!! Form::hidden('location_id', '', array('id' => 'form_location_id')) !!}
    {!! Form::close() !!}
    <div class="practice_section active" id="select_practice">
        @if(array_key_exists('referraltype_id', $data) and array_key_exists('patient_id', $data))
        <div class="row content-row-margin">
            <div class="col-xs-12 section-header">
                <span class="arial_bold">Schedule an appointment</span>
                <p class="button_type_3 schedule_button" data-id="" data-practice-id="" align="right">Schedule<span class="glyphicon glyphicon-chevron-right"></span></p>
            </div>
        </div>
    </div>
    <div class="row content-row-margin-scheduling">
        <div class="col-xs-12 subsection-header-previous-stage arial">
            <span>1. Search for Patient&nbsp;<span class="glyphicon glyphicon-ok-circle" style="color:green;"></span></span>
            <span class="selected_patient arial_bold"><p>Patient Selected:</p>&nbsp;&nbsp;<p class="selected_patient_name"></p>
            <p class="view_selected_patient view">View</p>&nbsp;&nbsp;
            <p class="change_selected_patient view" id="change_patient_button">Change</p>
        </span>
    </div>
    <div class="col-xs-12 subsection-header">
        <span>2. Search for Provider</span>
    </div>
</div>
@endif @include('provider.search') @include('provider.preferences')
</div>
<div class="practice_section active" id="practice_listing">
@include('provider.listing')
</div>
<div class="row">
<div class="patient_previous_information active">
    <div class="provider_near_patient arial_bold">
        <p>Providers near the patient address&nbsp;<span class="glyphicon glyphicon-chevron-right provider_near"></span></p>
    </div>
    <div class="section_seperator">
    </div>
    <div class="provider_near_patient_list row">
    </div>
    <div class="previous_provider_patient arial_bold">
        <p>Previous providers for this patient&nbsp;<span class="glyphicon glyphicon-chevron-right provider_previous"></span></p>
    </div>
    <div class="section_seperator">
    </div>
    <div class="previous_provider_patient_list">
    </div>
</div>
</div>
</div>
@endsection