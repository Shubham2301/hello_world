<div class="row content-row-margin-scheduling">
   <div class="search_bar">
    <div class="col-xs-10 search_input">
        <input type="text" class="" id="search_practice_input" >
<!--        <span class="glyphicon glyphicon-search" id="search_practice_button" aria-hidden="true"></span>-->
        <img src="{{URL::asset('images/search-icon.png')}}" id="search_practice_button">
        <span class="glyphicon glyphicon-plus-sign add_search_option" id="add_practice_search_option" aria-hidden="true">    </span>
    </div>
    <div class="col-xs-2 search_dropdown" patient-id="{!! $data['patient_id']!!}">
        <div class="dropdown"><span  data-toggle="dropdown" class="dropdown-toggle" aria-expanded="true"><span class="custom_dropdown"><span id="search_practice_input_type" value="all">All</span><img src="/images/triangle-down.png" class="custom_dropdown_img_search"></span></span><ul class="dropdown-menu" id="custom_dropdown">
                <li value="all">All</li>
                <li value="pratice_name">Practice Name</li>
                <li value="location">Location</li>
                <li value="provider_name">Provider Name</li>
                <li value="zip">Zip code</li>
        </ul></div>
    </div>
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
            <p><span>Last seen by</span>&nbsp;<span class="glyphicon glyphicon-chevron-right lastseenby_icon"></span></p></div>

        </div>

        <div class="col-xs-4 referredby">
            <div class="referredby_show arial_bold">
            <p> <span>Last referred by</span>&nbsp;<span class="glyphicon glyphicon-chevron-right referredby_icon "></span></p></div>
        </div>

        <div class="col-xs-4 insurance_provider">
            <div class="insurance_provider_show arial_bold">
            <p><span>Insurance provider</span>&nbsp;<span class="glyphicon glyphicon-chevron-right insurance_provider_icon"></span></p></div>
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

