@extends('layouts.master')

@section('title', 'My Ocuhub - import Patients')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{asset('css/patient.css')}}">
<script type="text/javascript" src="{{asset('js/patient_import.js')}}"></script>
@endsection

@section('content')
<div class="content-section active" id="patients_section">
    <div class="import_form">
        {!! Form::open(array('url' => 'import/csv', 'method' => 'POST', 'files'=>true)) !!}
    <span class="input_row">
        <p class="input_lable" >Practice</p>
        {!!Form::select('practice_id',  $data, null, array('class'=>'form-control','id'=>'practice_list')) !!}
    </span>
    <span class="input_row">
        <p class="input_lable" >Location</p>
        <select name="location" id="practice_locations" class="form-control">
        </select>
    </span>

      {!!Form::file('patient_csv')!!}



       {!!FOrm::submit('save')!!}

        {!! Form::close() !!}
    </div>

</div>


@endsection
