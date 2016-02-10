<!-- TODO: Add pagination to patient list to avoid scrolling of elements -->
<div class="row content-row-margin practice_list"></div>
<div class="row content-row-margin practice_info" data-id="">
    <div class="col-xs-12">
        <div class="col-xs-4 center-align">
            <img src="{{asset('images/patient.png')}}" alt=""><br>
            <p class="button_type_1" id="change_practice_button">Change Provider</p><br>
            <p id ="openModel" type="button" class="button_type_1" data-toggle="modal" data-target="#provider_preferences" >Provider Preferences</p>
        </div>
        <div class="col-xs-8">
            <p class="bold" id="practice_name">Opthalmic Consultants of Long Island</p>
            <hr>
            <div class="row">
                <div class="col-xs-6">
                    <p><span class="bold">Doctor Name</span>
                        <br><span id="provider_name"></span></p>
                </div>
                <div class = "col-xs-6 ">
                    <p>
                        <span class="bold">Locations</span>
                        <br>
                        <select name="location" class="dropdown" id="location">
                        </select>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <p><span class="bold">Phone</span>
                        <br><span id="phone"></span></p>
                </div>
                <div class = "col-xs-6 ">
                    <p><span class="bold">Address</span><br>
                        <span id="location_address"></span>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <p><span class="bold">Speciality</span>
                        <br><span id="speciality"></span></p>
                </div>
                <div class="col-xs-6">
                    <p>
                        <span class="bold">Appointment Type &nbsp;</span><img src="{{asset('images/ajax-loader.gif')}}" class="ajax appointment_type">
                        <br>
                        <select name="appointment-type" class="hidden dropdown" id="appointment-type">
                        </select>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class = "col-xs-6 ">
                    <p><span class="bold">Insurance</span><br>
                        <span class="glyphicon glyphicon-ok-circle"></span>
                    </p>
                </div>
                <div class="col-xs-6">
                    <p>
                        <span class="bold">Appointment Date&nbsp;</span>
                        <input type='text' class="" id="appointment_date"/>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class = "col-xs-6 ">
                    <p>
                        <span class="bold">Appointment&nbsp;</span><img src="{{asset('images/ajax-loader.gif')}}" class="ajax appointment_schedule"><br>
                        <button class="availability-btn" id="get_availability" disabled>Availability<span class="glyphicon glyphicon-chevron-down"></span></button>
                    </p>
                </div>
                <div class="col-xs-6">
                    <p>
                        <span class="bold">Appointment Time</span>
                        <br><span id="appointment_time"></span></p>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="availability">
<!--
    <div class="weekday">
        <p class="date">02/02/2016</p>
    </div>
    <div class="weekday">
        <p class="date">02/02/2016</p>
    </div>
-->
</div>
           <!--Redesign this section in a more scalable way-->
           <!--
            <div class="row">
            <div class="col-xs-12 availability_display center-align">
        <div class="col-xs-offset-2 col-xs-1">
            <p><span class="bold">Sun</span>
                        <br> Closed</p>
        </div>
        <div class="col-xs-1">
            <p><span class="bold">Mon</span>
                        <br> Closed</p>
        </div>
        <div class="col-xs-1">
            <p><span class="bold">Tue</span>
                        <br> Closed</p>
        </div>
        <div class="col-xs-1">
            <p><span class="bold">Wed</span>
                        <br> Closed</p>
        </div>
        <div class="col-xs-1">
            <p><span class="bold">Thu</span>
                        <br> Closed</p>
        </div>
        <div class="col-xs-1">
            <p><span class="bold">Fri</span>
                        <br> Closed</p>
        </div>
        <div class="col-xs-1">
            <p><span class="bold">Sat</span>
                        <br> Closed</p>
        </div>
                </div>
        </div>
        -->
