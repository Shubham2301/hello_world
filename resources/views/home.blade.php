@extends('layouts.master')

@section('title', 'My Ocuhub')

@section('content')
    @if(Auth::check())
        @include('layouts.admin-console')
        @include('layouts.payer-console')
        @include('layouts.care-console')
    @endif    
@endsection