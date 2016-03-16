@extends('layouts.master') @section('title', 'My Ocuhub - Administration') @section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/users.css')}}">
<script type="text/javascript" src="{{elixir('js/users.js')}}"></script>
@endsection @section('sidebar') @include('admin.sidebar') @endsection @section('content') @if (Session::has('success'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>
            <i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
        </strong> {{ Session::pull('success') }}
</div>
@endif

<div class="content-section active" id="admin-user-console">
    @if (Session::has('error'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>
            <i class="fa fa-check-circle fa-lg fa-fw"></i> Error. &nbsp;
        </strong> {{ Session::pull('error') }}
    </div>
    @endif @if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops! Something went wrong!</strong>

        <br>
        <br>

        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="row form_row_margin">
        <div class="col-xs-12 top_nav">
            <a href="/administration/users">
                <button class="btn back_btn">Back</button>
            </a>
            <span class="add_title">
            @if(isset($user['email']))
                Edit User
            @else
                Add New User
            @endif
            </span>
        </div>
        <div class="col-xs-12">
            <form method="POST" action="{{$data['url']}}">
                {!! csrf_field() !!} {{ method_field('POST') }}
                <div class="panel-group accordian_margin" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
        Roles and User Access</a>
      </h4>
                        </div>
                        <div id="collapse1" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6">
                                        {!! Form::select('usertype', $userTypes, $user['usertype_id'], array('class' => 'input, add_user_input', 'placeholder' => 'Select User Types*', 'id' => 'user_type', 'required' => 'required')) !!}
                                        {!! Form::select('userlevel', $userLevels, $user['level'], array('class' => 'input, add_user_input', 'placeholder' => 'Select User Levels*', 'id' => 'user_level', 'required' => 'required')) !!}
                                        @if(session('user-level') == 1)
                                        {!! Form::select('user_network', $networks, $user['network_id'], array('class' => 'input, add_user_input', 'placeholder' => 'Select Network*', 'id' => 'user_network', 'required' => 'required')) !!}
                                        @endif
                                        @if(session('user-level') == 1 || session('user-level') == 2)
                                        {!! Form::select('user_practice', $practices, $user['practice_id'], array('class' => 'input, add_user_input', 'placeholder' => 'Select Practice*', 'id' => 'user_practice', 'required' => 'required')) !!}
                                        @else
                                        {!! Form::hidden('user_practice', $user['practice_id'], array('id' => 'user_practice')) !!}
                                        @endif


                                        {!! Form::select('landing_page', $menuoption, $user['menu_id'], array('class' => 'add_user_input', 'placeholder' => 'Select Landing Page', 'id' => 'landing_page')) !!}

                                    </div>
                                    <div class="col-xs-12 col-sm-6" style="color:#fff;">
                                       <h4>Roles*</h4>
                                        @foreach($roles as $role) @if(isset($user[$role])) {!! Form::checkbox('role[]', $role, true); !!} @else {!! Form::checkbox('role[]', $role); !!} @endif {!! Form::label('role', $role); !!}
                                        <br> @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
        General Information</a>
      </h4>
                        </div>
                        <div id="collapse2" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6">
                                        {!! Form::text('title', $user['title'], array('class' => 'input, add_user_input', 'placeholder' => 'Title', 'id' => 'title')) !!}
                                        {!! Form::text('firstname', $user['firstname'], array('class' => 'input, add_user_input', 'placeholder' => 'First Name*', 'id' => 'first_name', 'required' => 'required')) !!}
                                        {!! Form::text('middlename', $user['middlename'], array('class' => 'input, add_user_input', 'placeholder' => 'Middle Name', 'id' => 'middle_name')) !!}
                                        {!! Form::text('lastname', $user['lastname'], array('class' => 'input, add_user_input', 'placeholder' => 'Last Name*', 'id' => 'last_name', 'required' => 'required')) !!}
                                        {!! Form::text('npi', $user['npi'], array('class' => 'input, add_user_input', 'placeholder' => 'NPI', 'id' => 'npi')) !!}
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        {!! Form::text('cellphone', $user['cellphone'], array('class' => 'input, add_user_input', 'placeholder' => 'Phone Number*', 'id' => 'cell_phone')) !!}
                                        {!! Form::text('address1', $user['address1'], array('class' => 'input, add_user_input', 'placeholder' => 'Address 1*', 'id' => 'address1', 'required' => 'required')) !!}
                                        {!! Form::text('address2', $user['address2'], array('class' => 'input, add_user_input', 'placeholder' => 'Address 2', 'id' => 'address2')) !!}
                                        {!! Form::text('city', $user['city'], array('class' => 'input, add_user_input', 'placeholder' => 'City', 'id' => 'city')) !!}
                                        {!! Form::text('zip', $user['zip'], array('class' => 'input, add_user_input', 'placeholder' => 'Zip', 'id' => 'zip')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
        Direct Address and Password</a>
      </h4>
                        </div>
                        <div id="collapse3" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6">
                                        {!! Form::email('email', $user['email'], array('class' => 'input, add_user_input','required' => 'required', 'placeholder' => 'Email*', 'id' => 'email')) !!}
                                        {!! Form::email('sesemail', $user['sesemail'], array('class' => 'input, add_user_input', 'placeholder' => 'SES Email', 'id' => 'ses_email')) !!}
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        {!! Form::password('password', array('class' => 'input, add_user_input', 'placeholder' => 'Password*', 'id' => 'password', 'required' => 'required')) !!}
                                        {!! Form::password('password_confirmation', array('class' => 'input, add_user_input', 'placeholder' => 'Password Confirmation*', 'id' => 'confirm_password', 'required' => 'required')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 no-padding">
                    {!! Form::submit('Save', array('class' => 'btn add_user_submit_button')) !!}
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
