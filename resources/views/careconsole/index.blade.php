@extends('layouts.master') @section('title', 'Care Console') @section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/careconsole.css')}}">
<script type="text/javascript" src="{{elixir('js/careconsole.js')}}"></script>
<script type="text/javascript" src="{{elixir('js/import.js')}}"></script>
@endsection @section('sidebar') @include('careconsole.sidebar') @endsection @section('content')
<div class="content-row-margin">
	<div class="row before_drilldown">
		<div class="col-xs-12">
			<div class="section-header">
				@can('bulk-import')
				<button type="button" class="btn import-btn open_import" data-toggle="modal" data-target="#importModal" id="import_patients">Import</button>
				@endcan
				<span class="overview_header">Overview</span>
				<div class="search" id="care_console_search">
					<input type="text" id="search_data">
					<span class="glyphicon glyphicon-chevron-left active" aria-hidden="true" id="search_bar_open"></span>
					<span class="glyphicon glyphicon-search" aria-hidden="true" id="search_do"></span>

					<div class="search_result">
						<div class="search_result_row row" data-index="">
							<div class="col-xs-1">
								<div class="circle" id="" style="background-color:green"></div>
							</div>
							<div class="col-xs-11 search_result_row_text">
								<p class="result_title result_name">Allen Rovenstine</p>
								<p class="result_title scheduled_name"><strong>Scheduled-to&nbsp;&nbsp;</strong>Daniel Garibaldi</p>
							</div>
						</div>
						<div class="search_result_row row" data-index="">
							<div class="col-xs-1">
								<div class="circle" id="" style="background-color:green"></div>
							</div>
							<div class="col-xs-11 search_result_row_text">
								<p class="result_title result_name">Allen Rovenstine</p>
								<p class="result_title scheduled_name"><strong>Contact stage</p>
							</div>
						</div>
					</div>

					<div class="search_result_info">
						<div class="search_result_row row">
							<div class="col-xs-1">
								<div class="circle" id="status_color"></div>
							</div>
							<div class="col-xs-11 search_result_row_text">
								<p class="result_title stage_name"></p>
							</div>
						</div>
						<div class="search_result_row row">
							<div class="col-xs-1">

							</div>
							<div class="col-xs-11 search_result_row_text">
								<p class="result_title searchfield_1"></p>
								<p class="result_text searchfield_1"></p>
							</div>
						</div>
						<div class="search_result_row row">
							<div class="col-xs-1">

							</div>
							<div class="col-xs-11 search_result_row_text">
								<p class="result_title searchfield_2"></p>
								<p class="result_text searchfield_2"></p>
							</div>
						</div>
						<div class="search_result_row row">
							<div class="col-xs-1">

							</div>
							<div class="col-xs-11 search_result_row_text">
								<p class="result_title searchfield_3"></p>
								<p class="result_text searchfield_3"></p>
							</div>
						</div>

						<div class="search_result_row row">
							<div class="col-xs-3"></div>
							<div class="col-xs-9  search_result_row_text">
								<div class="dropdown" style="cursor: pointer;">
									<p class="result_title" data-toggle="dropdown">Take action
										<span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
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

	@include('careconsole.patientinfo')
	@include('careconsole.overview')
	@include('careconsole.drilldown')
	@include('careconsole.actions')
</div>
@endsection
