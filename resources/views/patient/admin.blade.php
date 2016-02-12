@extends('layouts.master')

@section('title', 'My Ocuhub - Administration')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/patient.css')}}">
<script type="text/javascript" src="{{elixir('js/patient.js')}}"></script>
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
<input type="hidden" id="from_admin" value="{{$data['admin']}}" >
@if(! empty($data['referraltype_id']))
@include('patient.create')
@endif
@include('patient.search')
@include('patient.listing')


@endsection
