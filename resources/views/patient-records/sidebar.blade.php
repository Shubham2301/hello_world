@extends('layouts.sidebar-mini')
@section('siderbar-title')
Patient Records
@endsection
@section('sidebar-content')

<div class="sidebar_item_list">
    <a class="record_sidebar_button patient_record" href="/records">
        <span class="record_sidebar_image">
            <span>
                <img src="{{elixir('images/sidebar/records-patient.png')}}">
            </span>
        </span>
        <span class="record_sidebar_text">
            <span class="text">Patient Records</span>
        </span>
    </a>
    <a class="record_sidebar_button health_record" href="/webform">
        <span class="record_sidebar_image">
            <span><img src="{{elixir('images/sidebar/records-health.png')}}"></span>
        </span>
        <span class="record_sidebar_text">
            <span class="text">Health Records</span>
        </span>
    </a>
</div>
@endsection
