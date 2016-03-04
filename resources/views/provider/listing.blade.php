<!-- TODO: Add pagination to patient list to avoid scrolling of elements -->
<div class="row content-row-margin practice_list"></div>
<div class="row content-row-margin no_item_found">
    <p>No results found matching :</p>
    <p></p>
</div>
<div class="row content-row-margin practice_info" data-id="">
    <div class="col-xs-12">
        <div class="col-xs-4 center-align">
            <img src="{{asset('images/patient.png')}}" alt="">
            <br>
            <p class="button_type_1" id="change_practice_button">Change Provider</p>
            <br>
            <p id="openModel" type="button" class="button_type_1" data-toggle="modal" data-target="#provider_preferences">Provider Preferences</p>
        </div>
        <div class="col-xs-8">
            <p class="bold arial_bold" id="practice_name">Opthalmic Consultants of Long Island</p>
            <div class="row">
                <div class="col-xs-6">
                    <p><span class="bold arial_bold">Doctor Name</span>
                        <br><span class="provider_detail_text arial" id="provider_name"></span></p>
                    <p><span class="bold arial_bold">Speciality</span>
                        <br><span class="provider_detail_text arial" id="speciality"></span></p>
                    <p><span class="bold arial_bold">Insurance</span>
                        <br>
                        <span class="glyphicon glyphicon-ok-circle"></span>
                    </p>
                </div>
                <div class="col-xs-6 ">
                    <p>
                        <span id="location" class="locations"></span><img src="{{asset('images/ajax-loader.gif')}}" class="ajax appointment_type">
                        <span class="provider_detail_text arial" id="location_address"></span>
                        <br>
                        <span class="provider_detail_text arial" id="location_contact"></span>
                        <br>
                        <span class="provider_detail_text arial appointment_type_not_found" style="display:none;">
                            <span class="bold arial_bold">Appointment Types</span>
                        <br> No appointment types found for this location. Select another location or call the practice to schedule an appointment.
                        </span>
                    </p>
                    <p>
                        <span id="appointment_type_list" class="appointment_type_list"></span>
                        <span class="provider_detail_text arial" id="appointment_type"></span>
                    </p>
                    <p class="get_availability hide">
                        <span class="bold arial_bold">Appointment&nbsp;</span><img src="{{asset('images/ajax-loader.gif')}}" class="ajax appointment_schedule">
                        <br>
                    </p>
                    <span class="appointment_detail">
                        <span class="get_availability hide availability-btn" id="get_availability">
                            <span id="appointment_date"></span>
                    <br><span id="appointment_time"></span><br>
                    </span>
                    </span>
                    <input type="text" class="get_availability hide select_date" id="select_date" placeholder="Select Date">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="availabilityModal" role="dialog">
    <div class="modal-dialog form_model center" data-value="0">
        <div class="modal-content availability">
        </div>
    </div>
</div>
