@extends('layouts.master')

@section('title', 'My Ocuhub')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{asset('css/referral.css')}}">
<script type="text/javascript" src="{{asset('js/referraltype.js')}}"></script>
@endsection

@section('content')


    <div class="content-section active" id="referral_types">
       <div class="row">
           <div class="col-xs-12">￼￼￼￼￼￼￼￼￼￼￼￼
               <p>Select type of patient you are referring</p>
           </div>
        </div>
        <div class="row">
            {!! Form::open(array('url' => '/patients', 'method' => 'GET', 'id' => 'form_select_patient')) !!}

                {!! Form::hidden('referraltype_id', '0', array('id' => 'form_referraltype_id')) !!}
                {!! Form::hidden('action', 'schedule_appointment', array('id' => 'form_referraltype_id')) !!}

            {!! Form::close() !!}

            <div id="existing_referraltypes">
                @foreach($referralTypes as $type)
                <div class="col-xs-2 referral_tile_outer">
                    <div class="referral_tile referr_patient" data-id="{{ $type->referraltype_id }}">
            <span class="remove_referral_type glyphicon glyphicon-remove" data-id="{{ $type->referraltype_id }}" aria-hidden="true"></span>
                        <div class="referral_tile_inner"></div>
                        <p>{{ $type->name }}</p>
                    </div>
                    <p>{{ $type->display_name }}</p>

                </div>
                @endforeach
            </div>

             <div class="col-xs-2 referral_tile_outer referral_tile_add" style="display:none">
                <div class="referral_tile " data-id="0">
                    <span class="remove_referral_type add_referral_type glyphicon glyphicon-ok" data-id="0" aria-hidden="true"></span>
                    <div class="referral_tile_inner"></div>
                    <p id='referrname'></p>
                </div>
                <p><select id="referr_list" class="referral_type_list" name="referr_list">
                    @foreach($referralTypeList as $option)
                    <option value="{{$option->id}}" data-name="{{$option->display_name}}" >{{$option->name}}</option>

                    @endforeach

                    </select>
                </p>

            </div>

            <div class="col-xs-2 referral_tile_outer">
                <div class="referral_tile configuration_tile">
                    <p>
                        <span class=" glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection
