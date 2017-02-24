@extends('layouts.master')

@section('title', 'Tech Support')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{asset('css/referral.css')}}">
@endsection

@section('sidebar')
@include('layouts.sidebar')
@endsection

@section('content')


<div class="content-section active" id="referral_types">

    <div class="row content-row-margin">
        <div class="col-xs-12 section-header">
            <span class="arial_bold">Contact illuma Support</span>
            <br>
            <span><p>
                <br>
                <br>
                Need help? Save time by starting your support request via email and we will have an expert contact within 24 hours.
                <br>
                <br>
                <span class="arial_bold">Support Options</span><br>
                Email- {{ config('constants.support.email_id') }}<br>
                Call- {{ config('constants.support.phone') }}
                </p>
            </span>
        </div>
    </div>

</div>

@endsection
@section('mobile_sidebar_content')
@include('layouts.sidebar')
@endsection
