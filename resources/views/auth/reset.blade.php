@extends('layouts.master')
@section('title', 'Ocuhub - Reset Password')
@section('content')
<form method="POST" action="/password/reset">
    {!! csrf_field() !!}
    <input type="hidden" name="token" value="{{ $token }}">
    @if (count($errors) > 0)
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif
    <div class="row content-row-margin">
        <div class="col-sm-12">
            <p class="white-text">Reset your password </p>
        </div>
    </div>
    <div class="row content-row-margin">
        <div class="col-sm-12">
            {!! Form::email('email', old('email'), array('class' => 'input', 'name' => 'email', 'placeholder' => 'EMAIL', 'id' => 'email')) !!}
        </div>
    </div>
    <div class="row content-row-margin">
        <div class="col-sm-12">
            {!! Form::password('password', array('class' => 'input', 'name' => 'password','placeholder' => 'NEW PASSWORD', 'id' => 'password')) !!}
        </div>
    </div>
    <div class="row content-row-margin">
        <div class="col-sm-12">
            {!! Form::password('password_confirmation', array('class' => 'input', 'name' => 'password_confirmation','placeholder' => 'CONFIRM PASSWORD', 'id' => 'password_confirmation')) !!}
        </div>
    </div>
    <div class="row content-row-margin">
        <div class="col-sm-12">
            {!! Form::submit('RESET PASSWORD', array('class' => 'button')) !!}
        </div>
    </div>
</form>
@endsection