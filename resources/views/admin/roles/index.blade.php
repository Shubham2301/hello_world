@extends('layouts.master')

@section('title', 'My Ocuhub - Administration')

@section('sidebar')
    @include('admin.sidebar')
@endsection

@section('content')

	@if (Session::has('success'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>
            <i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
        </strong>
		{{ Session::pull('success') }}
    </div>
    @endif

    <div class="content-section active" id="admin-role-console">

        <div class="admin-console-section active" id="admin-view-role">
            @include('admin.roles.listing')
        </div>
     </div>
@endsection
