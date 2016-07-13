@extends('layouts.master') @section('title', 'My Ocuhub - Reach Rate Report') @section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/reports.css')}}">
<script type="text/javascript" src="{{elixir('js/reports.js')}}"></script>
@endsection @section('sidebar') @include('reports.sidebar') @endsection @section('content') @if (Session::has('success'))
<div class="alert alert-success" id="flash-message">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>
            <i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
        </strong> {{ Session::pull('success') }}
</div>
@endif
@endsection
@section('mobile_sidebar_content')
@include('reports.sidebar')
@endsection
