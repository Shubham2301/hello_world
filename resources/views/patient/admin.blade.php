@extends('layouts.master')

@section('title', 'My Ocuhub - Administration')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/patient.css')}}">
<script type="text/javascript" src="{{elixir('js/patient.js')}}"></script>
<script type="text/javascript" src="{{elixir('js/import.js')}}"></script>
@endsection

@section('sidebar')
    @include('admin.sidebar')
@endsection

@section('content')

@if (Session::has('success'))
<div class="alert alert-success" id="flash-message">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>
            <i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
        </strong> {{ Session::pull('success') }}

</div>
@endif
<input type="hidden" id="from_admin" value="{{$data['admin']}}" >
@include('patient.compare_ccda')
@if(! empty($data['referraltype_id']))
@include('patient.create')
@else
@include('patient.search')
@include('patient.listing')
@endif
@endsection
@section('mobile_sidebar_content')
@include('admin.sidebar')
@endsection
