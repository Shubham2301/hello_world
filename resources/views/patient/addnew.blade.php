
  {!! Form::open(array('url' => '/patients', 'method' => 'GET', 'id' => 'back_to_select_patient')) !!}
      {!! Form::hidden('referraltype_id', $data['referraltype_id'], array('id' => 'form_referraltype_id')) !!}
      {!! Form::hidden('action', $data['action'], array('id' => 'form_action')) !!}
  {!! Form::close() !!}
   <div class="row content-row-margin add_header">
    <div><button type="button" id="back_to_select_patient_btn" class="btn back">Back</button></div>
    <div><p class="add_title">Add New Patient</p></div>
</div>
{!! Form::open(array('url' => '/administration/patients/add', 'method' => 'POST', 'id' => 'form_add_patients')) !!}
<div class="row content-row-margin">
    <div class="col-sm-6 col-xs-12">
        {!! Form::text('FirstName', old('patient_fname'), array('class' => 'add_patient_input', 'required' => 'required', 'name' => 'patient_fname', 'placeholder' => 'First Name', 'id' => 'first_name')) !!}
        {!! Form::text('LastName', old('patient_lname'), array('class' => 'add_patient_input', 'name' => 'patient_lname', 'placeholder' => 'Last Name', 'id' => 'last_name')) !!}
        {!! Form::email('email', old('email'), array('class' => 'add_patient_input', 'name' => 'email', 'placeholder' => 'Email', 'id' => 'email')) !!}
        {!! Form::text('phone', old('phone'), array('class' => 'add_patient_input', 'required' => 'required', 'name' => 'phone', 'placeholder' => 'Phone', 'id' => 'phone')) !!}
    </div>
    <div class="col-sm-6 col-xs-12">
        {!! Form::text('Gender', old('gender'), array('class' => 'add_patient_input', 'required' => 'required', 'name' => 'gender', 'placeholder' => 'Gender', 'id' => 'gender')) !!}
        {!! Form::text('DateOfBirth', old('dob'), array('class' => 'add_patient_input', 'name' => 'dob', 'placeholder' => 'Date of Birth', 'id' => 'dob')) !!}
        {!! Form::text('last_4_ssn', old('Last4SSN'), array('class' => 'add_patient_input', 'name' => 'last_4_ssn', 'placeholder' => 'Last 4 SSN', 'id' => 'last_4_ssn')) !!}
        {!! Form::text('Preferred Language', old('preferredlanguage'), array('class' => 'add_patient_input', 'required' => 'required', 'name' => 'preferredlanguage', 'placeholder' => 'Preferred Language', 'id' => 'preferredlanguage')) !!}
    </div>
</div>
<div class="section-break"></div>
   <div class="row content-row-margin">
    <div class="col-sm-6 col-xs-12">
        <p class="address">Address</p>
        {!! Form::text('address_1', old('Address1'), array('class' => 'add_patient_input', 'name' => 'address_1', 'placeholder' => 'Address 1', 'id' => 'Address_1')) !!}
        {!! Form::text('address_2', old('Address2'), array('class' => 'add_patient_input', 'name' => 'address_2', 'placeholder' => 'Address 2', 'id' => 'Address_2')) !!}
        {!! Form::text('City', old('city'), array('class' => 'add_patient_input', 'name' => 'city', 'placeholder' => 'City', 'id' => 'city')) !!}
        {!! Form::text('State', old('state'), array('class' => 'add_patient_input', 'name' => 'state', 'placeholder' => 'State', 'id' => 'state')) !!}
        {!! Form::text('Zip', old('zip'), array('class' => 'add_patient_input', 'name' => 'zip', 'placeholder' => 'ZIP', 'id' => 'zip')) !!}

          {!! Form::hidden('referraltype_id', $data['referraltype_id'], array('name' => 'referraltype_id' , 'id' => 'form_referraltype_id')) !!}
    {!! Form::hidden('action', $data['action'], array('name' => 'action' , 'id' => 'form_action')) !!}
    </div>
</div>
     <div class="row content-row-margin">
<!--         <center><button class="btn btn-default btn-primary" id="save_patient_info">Save</button></center>-->
     {!! Form::submit('Add Patient', array('class' => 'btn btn-default btn-primary')) !!}
</div>



 {!! Form::close() !!}
