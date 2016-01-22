@extends('layouts.master')

@section('title', 'Privacy Policy')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{asset('css/referral.css')}}">
<script type="text/javascript" src="{{asset('js/referraltype.js')}}"></script>
@endsection

@section('sidebar')
@include('layouts.sidebar')
@endsection

@section('content')


<div class="content-section active" id="referral_types">

    <div class="row content-row-margin">
        <div class="col-xs-12 section-header">
            <span class="">Privacy Policy</span>
            <br>
            <p style="margin-top:5em;font-size:xx-large;"><strong>Coming Soon..</strong></p>
        </div>
    </div>

</div>

@endsection
