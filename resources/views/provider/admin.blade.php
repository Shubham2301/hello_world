@extends('layouts.master')

@section('title', 'My Ocuhub - Administration')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/provider.css')}}">
<script type="text/javascript" src="{{elixir('js/provider.js')}}"></script>
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

<div class="content-section active">

</div>
@endsection
