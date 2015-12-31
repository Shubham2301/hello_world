<!-- TODO: Add pagination to patient list to avoid scrolling of elements -->
<div class="row content-row-margin patient_list"></div>
<div class="row content-row-margin patient_info" data-id="">
    <div class="col-xs-12">
        <div class="col-xs-4 center-align">
            <img src="{{asset('images/patient.png')}}" alt=""><br>
            <p class="button_type_1" id="change_patient_button"  >Change Patient</p><br>
            <p class="button_type_2" id="select_provider_button" data-id="0">Select Provider</p>
        </div>
        <div class="col-xs-8">
            <p class="bold" id="patient_name">Allen Rovenstine</p>
            <hr>
            <div class="row">
                <div class="col-xs-6">
                    <p><span class="bold" >Email</span>
                        <br><span id="patient_email"> allen@gmail.com</span></p>
                    <p><span class="bold">Date of Birth</span>
                        <br><span id="patient_dob"> 12 March 1969</span></p>
                </div>
                <div class="col-xs-6">
                    <p><span class="bold">Address</span>
                        <br><span id="patient_add1">G-747, Suncity,</span>
                        <br><span id="patient_add2">Sector-54, Gurgaon,</span>
                        <br><span id="patient_add3">Haryana, India </span></p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <p><span class="bold">Phone</span>
                        <br><span id="patient_phone"> +0123456789 </span></p>
                </div>
                <div class="col-xs-6">
                    <p><span class="bold" >SSN</span>
                        <br><span id="patient_ssn">1234 </span> </p>
                </div>
            </div>

        </div>
    </div>
    <div class="col-xs-12 patient_table_header">
        <div class="col-xs-4">
            <span>Last seen by</span>
        </div>
        <div class="col-xs-4">
            <span>Last referred by</span>
        </div>
        <div class="col-xs-4">
            <span>Insurance provider</span>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-4">
            <p><span class="bold">Provider</span>
                        <br>Opthalmic Consultants</p>
            <p><span class="bold">Doctor</span>
                        <br>Danial Garibaldi</p>
        </div>
        <div class="col-xs-4">
            <p><span class="bold">Provider</span>
                        <br>Opthalmic Consultants</p>
            <p><span class="bold">Doctor</span>
                        <br>Danial Garibaldi</p>
        </div>
        <div class="col-xs-4">
            <p><span class="bold">Provider</span>
                        <br>Opthalmic Consultants</p>
            <p><span class="bold">Doctor</span>
                        <br>Danial Garibaldi</p>
        </div>
    </div>
</div>
