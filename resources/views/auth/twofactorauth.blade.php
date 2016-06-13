@extends('layouts.master')
@section('title', 'Ocuhub - Reset Password')
@section('content')
    @if (count($errors) > 0)
    <ul id="flash-message">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif
    <div class="row content-row-margin">
        <div class="col-sm-12">
            <p class="white-text">Enter the OTP sent to your configured cellphone.</p>
        </div>
    </div>
    <form method="POST" action="/auth/verifyotp">
    <div class="row content-row-margin">
        <div class="col-sm-12">
            {!! Form::text('otp', '', array('class' => 'input', 'name' => 'otp', 'placeholder' => 'OTP', 'id' => 'otp')) !!}
        </div>
    </div>
    <div class="row content-row-margin">
        <div class="col-sm-12">
            {!! Form::submit('VERIFY OTP', array('class' => 'button')) !!}
        </div>
    </div>
    </form>
    <form method="POST" action="/auth/resendotp">
    <div class="row content-row-margin">
        <div class="col-sm-12">
            {!! Form::submit('RESEND OTP', array('class' => 'button')) !!}
        </div>
    </div>
    </form>
@endsection