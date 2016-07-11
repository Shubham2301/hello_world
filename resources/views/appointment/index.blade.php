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
    {{ Session::pull('success') }}
</div>
@endif
<div class="content-section active" id="appointment_section">
    {!! Form::open(array('url' => '/patients', 'method' => 'GET', 'id' => 'form_schedule_another_appointment')) !!}
    {!! Form::hidden('referraltype_id', $data['referraltype_id'] , array('id' => 'form_referraltype_id')) !!}
    {!! Form::hidden('action', $data['action'], array('id' => 'form_action')) !!}
    {!! Form::hidden('provider_id', $data['provider_id'], array('id' => 'form_provider_id')) !!}
    {!! Form::hidden('practice_id', $data['practice_id'], array('id' => 'form_practice_id')) !!}
    {!! Form::hidden('provider_acc_key', $data['provider_acc_key'], array('id' => 'form_provider_acc_key')) !!}
    {!! Form::hidden('appointment_date', $data['appointment_date'], array('id' => 'form_appointment_date')) !!}
    {!! Form::hidden('appointment_time', $data['appointment_time'], array('id' => 'form_appointment_time')) !!}
    {!! Form::hidden('appointment_type_name', $data['appointment_type_name'], array('id' => 'form_appointment_type_name')) !!}
    {!! Form::hidden('appointment_type_id', $data['appointment_type_id'], array('id' => 'form_appointment_type_id')) !!}
    {!! Form::hidden('location_id', $data['location_id'], array('id' => 'form_location_id')) !!}
    {!! Form::hidden('location_code', $data['location_code'], array('id' => 'form_location_code')) !!}
    {!! Form::hidden('patient_id', $data['patient_id'], array('id' => 'form_patient_id')) !!}
	{!! Form::hidden('selectedfiles', $data['selectedfiles'], array('id' => 'selected_patient_files')) !!}
    {!! Form::close() !!}
    <div class="appointment_section active" id="confirm-appointment">
        <button type="button" class="btn back-btn" id="back">Back</button><h3 class="center-align arial_bold">Schedule an appointment</h3>
        <div class="row appointment_info center-align">
            <div class="col-sm-4 col-xs-12 appointment_info_div">
                <div class="appointment_info_box patient">
                    <span class="arial_bold">Patient</span>
                    <div style="width:40%;align-self: center;"><img src="{{URL::asset('images/patient-icon-schedule.png')}}" style="width:100%;"></div>
                    <h4><strong>{{ $data['patient_name'] }}</strong></h4>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12 appointment_info_div">
                <div class="appointment_info_box schedule">
                    <span class="arial_bold">Details</span>
                    <h4>{{ $data['appointment_time'] }}<br>{{ $data['appointment_date'] }}<br>{{ $data['appointment_type_name'] }}</h4><br>
                    <h4 class="schedule_place" style="padding-top:6px;"><strong>{{ $data['location'] }}</strong></h4>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12 appointment_info_div">
                <div class="appointment_info_box provider">
                    <span class="arial_bold">Provider</span>
                    <div style="width:40%;align-self: center;"><img src="{{URL::asset('images/provider-icon-schedule.png')}}" style="width:100%;"></div>
                    <h4><strong>{{ $data['provider_name'] }}</strong><br>{{ $data['practice_name'] }}</h4>
                </div>
            </div>
        </div>
        <div class="appointment_message arial">
			<input type="hidden" id="isses" value="{{$data['sesmail']}}">
			@if($data['sesmail'])

            @if($data['count_files'])
			<p class="apt_msg"><span><input type="checkbox" checked="checked" id="send_ccda_checkbox" ></span> Send {{$data['count_files']}} patient files to provider</p>
            @else
            <p> No file selected </p>
            @endif

            @else
			<p class="apt_msg"><span></span> No files will be sent because the provider does not have an SES email </p>
            @endif
        </div>
        <div class="appointment_confirm center-align arial">
            <p><button id="confirm_appointment">Confirm</button>&nbsp; <button id="cancel_appointment" data-toggle="tooltip" title="You will lose all progress" data-placement="bottom">Abort</button></p>
        </div>
        <div id="apt_loader" style="display:none;">
		<div id="schedule_apt_loader" class="hidden-xs"></div>
			<p id="loadingText"><span style="padding-left:25px;">Please wait...</span><br> It may take some time</p>
		</div>
        <div class="appointment_confirmed center-align" style="display:none">
            <button class="confirmed" disabled>Confirmed</button>
            <h4>You have scheduled an appointment</h4>
            <p id="schedule_new_patient">Schedule another appointment</p>
            <a href="/careconsole"><p id="back_to_console">Back to Care Console</p></a>
        </div>
    </div>
</div>
<button id="show_fpc_model" style="display:none" data-toggle="modal" data-target="#field_modal_fpc">show</button>
<div id="model_fpc_view"></div>
@endsection
@section('mobile_sidebar_content')
@include('layouts.sidebar')
@endsection
