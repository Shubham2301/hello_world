@extends('layouts.master')

@section('title', 'My Ocuhub')

@section('content')
    @include('layouts.admin-console')
    @include('layouts.payer-console')
    @include('layouts.care-console')
@endsection