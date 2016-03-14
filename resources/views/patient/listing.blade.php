<!-- TODO: Add pagination to patient list to avoid scrolling of elements -->
<div class="row content-row-margin patient_list">

    <p id="search_results" class="search_result"><strong></strong></p>
    <div class="row search_header">
        <div class="col-xs-3 search_name_header">
            <div>
                <input id="checked_all_patients" type="checkbox">&nbsp;&nbsp;</div>
            <div class="">
                <p style="color:black"><strong>Name</strong></p>
            </div>
        </div>
        <div class="col-xs-4">
            <p style="color:black"><strong>Address</strong></p>
        </div>
        <div class="col-xs-3">
            <p style="color:black"><strong>Email</strong></p>
        </div>
        <div class="col-xs-2">
            <input type="hidden" id="delete_practice_img" value="{{asset('images/delete-active-01.png')}}">
            <input type="hidden" id="schedule_patient_img" value="{{asset('images/schedule-icon-01.png')}}">
            <p class="" style="color:black"><span class="glyphicon glyphicon-chevron-left p_left" id="search_practice_button" aria-hidden="true"></span> <span class="page_info"><strong></strong></span><span class="glyphicon glyphicon-chevron-right p_right" id="search_practice_button" aria-hidden="true"></span></p>
        </div>
    </div>
        <form action="">
            <input type="hidden" id="assign_role_image_path" value="{{URL::asset('images/assign-role-icon-01.png')}}">
            <input type="hidden" id="assign_user_image_path" value="{{URL::asset('images/assign-user-icon-01.png')}}">
        </form>
    <div class="patient_search_content">


        <!--
        <div class="row search_item" data-id="11">
        <div class="col-xs-3" style="display:inline-flex">
            <div>
                <input type="checkbox">&nbsp;&nbsp;
            </div>
            <div class="search_name">
                <p> practice.name </p>
            </div>
        </div>
            <div class="col-xs-3">practice.address1<br>practice.address1 </div>
        <div class="col-xs-1"></div>
        <div class="col-xs-3">
            <p>practice.email </p>
        </div>
        <div class="col-xs-2 search_edit">
            <p>
                <div>
                    <span area-hidden="true" data-toggle="dropdown" class="dropdown-toggle">
                        <img class="action_dropdown_img" src="{{asset('images/dropdown-natural-01.png')}}" alt="">
                    </span>
                </div>
            </p>&nbsp;&nbsp;
            <p class="editPatient_from_row" data-toggle="modal" data-target="#create_practice">Edit</p>
            <div class="dropdown">
                <span area-hidden="true" area-hidden="true" data-toggle="dropdown" class="dropdown-toggle removepatient_from_row">
                <img src="{{asset('images/delete-active-01.png')}}" alt="" class="removepatient_img">
                </span>
                <ul class="dropdown-menu" id="row_remove_dropdown">
                    <li class="confirm_text">
                        <p><strong>Do you really want to delete this?</strong></p>
                    </li>
                    <li class="confirm_buttons">
                        <button type="button" class="btn btn-info btn-lg confirm_yes"> Yes</button>
                        <button type="button" class="btn btn-info btn-lg confirm_no">NO</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
       -->



    </div>
</div>
<div class="row content-row-margin no_item_found">
    <p>No results found matching :</p>
    <p></p>
</div>
<div class="row content-row-margin patient_info arial" data-id="">
    <div class="col-xs-12">
        <div class="col-xs-4 center-align">
            <img src="{{asset('images/patient.png')}}" alt="">
            <br>
            <p class="button_type_1" id="change_patient_button">Change Patient</p>
            <br>

			{!! Form::open(array('url' => 'import/ccda', 'method' => 'POST', 'files'=>true,'id'=>'import_ccda_form')) !!}
			{!! Form::hidden('patient_id', '', array('id' => 'ccda_patient_id')) !!}

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
        </div>
        <div class="col-xs-8">
            <p class="bold arial_bold" id="patient_name"></p>
            <div class="row">
                <div class="col-xs-6">
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
        <div class="col-xs-4 lastseenby">
            <div class="lastseenby_show arial_bold">
                <p><span>Last seen by</span>&nbsp;<span class="glyphicon glyphicon-chevron-right lastseenby_icon"></span></p>
            </div>

        </div>

        <div class="col-xs-4 referredby">
            <div class="referredby_show arial_bold">
                <p> <span>Last referred by</span>&nbsp;<span class="glyphicon glyphicon-chevron-right referredby_icon "></span></p>
            </div>
        </div>

        <div class="col-xs-4 insurance_provider">
            <div class="insurance_provider_show arial_bold">
                <p><span>Insurance provider</span>&nbsp;<span class="glyphicon glyphicon-chevron-right insurance_provider_icon"></span></p>
            </div>
        </div>
    </div>

    <div class="col-xs-12">
        <div class="col-xs-4">
            <div class="lastseen_content">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="referredby_content">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="insurance_provider_content">
            </div>
        </div>
    </div>

</div>
