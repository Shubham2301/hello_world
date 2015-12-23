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
            @foreach($referralTypes as $type)
            <div class="col-xs-2 referral_tile_outer">
                <div class="referral_tile" data-id="{{ $type->referraltype_id }}" data-name="{{ $type->name }}">
                    <span class="remove_referral_type glyphicon glyphicon-remove" data-id="{{ $type->referraltype_id }}" aria-hidden="true"></span>
                    <div class="referral_tile_inner" ></div>
                    <p>{{ $type->name }}</p>
                </div>
                <p>{{ $type->display_name }}</p>
            </div>
            @endforeach
            <div class="col-xs-2 referral_tile_outer">
                <div class="referral_tile configuration_tile">
                    <p>
                        <span class=" glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </p>
                </div>
            </div>
            <!--  TODO: add tile to select referral type from database and add it for current network   -->
        </div>
    </div>

@endsection
