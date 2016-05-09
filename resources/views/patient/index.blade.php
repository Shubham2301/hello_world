@extends('layouts.master')

@section('title', 'My Ocuhub - Select Patients')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/patient.css')}}">
<script type="text/javascript" src="{{elixir('js/patient.js')}}"></script>
<script type="text/javascript" src="{{elixir('js/import.js')}}"></script>
@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')

	@if (Session::has('success'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>
            <i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
        </strong>
        {{ Session::pull('success') }}
    </div>
    @endif
<input type="hidden" id="from_admin" value="{{$data['admin']}}" >
    <div class="content-section content-section-scheduling active" id="patients_section">
        <div class="patients_section active" id="select_patient">
            {!! Form::open(array('url' => '/providers', 'method' => 'GET', 'id' => 'form_select_provider')) !!}

                {!! Form::hidden('referraltype_id', $data['referraltype_id'] , array('id' => 'form_referraltype_id')) !!}
                {!! Form::hidden('action', $data['action'], array('id' => 'form_action')) !!}
                {!! Form::hidden('patient_id', '', array('id' => 'form_patient_id')) !!}

            {!! Form::close() !!}

            @if(array_key_exists('referraltype_id', $data))
            <div class="row content-row-margin">
                <div class="col-xs-12 section-header">
                    <span class="arial_bold">Schedule an appointment</span>
                    <p class="button_type_3 select_provider_button" id="select_provider_button" data-id="0" align="right">Select Provider<span class="glyphicon glyphicon-chevron-right"></span></p>
                </div>
            </div>
            <div class="row content-row-margin-scheduling">
                <div class="col-xs-12 subsection-header arial">
                    <span>1. Search for a patient</span>
                    <sapn class="action-btns active">
                    @can('edit-patient')
                    <a id="add_patient_btn">Add New</a>
                    @endcan
                    @can('bulk-import')
                    <a data-toggle="modal" data-target="#importModal" id="import_patients">Import</a></sapn>
                    @endcan
                </div>
            </div>
            @endif
            @include('patient.search')
        </div>
        <div class="patients_section active" id="patient_listing">
            @include('patient.listing')
        </div>
        @include('patient.import_ccda')
        @include('patient.compare_ccda')
    </div>


@endsection
