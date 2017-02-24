
@extends('layouts.master')

@section('title', 'illuma - Show Vitals')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{asset('css/provider.css')}}">
<script type="text/javascript" src="{{asset('js/provider.js')}}"></script>
@endsection


@section('content')
<h1>Vitals</h1>
<div style="color:black;">
    <a href="/addvital/{{$id}}" class="btn btn-large "><i class="icon-download-alt"> </i> Add New Vitals</a>
</div>

<div style="color:black;">
    <a href="/download/{{$id}}" class="btn btn-large "><i class="icon-download-alt"> </i> Download CCDA</a>
</div>
<div class="row" style="color:black;border-bottom:2px solid #fff">
    <div class="col-md-2"><strong>Date</strong></div>
    <div class="col-md-2"><strong>Name</strong></div>
    <div class="col-md-2"><strong>code</strong></div>
    <div class="col-md-3"><strong>code system</strong></div>
    <div class="col-md-1"><strong>code system name </strong></div>
    <div class="col-md-1"><strong>value</strong></div>
    <div class="col-md-1"><strong>unit</strong></div>
</div>

@foreach ($vitals as $vital)

<div class="row" style="margin-top:2em;">
    <div class="col-md-2">{{$vital->v_date}}</div>
    <div class="col-md-2">{{$vital->name}}</div>
    <div class="col-md-2">{{$vital->code}}</div>
    <div class="col-md-3">{{$vital->code_system}}</div>
    <div class="col-md-1">c{{$vital->code_system_name}} </div>
    <div class="col-md-1">{{$vital->value}}</div>
    <div class="col-md-1">{{$vital->unit}}</div>
</div>

@endforeach




@endsection
