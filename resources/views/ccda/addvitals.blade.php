
@extends('layouts.master')

@section('title', 'illuma - Select Provider')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{asset('css/provider.css')}}">
<script type="text/javascript" src="{{asset('js/provider.js')}}"></script>
@endsection


@section('content')
<h1>Add VitalSigns</h1>

<div style="color:black;">
    {!! Form::open(array('url' => '/savevitals', 'method' => 'Post', )) !!}
    <input type="hidden" name="ccda_id" value="{{$id}}">
    <p>Date</p>         {!! Form::date('v_date')  !!}
    <p>name</p>         {!! Form::text('v_name')  !!}
    <p>value</p>        {!! Form::text('v_value') !!}
    <p>unit</p>         {!! Form::text('v_unit')  !!}
    {!! Form::submit('save') !!}
    {!! Form::close() !!}
</div>

<div style="color:black;">
<a href="/download/{{$id}}" class="btn btn-large pull-right"><i class="icon-download-alt"> </i> Download file</a>
</div>

@endsection
