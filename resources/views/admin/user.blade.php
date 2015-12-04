@extends('layouts.master')

@section('title', 'My Ocuhub - Administration')

@section('content')
    @if(Auth::check())
        <div class="content-section active" id="admin-user-console">
            <div class="admin-console-section active" id="admin-show-user">
                @include('admin.users.listing')
            </div>
            <div class="admin-console-section" id="admin-create-user">
                @include('admin.users.create')
            </div>
        </div>
    @endif
@endsection
