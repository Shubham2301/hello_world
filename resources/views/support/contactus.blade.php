@extends('layouts.master') @section('title', 'Contact us') @section('imports')
<link rel="stylesheet" type="text/css" href="{{asset('css/referral.css')}}">
<script type="text/javascript" src="{{asset('js/referraltype.js')}}"></script>
@endsection @section('sidebar') @include('layouts.sidebar') @endsection @section('content')


<div class="content-section active" id="referral_types">

    <div class="row content-row-margin">
        <div class="col-xs-12 section-header">
            <span class=""><strong>Contact OcuHub</strong></span>
            <br>
        </div>
        <div id="mk-text-block-909" style=" margin-bottom:0px;text-align: left;" class="mk-text-block  ">
            <p style="text-align: left;">Atlanta-based OcuHub connects eyecare providers, building tightly integrated networks of referral sources and enabling secure, seamless communication with other members of the health care delivery team, to drive better outcomes and practice growth. OcuHub is a health IT subsidiary of TearLab Corporation, a premier innovator in diagnostic eyecare technology.</p>
            <p style="text-align: left;">If you would like to receive more information, please contact us at support@ocuhub.com.</p>
            <div class="clearboth"></div>
        </div>
    </div>

</div>

@endsection
