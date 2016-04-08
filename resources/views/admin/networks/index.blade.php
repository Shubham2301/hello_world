@extends('layouts.master')

@section('title', 'My Ocuhub - Network Management')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/networks.css')}}">
<script type="text/javascript" src="{{elixir('js/networks.js')}}"></script>
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
        </strong> {{ Session::pull('success') }}
</div>
@endif

<div class="content-section active side_padding">
    @include('admin.networks.search')
</div>

<div class="network_section active auto_scroll side_padding" id="network_listing">
    @include('admin.networks.listing')
</div>

@endsection
