@extends('layouts.sidebar-mini')
@section('siderbar-title')
Reporting
@endsection
@section('sidebar-content')
<div class="sidebar_item_list">
    @if(policy(new \myocuhub\Http\Controllers\Reports\ReportController)->accessCareconsoleReport())
    <a href="/report/careconsole_reports?type=real_time" class="report_sidebar_button sidebar_realtime realtime_header {{ array_key_exists('real_time', $data) ? 'active' : '' }}">
        <span class="report_sidebar_image">
          <span>
            <img src="{{elixir('images/sidebar/real-time-icon.png')}}">
          </span>
        </span>
        <span class="report_sidebar_text">
          <span class="text">Real Time</span>
        </span>
    </a>
  <div class="expandable_sidebar" id="population_report_options">
  </div>
  @endif
  @if(policy(new \myocuhub\Http\Controllers\Reports\ReportController)->accessCareconsoleReport())
  <a href="/report/careconsole_reports?type=historical" class="report_sidebar_button sidebar_historical historical_header {{ array_key_exists('historical', $data) ? 'active' : '' }}">
    <span class="report_sidebar_image">
      <span><img src="{{elixir('images/sidebar/historical-icon.png')}}"></span>
    </span>
    <span class="report_sidebar_text">
      <span class="text">Historical</span>
    </span>
  </a>
  @endif
  @if(policy(new \myocuhub\Http\Controllers\Reports\ReportController)->accessReachReport())
  <a href="/report/reach_report" class="report_sidebar_button {{ array_key_exists('reach_report', $data) ? 'active' : '' }}">
    <span class="report_sidebar_image">
      <span><img src="{{elixir('images/sidebar/reports-reachrate.png')}}"></span>
    </span>
    <span class="report_sidebar_text">
      <span class="text">Reach Report</span>
    </span>
  </a>
  @endif
  @if(policy(new \myocuhub\Http\Controllers\Reports\ReportController)->accessPerformanceReport())
  <a href="/report/performance" class="report_sidebar_button {{ array_key_exists('performance-report', $data) ? 'active' : '' }}">
    <span class="report_sidebar_image">
      <span><img src="{{elixir('images/sidebar/reports-reachrate.png')}}"></span>
    </span>
    <span class="report_sidebar_text">
      <span class="text">Performance Report</span>
    </span>
  </a>
  @endif
</div>
@endsection
