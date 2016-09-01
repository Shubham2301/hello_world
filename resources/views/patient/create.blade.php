@if(!$data['admin'])
{!! Form::open(array('url' => '/patients', 'method' => 'GET', 'id' => 'back_to_select_patient')) !!}
{!! Form::hidden('referraltype_id', $data['referraltype_id'], array('id' => 'form_referraltype_id')) !!}
{!! Form::hidden('action', $data['action'], array('id' => 'form_action')) !!}
@if(isset($data['patient_id']))
{!! Form::hidden('patient_id', $data['patient_id'], array('id' => 'form_patientid')) !!}
@endif {!! Form::close() !!}
@endif
<div class="row content-row-margin add_header">
    <div>
        <button type="button" id="{{$data['back_btn']}}" class="btn back patient_back">Back</button>
    </div>
    <div>
        <p class="add_title">
            @if(isset($data['email'])) Edit Patient @else Add New Patient @endif
        </p>
    </div>
    {!! Form::open(array('url' => '#', 'method' => 'POST', 'files'=>true,'id'=>'import_from_ccda_form')) !!}
    <span class ='btn import-btn' id = "import_from_ccda">Import from C-CDA {!!Form::file('patient_ccda')!!}
    </span>
    <input type="hidden" id="form_edit_mode" name="ccda_patient_id"  value="{{$data['id']}}">
    {!! Form::close() !!}
</div>
{!! Form::open(array('url' => $data['url'], 'method' => 'POST', 'id' => 'form_add_patients')) !!}
<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
            <span class="popover_text" data-content="">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                Patient Information</a>
            </span>
            </h4>
        </div>
        <div id="collapse1" class="panel-collapse collapse in">
            <div class="panel-body">
                <div class="row content-row-margin">
                    <div class="col-sm-6 col-xs-12">
                        {!! Form::text('FirstName', $data['firstname'], array('class' => 'add_patient_input', 'required' => 'required', 'name' => 'firstname', 'placeholder' => 'First Name*', 'id' => 'firstname', 'data-toggle' => 'tooltip', 'title' => 'First Name', 'data-placement' => 'right', 'maxlength' => '50')) !!}
                        {!! Form::text('middlename', $data['middlename'], array('class' => 'add_patient_input', 'name' => 'middlename', 'placeholder' => 'Middle Name', 'id' => 'middlename', 'data-toggle' => 'tooltip', 'title' => 'Middle Name', 'data-placement' => 'right', 'maxlength' => '50')) !!}
                        {!! Form::text('LastName', $data['lastname'], array('class' => 'add_patient_input', 'name' => 'lastname', 'placeholder' => 'Last Name*', 'id' => 'lastname','required' => 'required', 'data-toggle' => 'tooltip', 'title' => 'Last Name', 'data-placement' => 'right', 'maxlength' => '50')) !!}
                        {!! Form::email('email', $data['email'], array('class' => 'add_patient_input patient_email_field', 'name' => 'email', 'placeholder' => 'Email', 'id' => 'email', 'data-toggle' => 'tooltip', 'title' => 'Email', 'data-placement' => 'right')) !!}
                        <span class="patient_phone_input">
                            {!! Form::text('cellphone', $data['cellphone'], array('class' => 'add_patient_input phone_visible', 'name' => 'cellphone', 'placeholder' => 'Cellphone', 'id' => 'cellphone', 'data-toggle' => 'tooltip', 'title' => 'Cellphone', 'data-placement' => 'right', 'pattern' => '[^A-Za-z]+', 'maxlength' => '20')) !!}
                            @if($data['workphone'] == '' || $data['homephone'] == '')<span class="add_another_phone" data-toggle="tooltip" title="Add another phone number" data-placement="right"><img src="{{URL::asset('images/plus_icon.png')}}"></span> @endif
                        </span>
                        @if($data['workphone'] == '')
                        <span class="workphone_span hide_phone_field">
                            @else
                            <span class="workphone_span">
                                @endif
                            {!! Form::text('workphone', $data['workphone'], array('class' => 'add_patient_input', 'name' => 'workphone', 'placeholder' => 'Workphone', 'id' => 'workphone', 'data-toggle' => 'tooltip', 'title' => 'Workphone', 'data-placement' => 'right', 'pattern' => '[^A-Za-z]+', 'maxlength' => '20')) !!}</span> @if($data['homephone'] == '')
                            <span class="homephone_span hide_phone_field">
                                @else
                                <span class="homephone_span">
                                    @endif
                                {!! Form::text('homephone', $data['homephone'], array('class' => 'add_patient_input', 'name' => 'homephone', 'placeholder' => 'Homephone', 'id' => 'homephone', 'data-toggle' => 'tooltip', 'title' => 'Homephone', 'data-placement' => 'right', 'pattern' => '[^A-Za-z]+', 'maxlength' => '20' )) !!}</span>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                {!! Form::select('gender', Helper::genders(), $data['gender'], array('class' => 'add_patient_input', 'placeholder' => 'Gender*', 'id' => 'gender', 'required' => 'required', 'data-toggle' => 'tooltip', 'title' => 'Gender', 'data-placement' => 'right')) !!}
                                {!! Form::text('DateOfBirth', $data['birthdate'], array('class' => 'add_patient_input', 'name' => 'birthdate', 'placeholder' => 'Date of Birth*', 'id' => 'birthdate', 'required' => 'required', 'data-toggle' => 'tooltip', 'title' => 'Date of Birth', 'data-placement' => 'right')) !!}
                                {!! Form::text('last_4_ssn', $data['lastfourssn'], array('class' => 'add_patient_input', 'name' => 'lastfourssn', 'placeholder' => 'Last 4 SSN', 'id' => 'lastfourssn', 'data-toggle' => 'tooltip', 'title' => 'Last 4 SSN', 'data-placement' => 'right', 'maxlength' => '4')) !!}
                                {!! Form::select('preferredlanguage', Helper::languages() , $data['preferredlanguage'], array('class' => 'add_patient_input', 'placeholder' => 'Language*', 'id' => 'preferredlanguage', 'required' => 'required', 'data-toggle' => 'tooltip', 'title' => 'Preferred Language', 'data-placement' => 'right')) !!}
                                {!! Form::select('engagement_preference', Helper::messageTypes(), Helper::ifExists('engagement_preference', $data), array('class' => 'add_patient_input', 'placeholder' => 'Engagement Preference', 'id' => 'engagement_preference', 'data-toggle' => 'tooltip', 'title' => 'Engagement Preference', 'data-placement' => 'right')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                    <span class="popover_text" data-content="">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                        Patient Address</a>
                    </span>
                    </h4>
                </div>
                <div id="collapse2" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="row content-row-margin">
                            <div class="col-sm-6 col-xs-12">
                                {!! Form::text('address_1', $data['addressline1'], array('class' => 'add_patient_input', 'name' => 'addressline1', 'placeholder' => 'Address 1', 'id' => 'addressline1', 'data-toggle' => 'tooltip', 'title' => 'Address Line 1', 'data-placement' => 'right')) !!}
                                {!! Form::text('City', $data['city'], array('class' => 'add_patient_input', 'name' => 'city', 'placeholder' => 'City', 'id' => 'city', 'data-toggle' => 'tooltip', 'title' => 'City', 'data-placement' => 'right')) !!}
                                {!! Form::text('Zip', $data['zip'], array('class' => 'add_patient_input', 'name' => 'zip', 'placeholder' => 'ZIP', 'id' => 'zip', 'data-toggle' => 'tooltip', 'title' => 'ZIP', 'data-placement' => 'right')) !!}
                                @if(!$data['admin'])
                                {!! Form::hidden('referraltype_id', $data['referraltype_id'], array('name' => 'referraltype_id' , 'id' => 'form_referraltype_id')) !!}
                                {!! Form::hidden('action', $data['action'], array('name' => 'action' , 'id' => 'form_action')) !!}
                                @endif
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                {!! Form::text('address_2', $data['addressline2'], array('class' => 'add_patient_input', 'name' => 'addressline2', 'placeholder' => 'Address 2', 'id' => 'addressline2', 'data-toggle' => 'tooltip', 'title' => 'Address Line 2', 'data-placement' => 'right')) !!}
                                {!! Form::text('State', $data['state'], array('class' => 'add_patient_input', 'name' => 'state', 'placeholder' => 'State', 'id' => 'state', 'data-toggle' => 'tooltip', 'title' => 'State', 'data-placement' => 'right')) !!}
                                {!! Form::select('timezone_id', Helper::timezones(), Helper::ifExists('timezone_id', $data), array('class' => 'add_patient_input', 'placeholder' => 'Timezone', 'id' => 'timezone', 'data-toggle' => 'tooltip', 'title' => 'Timezone', 'data-placement' => 'right')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                    <span class="popover_text" data-content="">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
                        Referring Details</a>
                    </span>
                    </h4>
                </div>
                <div id="collapse3" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="row content-row-margin">
                            <div class="col-sm-6 col-xs-12">
                                {!! Form::text('referred_by_practice', Helper::ifExists('referred_by_practice', $data), array('class' => 'add_patient_input referredby_practice', 'name' => 'referred_by_practice', 'placeholder' => 'ReferredBy Practice', 'id' => 'referred_by_practice', 'onkeyup'=>'referredByPracticeSuggestions(this.value)', 'autocomplete'=>'off', 'data-toggle' => 'tooltip', 'title' => 'Referred By Practice', 'data-placement' => 'right')) !!}
                                <ul class="suggestion_list practice_suggestions">
                                </ul>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                {!! Form::text('referred_by_provider', Helper::ifExists('referred_by_provider', $data), array('class' => 'add_patient_input referredby_provider', 'name' => 'referred_by_provider', 'placeholder' => 'ReferredBy Provider', 'id' => 'referred_by_provider', 'onkeyup'=>'referredByProviderSuggestions(this.value)','autocomplete'=>'off', 'data-toggle' => 'tooltip', 'title' => 'Referred By Provider', 'data-placement' => 'right')) !!}
                                <ul class="suggestion_list provider_suggestions">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                    <span class="popover_text" data-content="">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">
                        Disease  Details</a>
                    </span>
                    </h4>
                </div>
                <div id="collapse4" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="row content-row-margin">
                            <div class="col-sm-6 col-xs-12">
                                {!! Form::text('address_1', Helper::ifExists('disease_type', $data), array('class' => 'add_patient_input', 'name' => 'disease_type', 'placeholder' => 'Disease Type', 'id' => 'disease_type', 'data-toggle' => 'tooltip', 'title' => 'Disease Type', 'data-placement' => 'right')) !!}
                                {!! Form::text('address_1', Helper::ifExists('severity', $data), array('class' => 'add_patient_input', 'name' => 'severity', 'placeholder' => 'Severity', 'id' => 'severity', 'data-toggle' => 'tooltip', 'title' => 'Severity', 'data-placement' => 'right')) !!}
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                {!! Form::text('special_request', $data['special_request'], array('class' => 'add_patient_input', 'name' => 'special_request', 'placeholder' => 'Special Request', 'id' => 'special_request', 'data-toggle' => 'tooltip', 'title' => 'Special Request', 'data-placement' => 'right')) !!}
                                {!! Form::text('pcp', $data['pcp'], array('class' => 'add_patient_input', 'name' => 'pcp', 'placeholder' => 'Primary Care Physician', 'id' => 'pcp', 'data-toggle' => 'tooltip', 'title' => 'Primary Care Physician', 'data-placement' => 'right')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                    <span class="popover_text" data-content="">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">
                        Insurance Details</a>
                    </span>
                    </h4>
                </div>
                <div id="collapse5" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="row content-row-margin">
                            <div class="col-sm-6 col-xs-12">
                                {!! Form::text('insurance_carrier', Helper::ifExists('insurance_carrier', $data), array('class' => 'add_patient_input', 'name' => 'insurance_carrier', 'placeholder' => 'Insurance Carrier', 'id' => 'insurance_carrier', 'data-toggle' => 'tooltip', 'title' => 'Insurance Carrier', 'data-placement' => 'right')) !!}
                                {!! Form::text('subscriber_birthdate', Helper::ifExists('subscriber_birthdate', $data) , array('class' => 'add_patient_input', 'name' => 'subscriber_birthdate', 'placeholder' => 'Subscriber DOB', 'id' => 'subscriber_birthdate', 'data-toggle' => 'tooltip', 'title' => 'Subscriber DOB', 'data-placement' => 'right')) !!}
                                {!! Form::text('insurance_group_no', Helper::ifExists('insurance_group_no', $data) , array('class' => 'add_patient_input', 'name' => 'insurance_group_no', 'placeholder' => 'Group #', 'id' => 'insurance_group_no', 'data-toggle' => 'tooltip', 'title' => 'Group #', 'data-placement' => 'right')) !!}
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                {!! Form::text('subscriber_name', Helper::ifExists('subscriber_name', $data) , array('class' => 'add_patient_input', 'name' => 'subscriber_name', 'placeholder' => 'Subscriber Name', 'id' => 'subscriber_name', 'data-toggle' => 'tooltip', 'title' => 'Subscriber Name', 'data-placement' => 'right')) !!}
                                {!! Form::text('subscriber_id', Helper::ifExists('subscriber_id', $data) , array('class' => 'add_patient_input', 'name' => 'subscriber_id', 'placeholder' => 'Subscriber ID', 'id' => 'subscriber_id', 'data-toggle' => 'tooltip', 'title' => 'Subscriber ID', 'data-placement' => 'right')) !!}
                                {!! Form::text('subscriber_relation', Helper::ifExists('subscriber_relation', $data) , array('class' => 'add_patient_input', 'name' => 'subscriber_relation', 'placeholder' => 'Relation to Patient', 'id' => 'subscriber_relation', 'data-toggle' => 'tooltip', 'title' => 'Relation to Patient', 'data-placement' => 'right')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row content-row-margin add_patient_footer">
            <div class="col-xs-8 col-sm-8 col-md-8">
                {!! Form::submit('Save', array('class' => 'btn btn-default btn-primary save_patient_button')) !!}
                <button type="button" class="btn add-btn" id="dontsave_new_patient">Don't Save</button>
            </div>
            <div class="col-xs-4 col-sm-4"></div>
        </div>
        {!! Form::close() !!}
