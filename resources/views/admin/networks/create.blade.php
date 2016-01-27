@extends('layouts.master')

@section('title', 'My Ocuhub - Add Network')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/networks.css')}}">
<script type="text/javascript" src="{{elixir('js/networks.js')}}"></script>
@endsection

@section('sidebar')
    @include('admin.sidebar')
@endsection

@section('content')

@if (Session::has('success'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>
            <i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
        </strong> {{ Session::get('success') }}
</div>
@endif

<div class="content-section active">
   <div class="row content-row-margin add_header">
       <div><a href="/administration/networks"><button type="button" class="btn back-btn">Back</button></a></div>
    <div><p class="add_title">Add New Network</p></div>
</div>
{!! Form::open(array('url' => '/administration/network/add', 'method' => 'POST', 'id' => 'form_add_networks')) !!}
<div class="row content-row-margin">
    <div class="col-sm-6 col-xs-12">
        {!! Form::text('Name', old('name'), array('class' => 'add_network_input', 'required' => 'required', 'name' => 'name', 'placeholder' => 'Network Name', 'id' => 'name')) !!}
        {!! Form::email('email', old('email'), array('class' => 'add_network_input', 'name' => 'email', 'placeholder' => 'Email', 'id' => 'email')) !!}
        {!! Form::text('Phone', old('phone'), array('class' => 'add_network_input', 'name' => 'phone', 'placeholder' => 'Phone', 'id' => 'phone')) !!}
    </div>
</div>
<div class="section-break"></div>
   <div class="row content-row-margin">
    <div class="col-sm-6 col-xs-12">
        {!! Form::text('address_1', old('Address1'), array('class' => 'add_network_input', 'name' => 'address_1', 'placeholder' => 'Address Line 1', 'id' => 'Address_1')) !!}
        {!! Form::text('address_2', old('Address2'), array('class' => 'add_network_input', 'name' => 'address_2', 'placeholder' => 'Address Line 2', 'id' => 'Address_2')) !!}
        {!! Form::text('City', old('city'), array('class' => 'add_network_input', 'name' => 'city', 'placeholder' => 'City', 'id' => 'city')) !!}
    </div>
    <div class="col-sm-6 col-xs-12">
        {!! Form::text('Zip', old('zip'), array('class' => 'add_network_input', 'name' => 'zip', 'placeholder' => 'ZIP', 'id' => 'zip')) !!}
        {!! Form::text('state', old('state'), array('class' => 'add_network_input', 'name' => 'state', 'placeholder' => 'State', 'id' => 'state')) !!}
        {!! Form::text('country', old('country'), array('class' => 'add_network_input', 'name' => 'country', 'placeholder' => 'Country', 'id' => 'country')) !!}

    </div>
</div>
     <div class="row content-row-margin">
     {!! Form::submit('Add Network', array('class' => 'btn btn-default btn-primary')) !!}
</div>



 {!! Form::close() !!}
</div>

@endsection
