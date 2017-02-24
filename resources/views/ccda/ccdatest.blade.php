
@extends('layouts.master')

@section('title', 'illuma - Select Provider')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{asset('css/provider.css')}}">
<script type="text/javascript" src="{{asset('js/provider.js')}}"></script>
@endsection


@section('content')


@if(Session::has('error'))

<h3 style="color:red">{{ Session::pull('error', 'default')}}</h3>
 @endif


   <h3>Select a CCDA file</h3>
{!! Form::open(array('url' => 'import/ccda', 'method' => 'post','files'=>true)) !!}
    <div style="display:flex;color:black;">
        <p style="margin-right:2em;">Patient id </p> {!!Form::text('patient_id')!!}
    </div>
    <div style="display:flex;margin-top:2em;margin-bottom:2em">
        <p style="margin-right:2em;"><strong>CCDA file</strong></p> {!!Form::file('ccda')!!}
    </div>
    {!!Form::submit('save')!!}
    {!! Form::close() !!}
@endsection
