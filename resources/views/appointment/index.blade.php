@extends('layouts.master')
@section('title', 'My Ocuhub - Schedule Appointment')
@section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/appointment.css')}}">
<script type="text/javascript" src="{{elixir('js/appointment.js')}}"></script>
@endsection
@section('sidebar')
@include('layouts.sidebar')
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
<div class="content-section active" id="appointment_section">
    {!! Form::open(array('url' => '/patients', 'method' => 'GET', 'id' => 'form_schedule_another_appointment')) !!}
    {!! Form::hidden('referraltype_id', $data['referraltype_id'] , array('id' => 'form_referraltype_id')) !!}
    {!! Form::hidden('action', $data['action'], array('id' => 'form_action')) !!}
    {!! Form::hidden('provider_id', $data['provider_id'], array('id' => 'form_provider_id')) !!}
    {!! Form::hidden('practice_id', $data['practice_id'], array('id' => 'form_practice_id')) !!}
    {!! Form::hidden('appointment_date', $data['appointment_date'], array('id' => 'form_appointment_date')) !!}
    {!! Form::hidden('appointment_time', $data['appointment_time'], array('id' => 'form_appointment_time')) !!}
    {!! Form::hidden('appointment_type_name', $data['appointment_type_name'], array('id' => 'form_appointment_type_name')) !!}
    {!! Form::hidden('appointment_type_id', $data['appointment_type_id'], array('id' => 'form_appointment_type_id')) !!}
    {!! Form::hidden('location_id', $data['location_id'], array('id' => 'form_location_id')) !!}
    {!! Form::hidden('patient_id', $data['patient_id'], array('id' => 'form_patient_id')) !!}
    {!! Form::close() !!}
    <div class="appointment_section active" id="confirm-appointment">
        <button type="button" class="btn back-btn" id="back">Back</button><h3 class="center-align arial_bold">Schedule an appointment</h3>
        <div class="row appointment_info center-align">
            <div class="col-xs-4">
                <div class="appointment_info_box patient">
                    <span class="arial_bold">Patient</span>
                    <div><img src="{{URL::asset('images/patient-img.png')}}" style="width:40%;"></div>
                    <h4><strong>{{ $data['patient_name'] }}</strong></h4>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="appointment_info_box schedule">
                    <span class="arial_bold">Details</span>
                    <h4>{{ $data['appointment_time'] }}<br>{{ $data['appointment_date'] }}<br>{{ $data['appointment_type_name'] }}</h4><br>
                    <h4 class="schedule_place" style="padding-top:6px;"><strong>{{ $data['location'] }}</strong></h4>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="appointment_info_box provider">
                    <span class="arial_bold">Provider</span>
                    <div><img src="{{URL::asset('images/provider-img.png')}}" style="width:40%;"></div>
                    <h4><strong>{{ $data['provider_name'] }}</strong><br>{{ $data['practice_name'] }}</h4>
                </div>
            </div>
        </div>
        <div class="appointment_message arial">
            <p><span><input type="checkbox"></span> Send patient C-CDA file to provider</p>
        </div>
        <div class="appointment_confirm center-align arial">
            <p><button id="confirm_appointment">Confirm</button>&nbsp; <button id="cancel_appointment" data-toggle="tooltip" title="You will loose all progress" data-placement="bottom">Abort</button></p>
        </div>
        <div class="appointment_confirmed center-align">
            <button class="confirmed" disabled>Confirmed</button>
            <h4>You have scheduled an appointment</h4>
            <p id="schedule_new_patient">Schedule another appointment</p>
            <a href="/careconsole"><p id="back_to_console">Back to Care Console</p></a>
        </div>
    </div>
</div>
@endsection