<div class="patient_records_info arial">
    <div class="close_patient_records_info">
       <img src="{{URL::asset('images/close-natural.png')}}" class="natural">
       <img src="{{URL::asset('images/close-active.png')}}" class="active">
<!--        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>-->
    </div>
    <div class="patient_demo_info">
        <p>
            <span class="patient_name arial_bold"></span>
        </p>
        <p>
            <span class="patient_phone"></span>
        </p>
    </div>
    <div class="patient_import_info ">
        <p class="info_header arial_bold">Special Request</p>
        <p>
            <span class="special_request arial"></span>
        </p>
    </div>
    <div class="patient_appt_info ">
        <p class="info_header arial_bold">Appointment Information</p>
        <p>
            <span class="scheduled_to arial"></span>
            <br>
            <span class="appointment_date arial"></span>
            <br>
            <span class="appointment_type arial"></span>
        </p>
    </div>
    <div class="patient_contact_info">
        <p class="info_header arial_bold">Patient Progression</p>
        <div class="contact_attempts"> </div>

        <p class="info_header arial_bold">
            Notes
        </p>

        <div class="contact_notes">
			<p class="action_note">Click on history status to see notes</p>
			<span id="action_result_section">
			<p class="arial text-normal" style = "font-style:normal;"><strong>Result</strong></p>
           	<p class="action_result"></p>
			</span>
         </div>
        <div class="dropdown patient_info_action">
            <div id="dropdownMenuPatientInfo" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" area-hidden="true" class="action_dropdown_patient_info dropdown-toggle">
                <div style="cursor: pointer;">Take Action <span class="glyphicon glyphicon glyphicon-triangle-bottom"></span></div>
            </div>
            <ul class="dropdown-menu" id="records_action_dropdown">

            </ul>
        </div>
    </div>
</div>
