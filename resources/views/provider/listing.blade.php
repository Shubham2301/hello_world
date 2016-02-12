<!-- TODO: Add pagination to patient list to avoid scrolling of elements -->
<div class="row content-row-margin practice_list"></div>
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
            <p class="bold" id="practice_name">Opthalmic Consultants of Long Island</p>
            <hr>
            <div class="row">
                <div class="col-xs-6">
                    <p><span class="bold">Doctor Name</span>
                        <br><span id="provider_name"></span></p>
                    <p><span class="bold">Speciality</span>
                        <br><span id="speciality"></span></p>
                    <p><span class="bold">Insurance</span>
                        <br>
                        <span class="glyphicon glyphicon-ok-circle"></span>
                    </p>
                </div>
                <div class="col-xs-6 ">
                    <p>
                        <span id="location" class="locations"></span><img src="{{asset('images/ajax-loader.gif')}}" class="ajax appointment_type">
                        <span id="location_address"></span>
                        <br>
                        <span id="location_contact"></span>
                    </p>
                    <p>
                        <span id="appointment_type_list" class="appointment_type_list"></span>
                        <span id="appointment_type"></span>
                    </p>
                    <p class="get_availability hide">
                        <span class="bold">Appointment&nbsp;</span><img src="{{asset('images/ajax-loader.gif')}}" class="ajax appointment_schedule">
                        <br>
                    </p>
                    <span style="display:flex;">
                        <p class="get_availability hide availability-btn" id="get_availability" style="padding:.3em;">
                            <span id="appointment_date"></span>
                            <span class="availability-text">Availability</span>
                            <br><span id="appointment_time"></span>
                        </p>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="availability" data-value="0">

</div>
