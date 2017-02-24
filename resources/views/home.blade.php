@extends('layouts.master') @section('title', 'illuma') @section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/referral.css')}}">
<script type="text/javascript" src="{{elixir('js/referraltype.js')}}"></script>
@endsection
@section('sidebar')
@include('layouts.sidebar')
@endsection
@section('content')
<div class="content-section active" id="referral_types">
	@if (Session::has('failure'))
    <div class="alert alert-danger" id="flash-message">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>
            <i class="fa fa-check-circle fa-lg fa-fw"></i> Failure. &nbsp;
        </strong>
        {{ Session::pull('failure') }}
    </div>
    @endif
	<p class="message arial_bold">Select type of patient you are referring</p>
	<div class="row arial">
		{!! Form::open(array('url' => '/patients', 'method' => 'GET', 'id' => 'form_select_patient')) !!}
		{!! Form::hidden('referraltype_id', '0', array('id' => 'form_referraltype_id')) !!}
		{!! Form::hidden('action', 'schedule_appointment', array('id' => 'form_referraltype_id')) !!}
		{!! Form::close() !!}
		<div class="referral_tiles" id="referraltypes_list"></div>
	</div>
</div>
@endsection
@section('mobile_sidebar_content')
@include('layouts.sidebar')
@endsection
