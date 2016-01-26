<div class="add_patient_form">
   <div class="row content-row-margin add_header">
    <div><button type="button" id="back_to_patient_admin" class="btn back">Back</button></div>
    <div><p class="add_title">Add New Patient</p></div>
</div>
{!! Form::open(array('url' => '/administration/patients', 'method' => 'GET', 'id' => 'form_add_patients')) !!}
<div class="row content-row-margin">
    <div class="col-sm-6 col-xs-12">
        {!! Form::text('First Name', old('parient_fname'), array('class' => 'add_patient_input', 'required' => 'required', 'name' => 'patient_fname', 'placeholder' => 'First Name', 'id' => 'first_name')) !!}
        {!! Form::text('Last Name', old('patient_lname'), array('class' => 'add_patient_input', 'name' => 'patient_lname', 'placeholder' => 'Last Name', 'id' => 'last_name')) !!}
        {!! Form::email('email', old('email'), array('class' => 'add_patient_input', 'name' => 'email', 'placeholder' => 'Email', 'id' => 'email')) !!}
    </div>
    <div class="col-sm-6 col-xs-12">
        {!! Form::text('Gender', old('gender'), array('class' => 'add_patient_input', 'required' => 'required', 'name' => 'gender', 'placeholder' => 'Gender', 'id' => 'gender')) !!}
        {!! Form::text('Date of Birth', old('dob'), array('class' => 'add_patient_input', 'name' => 'dob', 'placeholder' => 'Date of Birth', 'id' => 'dob')) !!}
        {!! Form::text('Last 4 SSN', old('last_4_ssn'), array('class' => 'add_patient_input', 'name' => 'last_4_ssn', 'placeholder' => 'Last 4 SSN', 'id' => 'last_4_ssn')) !!} 
    </div>
</div>
<div class="section-break"></div>
   <div class="row content-row-margin">
    <div class="col-sm-6 col-xs-12">
        <p class="address">Address</p>
        {!! Form::text('Address 1', old('address_1'), array('class' => 'add_patient_input', 'name' => 'address_1', 'placeholder' => 'Address 1', 'id' => 'Address_1')) !!}
        {!! Form::text('Address 2', old('address_2'), array('class' => 'add_patient_input', 'name' => 'address_2', 'placeholder' => 'Address 2', 'id' => 'Address_2')) !!}
        {!! Form::text('City', old('city'), array('class' => 'add_patient_input', 'name' => 'city', 'placeholder' => 'City', 'id' => 'city')) !!}
        {!! Form::text('State', old('state'), array('class' => 'add_patient_input', 'name' => 'state', 'placeholder' => 'State', 'id' => 'state')) !!}
        {!! Form::text('Zip', old('zip'), array('class' => 'add_patient_input', 'name' => 'zip', 'placeholder' => 'ZIP', 'id' => 'zip')) !!}
    </div>
</div>
     <div class="row content-row-margin">
         <center><button class="btn btn-default btn-primary">Save</button>
             <button class="btn btn-default">Dont Save</button></center>
</div>

 {!! Form::close() !!}
</div>