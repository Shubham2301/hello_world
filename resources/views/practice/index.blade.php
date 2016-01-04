@extends('layouts.master')

@section('title', 'My Ocuhub - Select Provider')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{asset('css/practice.css')}}">
<script type="text/javascript" src="{{asset('js/practice.js')}}"></script>
@endsection

@section('content')

	@if (Session::has('success'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>
            <i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
        </strong>
        {{ Session::get('success') }}
    </div>
    @endif

    <div class="content-section active" id="practice_section">
        <div class="practice_section active" id="select_practice">
            @if(array_key_exists('referraltype_id', $data) and array_key_exists('patient_id', $data))
            <div class="row content-row-margin">
                <div class="col-xs-12 section-header">
                    <span class="">Schedule an appointment</span>
                </div>
            </div>
            <div class="row content-row-margin">
                <div class="col-xs-12 subsection-header">
                    <span>2. Select a Provider</span>
                </div>
            </div>
            @endif
            @include('practice.search')
        </div>
        <div class="practice_section active" id="practice_listing">
            @include('practice.listing')
        </div>
        <div class="row">
        <div class="patient_previous_information active">
            <div class="provider_near_patient">
                <p>Providers near the patient address&nbsp;<span class="glyphicon glyphicon-chevron-right provider_near"></span></p>
            </div>
            <div class="section_seperator">
            </div>
            <div class="provider_near_patient_list row">
            </div>
            <div class="previous_provider_patient">
                <p>Previous providers for this patient&nbsp;<span class="glyphicon glyphicon-chevron-right provider_previous"></span></p>
            </div>
            <div class="section_seperator">
            </div>
            <div class="previous_provider_patient_list">
            </div>
            </div>
        </div>
        <div class="row">
        <div class="col-xs-12 selected_patient center-align">
            <p>You have selected a patient</p>
           <img src="{{URL::asset('images/patient.png')}}">
        </div>
        </div>
    </div>



@endsection
