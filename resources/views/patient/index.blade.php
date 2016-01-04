@extends('layouts.master')

@section('title', 'My Ocuhub - Select Patients')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{asset('css/patient.css')}}">
<script type="text/javascript" src="{{asset('js/patient.js')}}"></script>
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

    <div class="content-section active" id="patients_section">
        <div class="patients_section active" id="select_patient">
            @if(array_key_exists('referraltype_id', $data))
            <div class="row content-row-margin">
                <div class="col-xs-12 section-header">
                    <span class="">Schedule an appointment</span>
                </div>
            </div>
            <div class="row content-row-margin">
                <div class="col-xs-12 subsection-header">
                    <span>1. Select for a patient</span>
                </div>
            </div>
            @endif
            @include('patient.search')
        </div>
        <div class="patients_section active" id="patient_listing">
            @include('patient.listing')
        </div>
        {!! Form::open(array('url' => '/practices', 'method' => 'GET', 'id' => 'form_select_provider')) !!}

                {!! Form::hidden('referraltype_id', $data['referraltype_id'] , array('id' => 'form_referraltype_id')) !!}
                {!! Form::hidden('action', $data['action'], array('id' => 'form_action')) !!}
                {!! Form::hidden('patient_id', '', array('id' => 'form_patient_id')) !!}

        {!! Form::close() !!}
    </div>


@endsection
