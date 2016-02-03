<div class="row content-row-margin-scheduling">
    <div class="col-xs-10 search_input">
        <input type="text" class="" id="search_practice_input" >
        <span class="glyphicon glyphicon-search" id="search_practice_button" aria-hidden="true"></span>
        <span class="glyphicon glyphicon-plus-sign add_search_option" id="add_practice_search_option" aria-hidden="true">    </span>
    </div>
    <div class="col-xs-2 search_dropdown" patient-id="{!! $data['patient_id']!!}">
        <!-- TODO: Create custom dropdown and write css in style.less and write js in main.js -->
        <select type="text" class="" id="search_practice_input_type">
            <option value="all">All</option>
            <option value="pratice_name">Practice Name</option>
            <option value="location">Location</option>
            <option value="doctor_name">Doctor name</option>
            <option value="zip">zip code</option>
        </select>
    </div>

  <div class="col-xs-12 search_filter">
        <!--
        <div class="search_filter_item">
           <span class="item_type">name</span>:
            <span class="item_value">Provider</span>
            <span class="remove_option">x</span>
        </div>
         -->


    </div>
    </div>
    <div class="row content-row-margin-scheduling patient_info" data-id="">
    <div class="col-xs-12">
        <div class="col-xs-4 center-align">
            <img src="{{asset('images/patient.png')}}" alt=""><br>
<!--            <p class="button_type_11" id="change_patient_button"  >Change Patient</p><br>-->
        </div>
        <div class="col-xs-8">
            <p class="bold" id="patient_name"></p>
            <hr>
            <div class="row">
                <div class="col-xs-6">
                    <p><span class="bold" >Email</span>
                        <br><span id="patient_email"> </span></p>
                    <p><span class="bold">Date of Birth</span>
                        <br><span id="patient_dob"> </span></p>
                </div>
                <div class="col-xs-6">
                    <p><span class="bold">Address</span>
                        <br><span id="patient_add1"></span>
                        <br><span id="patient_add2"></span>
                        <br><span id="patient_add3"></span></p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <p><span class="bold">Phone</span>
                        <br><span id="patient_phone">  </span></p>
                </div>
                <div class="col-xs-6">
                    <p><span class="bold" >SSN</span>
                        <br><span id="patient_ssn"></span> </p>
                </div>
            </div>

        </div>
    </div>
    <div class="col-xs-12 patient_table_header">
        <div class="col-xs-4 lastseenby">
            <div class="lastseenby_show">
            <p><span>Last seen by</span>&nbsp;<span class="glyphicon glyphicon-chevron-right lastseenby_icon"></span></p></div>

        </div>

        <div class="col-xs-4 referredby">
            <div class="referredby_show">
            <p> <span>Last referred by</span>&nbsp;<span class="glyphicon glyphicon-chevron-right referredby_icon "></span></p></div>
        </div>

        <div class="col-xs-4 insurance_provider">
            <div class="insurance_provider_show">
            <p><span>Insurance provider</span>&nbsp;<span class="glyphicon glyphicon-chevron-right insurance_provider_icon"></span></p></div>
        </div>
    </div>

     <div class="col-xs-12">
        <div class="col-xs-4">
           <div class="lastseen_content">
            <ul>
            <li><p><span class="bold">Provider</span>
                        <br>Opthalmic Consultants</p></li>
            <li><p><span class="bold">Doctor</span>
                        <br>Danial Garibaldi</p></li>
            </ul>
            </div>
        </div>
        <div class="col-xs-4">
           <div class="referredby_content">
            <ul>
            <li><p><span class="bold">Provider</span>
                        <br>Opthalmic Consultants</p></li>
            <li><p><span class="bold">Doctor</span>
                        <br>Danial Garibaldi</p></li>
            </ul>
            </div>
        </div>
        <div class="col-xs-4">
           <div class="insurance_provider_content">
            <ul>
           <li> <p><span class="bold">Provider</span>
                        <br>Opthalmic Consultants</p></li>
            <li><p><span class="bold">Doctor</span>
                        <br>Danial Garibaldi</p></li>
            </ul>
            </div>
        </div>
    </div>

</div>

