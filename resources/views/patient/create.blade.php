@if(!$data['admin'])
{!! Form::open(array('url' => '/patients', 'method' => 'GET', 'id' => 'back_to_select_patient')) !!}
{!! Form::hidden('referraltype_id', $data['referraltype_id'], array('id' => 'form_referraltype_id')) !!}
{!! Form::hidden('action', $data['action'], array('id' => 'form_action')) !!}
{!! Form::close() !!}
@endif
   <div class="row content-row-margin add_header">
    <div>
        <button type="button" id="{{$data['back_btn']}}" class="btn back patient_back">Back</button>
    </div>

    <div>
        <p class="add_title">Add New Patient</p>
    </div>
</div>
{!! Form::open(array('url' => $data['url'], 'method' => 'POST', 'id' => 'form_add_patients')) !!}
<div class="row content-row-margin">
    <div class="col-sm-6 col-xs-12">
        {!! Form::text('FirstName', $data['firstname'], array('class' => 'add_patient_input', 'required' => 'required', 'name' => 'firstname', 'placeholder' => 'First Name', 'id' => 'first_name')) !!} {!! Form::text('LastName', $data['lastname'], array('class' => 'add_patient_input', 'name' => 'lastname', 'placeholder' => 'Last Name', 'id' => 'last_name')) !!} {!! Form::email('email', $data['email'], array('class' => 'add_patient_input', 'name' => 'email', 'placeholder' => 'Email', 'id' => 'email')) !!} {!! Form::text('phone', $data['cellphone'], array('class' => 'add_patient_input', 'required' => 'required', 'name' => 'cellphone', 'placeholder' => 'Phone', 'id' => 'phone')) !!}
    </div>
    <div class="col-sm-6 col-xs-12">
        {!! Form::text('Gender', $data['gender'], array('class' => 'add_patient_input', 'required' => 'required', 'name' => 'gender', 'placeholder' => 'Gender', 'id' => 'gender')) !!} {!! Form::text('DateOfBirth', $data['birthdate'], array('class' => 'add_patient_input', 'name' => 'birthdate', 'placeholder' => 'Date of Birth', 'id' => 'dob')) !!} {!! Form::text('last_4_ssn', $data['lastfourssn'], array('class' => 'add_patient_input', 'name' => 'lastfourssn', 'placeholder' => 'Last 4 SSN', 'id' => 'last_4_ssn')) !!} {!! Form::text('Preferred Language', $data['preferredlanguage'], array('class' => 'add_patient_input', 'required' => 'required', 'name' => 'preferredlanguage', 'placeholder' => 'Preferred Language', 'id' => 'preferredlanguage')) !!}
    </div>
</div>
<div class="section-break"></div>
<div class="row content-row-margin">
    <div class="col-sm-6 col-xs-12">
        <p class="address">Address</p>
        {!! Form::text('address_1', $data['addressline1'], array('class' => 'add_patient_input', 'name' => 'addressline1', 'placeholder' => 'Address 1', 'id' => 'Address_1')) !!}
         {!! Form::text('address_2', $data['addressline2'], array('class' => 'add_patient_input', 'name' => 'addressline2', 'placeholder' => 'Address 2', 'id' => 'Address_2')) !!}
        {!! Form::text('City', $data['city'], array('class' => 'add_patient_input', 'name' => 'city', 'placeholder' => 'City', 'id' => 'city')) !!}
        {!! Form::text('State', $data['state'], array('class' => 'add_patient_input', 'name' => 'state', 'placeholder' => 'State', 'id' => 'state')) !!} {!! Form::text('Zip', $data['zip'], array('class' => 'add_patient_input', 'name' => 'zip', 'placeholder' => 'ZIP', 'id' => 'zip')) !!}
        @if(!$data['admin'])
        {!! Form::hidden('referraltype_id', $data['referraltype_id'], array('name' => 'referraltype_id' , 'id' => 'form_referraltype_id')) !!}
        {!! Form::hidden('action', $data['action'], array('name' => 'action' , 'id' => 'form_action')) !!}
         @endif
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
