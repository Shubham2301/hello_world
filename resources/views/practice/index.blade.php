@extends('layouts.master')

@section('title', 'My Ocuhub - Select Provider')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{asset('css/provider.css')}}">
<script type="text/javascript" src="{{asset('js/provider.js')}}"></script>
@endsection

@section('content')

	@if (Session::has('success'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>
            <i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
        </strong>
        {{ Session::get('success') }}
    </div>
    @endif

    <div class="content-section active">
        <div class="practice_section active">
            @include('practice.search')
        </div>
        <div class="practice_section active">
            @include('practice.listing')
        </div>
    </div>
@endsection
