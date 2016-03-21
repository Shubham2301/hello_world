@extends('layouts.master')
@section('title', 'My Ocuhub - File Exchange')
@section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/users.css')}}">
<script type="text/javascript" src="{{elixir('js/users.js')}}"></script>
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
    </strong> {{ Session::pull('success') }}
</div>
@endif
<div class="container">
	<div class="row" style="margin-top: 3em;">
        <div class="col-xs-12">
            <a href="/home">
                <button class="btn back_btn">Back</button>
            </a>
        </div>
		{!! Form::open(array('url' => '/updateprofile', 'method' => 'post','files'=>true, 'id'=>'profile_form')) !!}
            <div class="col-xs-12">
                <div class="col-xs-5 center-align">
                    <img src="{{URL::asset('images/users/user_'. Auth::user()->id .'.jpg')}}" class="img-responsive edit_profile_img" onerror="this.src = '{{URL::asset('images/sidebar/care_coordinator.png')}}'" id="profile_image_view">
                    <br>
                    <p class="button_type_1" id="change_practice_button file_button">
                        <input type="file" name="profile_img" class="profile_img_upload file_upload">
                        <span>Change Profile Image</span>
                    </p>
                    </p>
                </div>
                <div class="col-xs-7">
                    <div class="col-xs-2">
                        {!! Form::text('title', $profile['title'], array('class' => 'input, add_user_input edit_profile_input', 'placeholder' => 'Title', 'id' => 'title')) !!}
                    </div>
                    <div class="col-xs-5">
                        {!! Form::text('lastname', $profile['lastname'], array('class' => 'input, add_user_input edit_profile_input', 'placeholder' => 'Last Name*', 'id' => 'last_name', 'required' => 'required')) !!}
                    </div>
                    <div class="col-xs-5">
                        {!! Form::text('firstname', $profile['firstname'], array('class' => 'input, add_user_input edit_profile_input', 'placeholder' => 'First Name*', 'id' => 'first_name', 'required' => 'required')) !!}
                    </div>
                    <div class="col-xs-6">
                        {!! Form::password('password', array('class' => 'input, add_user_input edit_profile_input', 'placeholder' => 'New Password', 'id' => 'password')) !!}
                    </div>
                    <div class="col-xs-6">
                        {!! Form::password('password_confirmation', array('class' => 'input, add_user_input edit_profile_input', 'placeholder' => 'Password Confirmation', 'id' => 'confirm_password')) !!}
                    </div>
					<div class="col-xs-12" style="text-align:right;margin-top:69px;">
                        {!! Form::submit('Save', array('class' => 'btn add_user_submit_button')) !!}
                    </div>
                </div>
            </div>
       {!!Form::close()!!}
    </div>
</div>
</div>
@endsection
