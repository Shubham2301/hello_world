<!-- TODO: Add pagination to patient list to avoid scrolling of elements -->
<div class="row content-row-margin patient_list"></div>
<div class="row content-row-margin patient_info" data-id="">
    <div class="col-xs-12">
        <div class="col-xs-4 center-align">
            <img src="{{asset('images/patient.png')}}" alt="">
            <br>
            <p class="button_type_1" id="change_patient_button">Change Patient</p>
            <br>

            <div class="dropdown">
                <a href="#" data-toggle="dropdown" class="dropdown-toggle">Patient File <b class="caret"></b></a>
                <ul class="dropdown-menu " id="ccda_dropdown">
                    <li>
                        <a href=""> <img class="ccda_view_icon" src="{{URL::asset('images/view-icon.png')}}" alt=""> View</a>
                    </li>
                    <li>
                        <p id="import_ccda_button" data-toggle="modal" data-target="#importCcda" data-id=""><img class="ccda_upload_icon" src="{{URL::asset('images/arrow-up-icon.png')}}" alt=""> Upload</p>
                    </li>
                    <li>
                        <a href="" id="download_ccda"> <img class="ccda_download_icon" src="{{URL::asset('images/arrow-down-icon.png')}}" alt=""> Download </a>
                    </li>
                </ul>
            </div>
            <p class="button_type_1" style="display:none;" id="compare_ccda_button" data-toggle="modal" data-target="#compareCcda">update Ccda</p>
        </div>
        <div class="col-xs-8">
            <p class="bold" id="patient_name"></p>
            <hr>
            <div class="row">
                <div class="col-xs-6">
                    <p><span class="bold">Email</span>
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
                    <p><span class="bold">SSN</span>
                        <br><span id="patient_ssn"></span> </p>
                </div>
            </div>

        </div>
    </div>
    <div class="col-xs-12 patient_table_header">
        <div class="col-xs-4 lastseenby">
            <div class="lastseenby_show">
                <p><span>Last seen by</span>&nbsp;<span class="glyphicon glyphicon-chevron-right lastseenby_icon"></span></p>
            </div>

        </div>

        <div class="col-xs-4 referredby">
            <div class="referredby_show">
                <p> <span>Last referred by</span>&nbsp;<span class="glyphicon glyphicon-chevron-right referredby_icon "></span></p>
            </div>
        </div>

        <div class="col-xs-4 insurance_provider">
            <div class="insurance_provider_show">
                <p><span>Insurance provider</span>&nbsp;<span class="glyphicon glyphicon-chevron-right insurance_provider_icon"></span></p>
            </div>
        </div>
    </div>

    <div class="col-xs-12">
        <div class="col-xs-4 lastseen_content">
            <ul>
                <li>
                    <p><span class="bold">Provider</span>
                        <br>Opthalmic Consultants</p>
                </li>
                <li>
                    <p><span class="bold">Doctor</span>
                        <br>Danial Garibaldi</p>
                </li>
            </ul>
        </div>
        <div class="col-xs-4 referredby_content">
            <ul>
                <li>
                    <p><span class="bold">Provider</span>
                        <br>Opthalmic Consultants</p>
                </li>
                <li>
                    <p><span class="bold">Doctor</span>
                        <br>Danial Garibaldi</p>
                </li>
            </ul>
        </div>
        <div class="col-xs-4 insurance_provider_content">
            <ul>
                <li>
                    <p><span class="bold">Provider</span>
                        <br>Opthalmic Consultants</p>
                </li>
                <li>
                    <p><span class="bold">Doctor</span>
                        <br>Danial Garibaldi</p>
                </li>
            </ul>
        </div>
    </div>

</div>
