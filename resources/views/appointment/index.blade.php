@extends('layouts.master')

@section('title', 'My Ocuhub - Schedule Appointment')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{asset('css/appointment.css')}}">
<script type="text/javascript" src="{{asset('js/appointment.js')}}"></script>
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
        <div class="appointment_section active" id="confirm-appointment">
            <h3 class="center-align">Schedule an appointment</h3>
            <div class="row appointment_info center-align">
                <div class="col-xs-4">
                    <div class="appointment_info_box patient">
                        <h4>Patient</h4>
                        <img src="{{URL::asset('images/provider.png')}}"><br><br><br>
                        <h4>{{ $data['patient_name'] }}</h4>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="appointment_info_box schedule">
                        <h4>5PM,<br>Tuesday,<br>5-Jan-2015</h4><br><br>
                        <h4 class="schedule_place" style="padding-top:10px;">Rockville Center</h4>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="appointment_info_box provider">
                        <h4>Provider</h4>
                        <img src="{{URL::asset('images/provider.png')}}"><br><br><br>
                        <h4><strong>{{ $data['provider_name'] }}</strong><br>{{ $data['practice_name'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="appointment_message">
                <p><span><input type="checkbox"></span> Send patient CCD file to provider</p>
            </div>
            <div class="appointment_confirm center-align">
                <p><button id="confirm_appointment">Confirm</button>&nbsp;                                            <button id="cancel_appointment">Cancel</button></p>
            </div>
         <div class="appointment_confirmed center-align">
                <button class="confirmed" disabled>Confirmed</button>
             <h4>You have scheduled an appointment</h4>
            </div>

        </div>
    </div>



@endsection
