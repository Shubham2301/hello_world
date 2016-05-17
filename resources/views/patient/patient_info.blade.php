<div class="col-xs-12">
	<div class="col-xs-4 center-align">
	<sapn class="patient_section" style="display:none">
		<img src="{{asset('images/patient.png')}}" alt="">
		<br> @can('edit-patient')
		<p class="edit_patient_button">Edit Patient</p>
		@endcan
		<p class="button_type_1" id="change_patient_button">Change Patient</p>
		<br>
		{!! Form::open(array('url' => 'import/ccda', 'method' => 'POST', 'files'=>true,'id'=>'import_ccda_form')) !!} {!! Form::hidden('patient_id', '', array('id' => 'ccda_patient_id')) !!}

		<div class="row input_row">
			<div class="col-md-3 form-group">
			</div>
			<div class="col-md-7 ">
				<span class="file-input">Upload Patient File{!!Form::file('patient_ccda')!!}
				</span>
				<span class="filename"></span>

			</div>
			<div class="col-md-2"></div>
		</div>

		{!! Form::close() !!}

		<p class="button_type_1" style="display:none;" id="compare_ccda_button" data-toggle="modal" data-target="#compareCcda">update CCDA</p>
	</sapn>
	<span class="provider_section" style="display:none">
		<img src="{{asset('images/patient.png')}}" alt=""><br>
	</span>
	</div>
	<div class="col-xs-8">
		<p class="bold arial_bold" id="patient_name"></p>
		<hr class="underline">
		<div class="row">
			<div class="col-xs-6 space_below">
				<p><span class="bold arial_bold">Email</span>
					<br><span class="patient_detail_info" id="patient_email"> </span></p>
				<p><span class="bold arial_bold">Date of Birth</span>
					<br><span class="patient_detail_info" id="patient_dob"> </span></p>
			</div>
			<div class="col-xs-6">
				<p><span class="bold arial_bold">Address</span>
					<br><span class="patient_detail_info" id="patient_add1"></span>
					<br><span class="patient_detail_info" id="patient_add2"></span>
					<br><span class="patient_detail_info" id="patient_add3"></span></p>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-6">
				<p><span class="bold arial_bold">Phone</span>
					<br><span class="patient_detail_info" id="patient_phone">  </span></p>
			</div>
			<div class="col-xs-6">
				<p><span class="bold arial_bold">SSN</span>
					<br><span class="patient_detail_info" id="patient_ssn"></span> </p>
			</div>
		</div>

	</div>
</div>

	<div class="col-xs-12 patient_table_header">
		<div class="col-xs-3 lastseenby no-padding">
			<div class="lastseenby_show arial">
				<span><span>Last seen by</span>&nbsp;<span class="glyphicon glyphicon-chevron-right lastseenby_icon"></span></span>
			</div>

		</div>

		<div class="col-xs-3 referredby no-padding">
			<div class="referredby_show arial">
				<span> <span>Last referred by</span>&nbsp;<span class="referredby_icon "></span></span>
			</div>
		</div>

		<div class="col-xs-3 insurance_provider no-padding">
			<div class="insurance_provider_show arial">
				<span><span>Insurance provider</span>&nbsp;<span class="glyphicon glyphicon-chevron-right insurance_provider_icon"></span></span>
			</div>
		</div>
		<div class="col-xs-3 patient_files no-padding">
			<div class="patient_files_show arial">
				<span><span>Files and Images</span>&nbsp;<span class="glyphicon glyphicon-chevron-right patient_files_icon"></span></span>
			</div>
		</div>
	</div>

	<div class="col-xs-12 no-padding">
		<div class="col-xs-3 no-padding">
			<div class="lastseen_content">
			</div>
		</div>
		<div class="col-xs-3 no-padding">
			<div class="referredby_content">
				<div><a data-toggle="modal" data-target="#referredby_details" id="referred_by_details_btn" class="button_type_1"> Add Details</a></div>
			</div>
		</div>
		<div class="col-xs-3 no-padding">
			<div class="insurance_provider_content">
			</div>
		</div>
		<div class="col-xs-3 no-padding">
			<div class="patient_files_content">
				<!--
<div class="patient_file_item row">
<div class="col-xs-6">
<p class="file_name" style="font-size:11px; margin-top:3px;">CCDA-24-12-2016</p>
</div>
<div class="col-xs-2">
<a href="" class="view_file" >View</a></div>
<div class="col-xs-4">
<a href="" class="download_file">Download</a></div>
</div>
-->
			</div>
		</div>
	</div>

