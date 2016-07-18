@extends('layouts.sidebar-mini')
@section('siderbar-title')
Reporting
@endsection
@section('sidebar-content')
<div class="sidebar_item_list">
  <span class="report_sidebar_button sidebar_realtime realtime_header active">
    <span class="report_sidebar_image">
      <span>
        <img src="{{elixir('images/sidebar/real-time-icon.png')}}">
      </span>
    </span>
    <span class="report_sidebar_text">
      <span class="text">Real Time</span>
    </span>
  </span>
  <div class="expandable_sidebar active" id="population_report_options">
  </div>
  <span class="report_sidebar_button sidebar_historical historical_header">
    <span class="report_sidebar_image">
      <span><img src="{{elixir('images/sidebar/historical-icon.png')}}"></span>
    </span>
    <span class="report_sidebar_text">
      <span class="text">Historical</span>
    </span>
  </span>
</div>
@endsection