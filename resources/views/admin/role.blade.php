@extends('layouts.master')

@section('title', 'My Ocuhub - Administration')

@section('content')
    <div class="content-section active" id="admin-role-console">

        <div class="admin-console-section active" id="admin-view-role">
            @include('admin.roles.listing')
        </div>

        <div class="admin-console-section" id="admin-create-role">
             @include('admin.roles.create')
             @include('admin.roles.permissions')
         </div>
     </div>
@endsection
