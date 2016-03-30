@extends('layouts.master')

@section('title', 'My Ocuhub - Select Patients')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/reporting.css')}}">
<script type="text/javascript" src="{{elixir('js/reporting.js')}}"></script>
@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')

	@if (Session::has('success'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>
            <i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
        </strong>
        {{ Session::pull('success') }}
    </div>
    @endif

@endsection
