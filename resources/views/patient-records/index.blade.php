@extends('layouts.master') @section('title', 'My Ocuhub - Web Forms') @section('imports')

<link rel="stylesheet" type="text/css" href="{{elixir('css/users.css')}}">
<link rel="stylesheet" type="text/css" href="{{elixir('css/web_forms.css')}}">

<script type="text/javascript" src="{{elixir('js/web_forms.js')}}"></script>
@section('sidebar') @include('patient-records.sidebar') @endsection @endsection @section('content')
<div style="padding:2em;">

 <h1>Patient Records</h1>


</div>
@endsection
