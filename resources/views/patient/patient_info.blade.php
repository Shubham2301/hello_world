<div class="col-xs-12">
	<div class="col-sm-4 col-xs-6 center-align">
	<span class="patient_section" style="display:none;">
		<img src="{{asset('images/patient.png')}}" alt="">
		<span class="show_in_patient" style="display:none;">
		<br>
		<p class="edit_patient_button">Edit Patient</p>
		<p class="button_type_1" id="change_patient_button">Change Patient</p>
		<br>
		</span>
		<span class="show_in_provider_patient" style="display:none;">
		{!! Form::open(array('url' => 'import/ccda', 'method' => 'POST', 'files'=>true,'id'=>'import_ccda_form')) !!} {!! Form::hidden('patient_id', '', array('id' => 'ccda_patient_id')) !!}
		<div class="row input_row">
			<div class="col-md-2 form-group">
			</div>
			<div class="col-md-8 ">
				<span class="upload_file_view_btn">Upload Patient File</span>
				<span class="filename"></span>

			</div>
			<div class="col-md-2"></div>
		</div>

		{!! Form::close() !!}
		</span>

		<p class="button_type_1" style="display:none;" id="compare_ccda_button" data-toggle="modal" data-target="#compareCcda">update CCDA</p>
	</span>
<!--
	<span class="provider_section" style="display:none">
		<img src="{{asset('images/patient.png')}}" alt=""><br>
	</span>
-->
	    <span class="patient_table_mobile show_mobile">
            <span class="patient_table_mobile_section">
               <span class="patient_table_header">
                    <span class="arial_bold">Last Seen By</span>
                    <span class="glyphicon glyphicon-chevron-down lastseenby_icon"></span>
                </span>
                <div class="lastseen_content patient_table_content">
			    </div>
            </span>
            <span class="patient_table_mobile_section">
                <span class="patient_table_header">
                    <span class="arial_bold">Last referred by</span>
                    <span class="glyphicon glyphicon-chevron-down referredby_icon"></span>
                </span>
                <div class="referredby_content patient_table_content">
			    </div>
            </span>
            <span class="patient_table_mobile_section">
                <span class="patient_table_header">
                    <span class="arial_bold">Insurance provider</span>
                   <span class="glyphicon glyphicon-chevron-down insurance_provider_icon"></span>
                </span>
                <div class="insurance_provider_content patient_table_content">
			    </div>
            </span><span class="patient_table_mobile_section">
                <span class="patient_table_header">
                    <span class="arial_bold">Files and Images</span>
                    <span class="glyphicon glyphicon-chevron-down patient_files_icon"></span>
                </span>
                <div class="patient_files_content patient_table_content">
			    </div>
            </span>
        </span>
	</div>
	<div class="col-sm-8 col-xs-6">
		<p class="bold arial_bold" id="patient_name"></p>
		<hr class="underline">
		<div class="row">
			<div class="col-sm-6 col-xs-12 space_below">
				<p><span class="bold arial_bold">Email</span>
					<br><span class="patient_detail_info" id="patient_email"> </span></p>
				<p><span class="bold arial_bold">Date of Birth</span>
					<br><span class="patient_detail_info" id="patient_dob"> </span></p>
			</div>
			<div class="col-sm-6 col-xs-12">
				<p><span class="bold arial_bold">Address</span>
					<br><span class="patient_detail_info" id="patient_add1"></span>
					<br class="hidden-xs"><span class="patient_detail_info" id="patient_add2"></span>
					<br class="hidden-xs"><span class="patient_detail_info" id="patient_add3"></span></p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 col-xs-12">
				<p><span class="bold arial_bold">Phone</span>
					<br><span class="patient_detail_info" id="patient_phone">  </span></p>
			</div>
			<div class="col-sm-6 col-xs-12">
				<p><span class="bold arial_bold">SSN</span>
					<br><span class="patient_detail_info" id="patient_ssn"></span> </p>
			</div>
		</div>

	</div>
</div>

	<div class="col-xs-12 patient_table_header hide_mobile">
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

	<div class="col-xs-12 no-padding hide_mobile">
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
			</div>
		</div>
	</div>
<div class="modal fade" id="referredby_details" role="dialog">
    <div class="modal-dialog alert">

        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color:black;margin-left:39%;">Referred By</h4>
            </div>
            <div class="modal-body">
                <div class="content-section active">
                    <div class="row content-row-margin">
						<div class="col-xs-3 form-group" style="padding-top: 5px;">
							<lable for="exampleInputName1"><strong style="padding-left:3em;color:black;"> Practice</strong></lable>
						</div>
                        <div class="col-xs-9">
                            {!! Form::text('referred_by_practice',null, array('class' => 'referredby_input referredby_practice', 'name' => 'referred_by_practice', 'id' => 'referred_by_practice', 'onkeyup'=>'referredByPracticeSuggestions(this.value)', 'autocomplete'=>'off')) !!}
                            <ul class="suggestion_list practice_suggestions">
                            </ul>
                        </div>
                    </div>
                    <div class="row content-row-margin">
						<div class="col-xs-3 form-group" style="padding-top: 5px;">
							<lable for="exampleInputName1"><strong style="padding-left:3em;color:black;"> Provider</strong></lable>
						</div>
                        <div class="col-xs-9">
							{!! Form::text('referred_by_provider',null , array('class' => 'referredby_input referredby_provider', 'name' => 'referred_by_provider', 'id' => 'referred_by_provider', 'onkeyup'=>'referredByProviderSuggestions(this.value)', 'autocomplete'=>'off')) !!}
                            <ul class="suggestion_list provider_suggestions">
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <div style="margin-right:33%">
                    <button type="button" class="btn save_referredby active">Save</button>
					<button type="button" class="btn btn-default dismiss_button" data-dismiss="modal" style="background-color:#d2d3d5">Cancel</button>
                </div>
            </div>
        </div>

    </div>
</div>

<input type="hidden" id="clear_image_path" value="{{URL::asset('images/close-active.png')}}">
<div id = "file_upload_view">
	
</div>

