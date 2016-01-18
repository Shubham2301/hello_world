
@extends('layouts.master')

@section('title', 'My Ocuhub - Select Provider')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{asset('css/provider.css')}}">
<script type="text/javascript" src="{{asset('js/provider.js')}}"></script>
@endsection


@section('content')
   <h3>Select a CCDA file</h3>
    {!! Form::open(array('url' => '/saveccd', 'method' => 'post','files'=>true)) !!}
    {!!Form::file('ccda')!!}
    {!!Form::submit('save')!!}
    {!! Form::close() !!}
@endsection
