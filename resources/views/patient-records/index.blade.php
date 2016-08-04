@extends('layouts.master') @section('title', 'My Ocuhub - Web Forms') @section('imports')

<link rel="stylesheet" type="text/css" href="{{elixir('css/patient_records.css')}}">
<script type="text/javascript" src="{{elixir('js/patient_records.js')}}"></script>
@section('sidebar')
@include('patient-records.sidebar')
@endsection @endsection
@section('content')
@include('patient-records.header')

<div class="row listing_section active">
    <div class="col-xs-5">
        <div  class="patient_listing_section">
        </div>
    </div>
    <div class="col-xs-7 care_timeline">
    </div>

</div>

@endsection
