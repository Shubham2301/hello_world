@extends('layouts.master') @section('title', 'My Ocuhub - Administration') @section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/users.css')}}">
<script type="text/javascript" src="{{elixir('js/users.js')}}"></script>
@endsection @section('sidebar') @include('admin.sidebar') @endsection @section('content') @if (Session::has('success'))
<div class="alert alert-success" id="flash-message">
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
            <form method="POST" action="{{$data['url']}}" id="form_add_users">
                {!! csrf_field() !!} {{ method_field('POST') }}
                <div class="panel-group accordian_margin" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <span class="popover_text" data-content="">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
        Roles and User Access</a>
                                </span>
      </h4>
                        </div>
                        <div id="collapse1" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6">
                                        {!! Form::select('usertype', $userTypes, $user['usertype_id'], array('class' => ' add_user_input', 'placeholder' => 'Select User Types*', 'id' => 'user_type', 'required' => 'required', 'data-toggle' => 'tooltip', 'title' => 'User Type', 'data-placement' => 'right')) !!}
                                        {!! Form::select('userlevel', $userLevels, $user['level'], array('class' => ' add_user_input', 'placeholder' => 'Select User Levels*', 'id' => 'user_level', 'required' => 'required', 'data-toggle' => 'tooltip', 'title' => 'User Level', 'data-placement' => 'right')) !!}

                                        @if(session('user-level') == 1)
                                            @if($user['network_id'] == '')
                                            {!! Form::select('user_network', $networks, $user['network_id'], array('class' => 'add_user_input', 'placeholder' => 'Select Network*', 'id' => 'user_network', 'data-toggle' => 'tooltip', 'title' => 'User Network', 'data-placement' => 'right', 'style' =>'display:none')) !!}
                                            @else
                                            <input type="hidden" value={{ $user['network_id'] }} name="user_network" />
                                            {!! Form::text('non_editable_network', $networks[$user['network_id']], array('class' => ' add_user_input non_selectable_field', 'placeholder' => '', 'id' => 'title' , 'data-toggle' => 'tooltip', 'title' => 'User Network', 'data-placement' => 'right', 'readonly')) !!}
                                            @endif
                                        {!! Form::select('user_practice', $practices, $user['practice_id'], array('class' => ' add_user_input', 'placeholder' => 'Select Practice*', 'id' => 'user_practice', 'data-toggle' => 'tooltip', 'title' => 'User Practice', 'data-placement' => 'right', 'style' => ($user['practice_id'] == '') ? 'display:none' : "display:inline-block")) !!}
                                        @elseif(session('user-level') == 2)
                                            {!! Form::select('user_practice', $practices, $user['practice_id'], array('class' => ' add_user_input', 'placeholder' => 'Select Practice*', 'id' => 'user_practice', 'data-toggle' => 'tooltip', 'title' => 'User Practice', 'data-placement' => 'right', 'style' => ($user['practice_id'] == '') ? 'display:none' : "display:inline-block")) !!}
                                        @else
                                        {!! Form::hidden('user_practice', $user['practice_id'], array('id' => 'user_practice')) !!}
                                        @endif

                                        {!! Form::select('landing_page', $menuoption, $user['menu_id'], array('class' => 'add_user_input', 'placeholder' => 'Select Landing Page', 'id' => 'landing_page' , 'data-toggle' => 'tooltip', 'title' => 'Landing Page', 'data-placement' => 'right')) !!}

                                    </div>
                                    <div class="col-xs-12 col-sm-6" style="color:#fff;">
                                       <h4>Roles*</h4>
                                        @foreach($roles as $key => $role)
                                        @if(isset($user[$role])) {!! Form::checkbox('role[]', $role, true, array('id' => $key, 'class' => 'user_roles')); !!}
                                        @else {!! Form::checkbox('role[]', $role, null, array('id' => $key, 'class' => 'user_roles')); !!}
                                        @endif {!! Form::label($role, $role); !!}
                                        <br> @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <span class="popover_text" data-content="">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
        General Information</a>
                                </span>
      </h4>
                        </div>
                        <div id="collapse2" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6">
                                        {!! Form::text('title', $user['title'], array('class' => ' add_user_input', 'placeholder' => 'Title', 'id' => 'title' , 'data-toggle' => 'tooltip', 'title' => 'Title', 'data-placement' => 'right')) !!}
                                        {!! Form::text('firstname', $user['firstname'], array('class' => ' add_user_input', 'placeholder' => 'First Name*', 'id' => 'first_name', 'required' => 'required', 'data-toggle' => 'tooltip', 'title' => 'First Name', 'data-placement' => 'right', 'maxlength' => '50')) !!}
                                        {!! Form::text('middlename', $user['middlename'], array('class' => ' add_user_input', 'placeholder' => 'Middle Name', 'id' => 'middle_name', 'data-toggle' => 'tooltip', 'title' => 'Middle Name', 'data-placement' => 'right', 'maxlength' => '50')) !!}
                                        {!! Form::text('lastname', $user['lastname'], array('class' => ' add_user_input', 'placeholder' => 'Last Name*', 'id' => 'last_name', 'required' => 'required', 'data-toggle' => 'tooltip', 'title' => 'Last Name', 'data-placement' => 'right', 'maxlength' => '50')) !!}
                                        {!! Form::text('npi', $user['npi'], array('class' => ' add_user_input', 'placeholder' => 'NPI', 'id' => 'npi', 'data-toggle' => 'tooltip', 'title' => 'NPI', 'data-placement' => 'right')) !!}
                                        {!! Form::text('acc_key', $user['acc_key'], array('class' => ' add_user_input', 'placeholder' => '4PC Account Key', 'id' => 'acc_key', 'data-toggle' => 'tooltip', 'title' => '4PC Account Key', 'data-placement' => 'right')) !!}
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        {!! Form::text('cellphone', $user['cellphone'], array('class' => ' add_user_input', 'placeholder' => 'Phone Number', 'id' => 'cell_phone', 'data-toggle' => 'tooltip', 'title' => 'Cellphone', 'data-placement' => 'right', 'pattern' => '[^A-Za-z]+', 'maxlength' => '20')) !!}
                                        {!! Form::text('address1', $user['address1'], array('class' => ' add_user_input', 'placeholder' => 'Address 1', 'id' => 'address1', 'data-toggle' => 'tooltip', 'title' => 'Address Line 1', 'data-placement' => 'right')) !!}
                                        {!! Form::text('address2', $user['address2'], array('class' => ' add_user_input', 'placeholder' => 'Address 2', 'id' => 'address2', 'data-toggle' => 'tooltip', 'title' => 'Address Line 2', 'data-placement' => 'right')) !!}
                                        {!! Form::text('city', $user['city'], array('class' => ' add_user_input', 'placeholder' => 'City', 'id' => 'city', 'data-toggle' => 'tooltip', 'title' => 'City', 'data-placement' => 'right')) !!}
                                        {!! Form::text('zip', $user['zip'], array('class' => ' add_user_input', 'placeholder' => 'Zip', 'id' => 'zip', 'data-toggle' => 'tooltip', 'title' => 'Zip', 'data-placement' => 'right')) !!}
                                        {!! Form::text('speciality', $user['speciality'], array('class' => ' add_user_input', 'placeholder' => 'Specialty', 'id' => 'speciality', 'data-toggle' => 'tooltip', 'title' => 'Zip', 'data-placement' => 'right')) !!}
                                        <span class="hide">
                                        <input type="checkbox" name="two_factor_auth" id="two_factor_auth" style="margin:1em" @if($user['two_factor_auth'] == true) checked @endif>
                                        <label for="two_factor_auth" style="margin:1em 0em;color: #fff;">Enable Two Factor Authentication</label>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" >
                            <h4 class="panel-title">
                                <span class="popover_text" data-content="">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
            Direct Address and Password</a>
                                </span>
      </h4>
                        </div>
                        <div id="collapse3" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6">
                                        {!! Form::email('email', $user['email'], array('class' => 'add_user_input user_email_field','required' => 'required', 'placeholder' => 'Email*', 'id' => 'email', 'data-toggle' => 'tooltip', 'title' => 'Email', 'data-placement' => 'right')) !!}
                                        {!! Form::email('sesemail', $user['sesemail'], array('class' => 'add_user_input user_email_field', 'placeholder' => 'SES Email', 'id' => 'ses_email', 'data-toggle' => 'tooltip', 'title' => 'SES Email', 'data-placement' => 'right')) !!}
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        {!! Form::password('password', array('class' => ' add_user_input', 'placeholder' => 'Password', 'id' => 'password', 'data-toggle' => 'tooltip', 'title' => 'Password', 'data-placement' => 'right', $user['password_required'] => $user['password_required'])) !!}
                                        {!! Form::password('password_confirmation', array('class' => ' add_user_input', 'placeholder' => 'Password Confirmation', 'id' => 'confirm_password', 'data-toggle' => 'tooltip', 'title' => 'Confirm Password', 'data-placement' => 'right')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 no-padding add_user_footer">
                    {!! Form::submit('Save', array('class' => 'btn add_user_submit_button')) !!}
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
