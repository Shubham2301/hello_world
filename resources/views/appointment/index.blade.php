@extends('layouts.master')

@section('title', 'My Ocuhub - Schedule Appointment')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{asset('css/appointment.css')}}">
<script type="text/javascript" src="{{asset('js/appointment.js')}}"></script>
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
            
        </div>
    </div>



@endsection
