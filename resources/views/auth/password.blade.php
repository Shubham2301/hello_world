@extends('layouts.master')
@section('title', 'Ocuhub - Reset Password')
@section('content')
<form method="POST" action="/password/email">
    {!! csrf_field() !!}
    @if (count($errors) > 0)
    <div class="row">
        @foreach ($errors->all() as $error)
        <div class="col-sm-12 left-padding top-margin">
            <p class="white-text">{{ $error }}</p>
        </div>
        @endforeach
    </div>
    @endif
    <div class="row content-row-margin">
        <div class="col-sm-12">
            <p class="white-text">Please sign in </p>
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
</form>
@endsection