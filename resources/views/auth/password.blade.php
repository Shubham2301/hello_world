@extends('layouts.master') @section('title', 'Ocuhub - Reset Password') @section('content')
<form method="POST" action="/password/email">
    {!! csrf_field() !!} @if (count($errors) > 0)
    <div class="row" id="flash-message">
        @foreach ($errors->all() as $error)
        <div class="col-sm-12 left-padding top-margin">
            <p class="white-text">{{ $error }}</p>
        </div>
        @endforeach
    </div>
    @endif @if(session()->has('success'))
	<p class="arial side_padding">Sent!</p>
	<p class="side_padding top_margin">The link expires in an hour.</p>
    @else
    <div class="row content-row-margin">
        <div class="col-sm-12">
            <p class="white-text">Reset password </p>
        </div>
    </div>
    <div class="row content-row-margin">
        <div class="col-sm-12">
            {!! Form::email('email', old('email'), array('class' => 'input', 'name' => 'email', 'placeholder' => 'EMAIL', 'id' => 'email')) !!}
        </div>
    </div>
    <div class="row content-row-margin">
        <div class="col-sm-12 left-padding box-margin">
            <button type="submit" class="button" type="submit">Send Password Reset Link</button>
        </div>
    </div>
	{!! Form::close() !!}
	@endif
	<p class="side_padding" style="padding-top: 5em;">Go back to <a title="" style="font-weight: bold; text-decoration: underline;" href="/auth/logout" data-original-title="">Sign in</a></p>

@endsection
