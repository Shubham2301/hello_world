@extends('layouts.master')

@section('title', 'Care Console')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{asset('css/careconsole.css')}}">
<script type="text/javascript" src="{{asset('js/careconsole.js')}}"></script>
@endsection

@section('content')

 <div class="content-row-margin">
<div class="row">
    <div class="col-xs-12">
        <div class="section-header">
            Overview
            <div class="search" id="care_console_search">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
        </div>
    </div>
</div>

@include('careconsole.overview')

</div>
@endsection
