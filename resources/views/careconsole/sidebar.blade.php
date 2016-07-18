@extends('layouts.sidebar-mini')
@section('siderbar-title')
Care Console
@endsection
@section('siderbar-subtitle')
<div class="c3_overview_link arial_bold"><img src="{{URL::asset('images/overview_icon.png')}}"><p>Overview</p></div>
@endsection
@section('sidebar-content')
<ul class="c3_sidebar_list sidebar_item_list">
    @foreach($overview['stages'] as $stage)
    <li class="sidebar_menu_item" data-id="{{ $stage['id'] }}">
        <div class="stage box" id="sidebar_{{ $stage['name'] }}" data-id="{{ $stage['id'] }}" data-name="{{ $stage['display_name'] }}"><span style="background-color:{{ $stage['color_indicator'] }}"><p class="stage-notation">{{ $stage['abbr'] }}</p></span><p>{{ $stage['display_name'] }}</p></div>
    </li>
    @endforeach
</ul>
<div class="C3_day_row console_bucket_row">
    <div class="console_buckets"  data-name="recall" style="color:black">
        <img src="{{elixir('images/sidebar/recall-icon.png')}}" alt="">
        <p>Recall</p>
    </div>
    <div class=" console_buckets" data-name="archived" style="color:black">
        <img src="{{elixir('images/sidebar/archive-icon.png')}}" alt="">
        <p>Archive</p>
    </div>
    <div class=" console_buckets"  data-name="priority" style="color:black">
        <img src="{{elixir('images/sidebar/priority-icon.png')}}" alt="">
        <p>Priority</p>
    </div>
</div>
<div class="control_section"></div>
@endsection