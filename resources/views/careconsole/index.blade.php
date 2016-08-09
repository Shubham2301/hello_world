@extends('layouts.master')
@section('title', 'Care Console')
@section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/careconsole.css')}}">
<script type="text/javascript" src="{{elixir('js/careconsole.js')}}"></script>
<script type="text/javascript" src="{{elixir('js/import.js')}}"></script>
@endsection
@section('sidebar')
@include('careconsole.sidebar')
@endsection
@section('content')
<div class="content-row-margin">
	<div class="row before_drilldown">
		<div class="col-xs-12">
			<div class="section-header">
				@can('bulk-import')
				<button type="button" class="btn import-btn open_import" data-toggle="modal" data-target="#importModal" id="import_patients">Import</button>
				@endcan
				<span class="overview_header arial_bold">Overview</span>
				<div class="search" id="care_console_search">
					<input type="text" id="search_data"   data-toggle="tooltip" title="Search using patient name, phone number, SSN, address, email, country or Insurance Subscriber ID" data-placement="left">
					<span class="active" aria-hidden="true" id="search_bar_open">
						<img src="{{elixir('images/sidebar/left-natural.png')}}" class="left_natural active_img">
						<img src="{{elixir('images/sidebar/left-active.png')}}" class="left_active active_img">
						<img src="{{elixir('images/sidebar/right-natural.png')}}" class="right_natural non_active_img">
						<img src="{{elixir('images/sidebar/right-active.png')}}" class="right_active non_active_img">
					</span>
					<span class="" aria-hidden="true" id="search_do">
						<img src="{{elixir('images/sidebar/search-icon-schedule.png')}}" class="">
					</span>
					<div class="search_result">
						<div class="search_result_row row" data-index="">
							<div class="col-xs-1 search_color_col">
								<div class="circle" id="" style="background-color:green"></div>
							</div>
							<div class="col-xs-11 search_result_row_text">
								<p class="result_title arial_bold result_name"></p>
								<p class="result_title arial_bold scheduled_name"></p>
							</div>
						</div>
						<div class="search_result_row row" data-index="">
							<div class="col-xs-1 search_color_col">
								<div class="circle" id="" style="background-color:green"></div>
							</div>
							<div class="col-xs-11 search_result_row_text">
								<p class="result_title arial_bold result_name"></p>
								<p class="result_title arial_bold scheduled_name"></p>
							</div>
						</div>
					</div>
					<div class="search_result_info">
						<div class="search_result_row row">
							<div class="col-xs-1 search_color_col">
								<div class="circle" id="status_color"></div>
							</div>
							<div class="col-xs-11 search_result_row_text">
								<p class="result_title arial_bold stage_name"></p>
							</div>
						</div>
						<div class="search_result_row row">
							<div class="col-xs-1 search_color_col">
							</div>
							<div class="col-xs-11 search_result_row_text">
								<p class="result_title arial_bold searchfield_1"></p>
								<p class="result_text arial searchfield_1"></p>
							</div>
						</div>
						<div class="search_result_row row">
							<div class="col-xs-1 search_color_col">
							</div>
							<div class="col-xs-11 search_result_row_text">
								<p class="result_title arial_bold searchfield_2"></p>
								<p class="result_text arial searchfield_2"></p>
							</div>
						</div>
						<div class="search_result_row row">
							<div class="col-xs-1 search_color_col">
							</div>
							<div class="col-xs-11 search_result_row_text">
								<p class="result_title arial_bold searchfield_3"></p>
								<p class="result_text arial searchfield_3"></p>
							</div>
						</div>
						<div class="search_result_row row">
							<div class="col-xs-12 search_result_row_text take_action_col">
								<div class="dropdown" style="cursor: pointer;">
									<p class="result_title arial_bold" data-toggle="dropdown">Take action
										<img src="{{URL::asset('/images/dropdown-triangle.png')}}" class="">
									</p>
									<ul class="dropdown-menu" id="search_action_dropdown">
									</ul>
								</div>
								<p id='back_to_search'>back to search</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" id="recall_icon_path" value="{{elixir('images/sidebar/recall-icon-header.png')}}">
	<input type="hidden" id="archive_icon_path" value="{{elixir('images/sidebar/archive-icon-header.png')}}">
	<input type="hidden" id="priority_icon_path" value="{{elixir('images/sidebar/priority-icon-header.png')}}">
	@include('careconsole.patientinfo')
	@include('careconsole.overview')
	@include('careconsole.drilldown')
	@include('careconsole.actions')
</div>
@endsection
@section('mobile_sidebar_content')
@include('careconsole.sidebar')
@endsection
