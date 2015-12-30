@extends('layouts.master')

@section('title', 'My Ocuhub - Select Providers')

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

    <div class="content-section active" id="provider_section">
        <div class="provider_section active" id="select_provider">
            @if(array_key_exists('referraltype_id', $data) and array_key_exists('patient_id', $data))
            <div class="row content-row-margin">
                <div class="col-xs-12 section-header">
                    <span class="">Schedule an appointment</span>
                </div>
            </div>
            <div class="row content-row-margin">
                <div class="col-xs-12 subsection-header">
                    <span>1. Select a provider</span>
                </div>
            </div>
            @endif
            @include('provider.search')
        </div>
        <div class="provider_section active" id="provider_listing">
            @include('provider.listing')
        </div>

    </div>


@endsection
