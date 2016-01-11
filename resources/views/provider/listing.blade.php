<!-- TODO: Add pagination to patient list to avoid scrolling of elements -->
<div class="row content-row-margin practice_list"></div>
<div class="row content-row-margin practice_info" data-id="">
    <div class="col-xs-12">
        <div class="col-xs-4 center-align">
            <img src="{{asset('images/patient.png')}}" alt=""><br>
            <p class="button_type_1" id="change_practice_button">Change Provider</p><br>
            <p class="button_type_2 schedule_button" data-id="" data-practice-id="">Schedule</p>
        </div>
        <div class="col-xs-8">
            <p class="bold" id="practice_name">Opthalmic Consultants of Long Island</p>
            <hr>
            <div class="row">
                <div class="col-xs-6">
                    <p><span class="bold">Doctor Name</span>
                        <br><span id="provider_name"></span></p>
                </div>
                <div class="col-xs-6">
                    <p><span class="bold">Speciality</span>
                        <br><span id="speciality"></span></p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <p><span class="bold">Phone</span>
                        <br><span id="phone"></span></p>
                </div>
                <div class="col-xs-6">
                    <p><span class="bold">Appointments</span>
                        <br>Availability</p>
                </div>
            </div>

            <div class="row">
                <div class = "col-xs-6 ">
                    <p><span class="bold">Locations</span><br>
                    <ul class="locations">
                       <li> <p>location 1</p></li>
                    </ul>
                </div>
                <div class="col-xs-6">
                </div>
            </div>
        </div>
    </div>
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
