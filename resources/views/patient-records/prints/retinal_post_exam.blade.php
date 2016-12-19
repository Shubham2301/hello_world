@extends('patient-records.prints.master')

@section('imports')

<link rel="stylesheet" type="text/css" href="{{ elixir('css/retinal_post_exam.css') }}">
<link rel="stylesheet" type="text/css" href="{{ public_path('css/retinal_post_exam.css') }}">

@endsection

@section('content')

<div class="move_to_center"></div>

<div class="row remove_padding_margin default_margin">

    <div class="col-xs-7">
        <p class="main_info_text"> Patient Name:<span class="unit_input_text">{{ $data['patient']['firstname'].' '.$data['patient']['lastname'] }}</span></p>
    </div>

    <div class="col-xs-5">
<p class="main_info_text">Exam performed :<span class="unit_input_text">{{ (isset($data['record']['creation_date']))?$data['record']['creation_date']:'' }}</span></p>
    </div>
</div>

 <div class="row default_margin default_border default_fontsize section_break" >


    <div class="col-xs-6 update_col_default_padding diagnosis_column">

        <div class="row">
            <div class="col-xs-12 border_bottom default_padding section_break">
                <p class="cat_info_text no-margin">Diagnosis</p>
            </div>
            <div class="col-xs-12 border_bottom default_padding">
                <div class="inline_element title">
                    <p>No diabetic retinopathy: &nbsp; &nbsp;</p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        OD&nbsp;<input type="checkbox" {{ (isset($data['record']['od_NDR'])) ? 'checked' : '' }}>
                    </p>
                    <p class="no-margin">
                        OS&nbsp;<input type="checkbox" {{ (isset($data['record']['os_NDR'])) ? 'checked' : '' }}>
                    </p>
                </div>
            </div>
            <div class="col-xs-12 border_bottom default_padding">
                <p> Non-proliferative diabetic retinopathy:</p>

                @if(!isset($data['record']['od_NPDR']))
                    <?php $data['record']['od_NPDR'] = ''; ?>
                @endif
                @if(!isset($data['record']['os_NPDR']))
                    <?php $data['record']['os_NPDR'] = ''; ?>
                @endif

                @php

                $npdr =  [
                    'mild' => 'Mild',
                    'moderate' => 'Moderate',
                    'severe' => 'Severe'
                ]

                @endphp
                <div class="subsection_padding">
                    <p class="npdr_checkbox">OD</p>
                    <p class="npdr_checkbox">
                        @foreach( $npdr as $name => $value)
                        <span>
                            <input type="checkbox" {{ ($data['record']['od_NPDR'] === $name) ? 'checked' : '' }}>&nbsp;{{ $value }}&nbsp;
                        </span>
                        @endforeach
                    </p>
                </div>
                <div class="subsection_padding">
                    <p class="npdr_checkbox">OS</p>
                    <p class="npdr_checkbox">
                        @foreach( $npdr as $name => $value)
                        <span>
                            <input type="checkbox" {{ ($data['record']['os_NPDR'] === $name) ? 'checked' : '' }}>&nbsp;{{ $value }}&nbsp;
                        </span>
                        @endforeach
                    </p>
                </div>
            </div>
            <div class="col-xs-12 border_bottom default_padding">
                <div class="inline_element title">
                    <p>Proliferative diabetic retinopathy: &nbsp; &nbsp;</p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        OD&nbsp;<input type="checkbox" {{ (isset($data['record']['od_PDR'])) ? 'checked' : '' }}>
                    </p>
                    <p class="no-margin">
                        OS&nbsp;<input type="checkbox" {{ (isset($data['record']['os_PDR'])) ? 'checked' : '' }}>
                    </p>
                </div>
            </div>
            <div class="col-xs-12 default_padding border_bottom">
                <div class="inline_element title">
                    <p>Clinically significant macular edema: &nbsp; &nbsp;</p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        OD&nbsp;<input type="checkbox" {{ (isset($data['record']['od_CSME'])) ? 'checked' : '' }}>
                    </p>
                    <p class="no-margin">
                        OS&nbsp;<input type="checkbox" {{ (isset($data['record']['os_CSME'])) ? 'checked' : '' }}>
                    </p>
                </div>
            </div>
            <div class="col-xs-12 border_bottom default_padding">
                <div class="inline_element title">
                    <p>Open Angle Glaucoma: &nbsp; &nbsp;</p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        OD&nbsp;<input type="checkbox" {{ (isset($data['record']['od_OAG'])) ? 'checked' : '' }}>
                    </p>
                    <p class="no-margin">
                        OS&nbsp;<input type="checkbox" {{ (isset($data['record']['os_OAG'])) ? 'checked' : '' }}>
                    </p>
                </div>
            </div>
            <div class="col-xs-12 default_padding">
                <div class="inline_element title">
                    <p>Cataract: &nbsp; &nbsp;</p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        OD&nbsp;<input type="checkbox" {{ (isset($data['record']['od_cataract'])) ? 'checked' : '' }}>
                    </p>
                    <p class="no-margin">
                        OS&nbsp;<input type="checkbox" {{ (isset($data['record']['os_cataract'])) ? 'checked' : '' }}>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-6">

        <div class="row">
            <div class="col-xs-12 default_padding section_break ">
                <p class="cat_info_text no-margin">Plan</p>
                @if(!isset($data['record']['plan']))
                <?php $data['record']['plan'] = ''; ?>
                @endif
            </div>
            <div class="col-xs-12 default_padding section_break transparent">
                <p class="no-margin"> <input type="checkbox" {{ ($data['record']['plan'] === 'monitor')?'checked':'' }} > &nbsp; &nbsp; Monitor
                </p>
            </div>
            <div class="col-xs-12 default_padding">
                <span>
                    <input type="checkbox" {{ ($data['record']['plan'] === 'additional-testing')?'checked':'' }}> &nbsp; &nbsp; Additional treatment recommendations
                </span>
            </div>
            <div class="col-xs-12 default_padding">
                <p class="no-margin">
                    @if(!isset($data['record']['plan_input']))
                        <?php $data['record']['plan_input'] = ''; ?>
                    @endif
                    <span class="unit_input_text plan_input {{ ($data['record']['plan_input'] === '')?'default':'' }}">{{($data['record']['plan'] === 'additional-testing')?$data['record']['plan_input']:''}}</span>
                </p>
            </div>
            <div class="col-xs-12 border_bottom default_padding transparent">
                <p class="no-margin">
                    Follow up: <span class="unit_input_text">{{$data['record']['followup']}}</span> months
                </p>
            </div>
            <div class="col-xs-12 no_bottom_border default_padding">
                <p class="no-margin">
                    Ophthalmology Retinal Referral
                </p>
                @if(!isset($data['record']['ORR']))
                    <?php $data['record']['ORR'] = ''; ?>
                @endif
                @php
                    $orr =  [
                        'no' => 'No',
                        'yes' => 'Yes',
                    ]
                @endphp
                @foreach($orr as $name => $value)
                    <p class="no-margin padding_left">
                        <input type="checkbox" {{ ($name === $data['record']['ORR']) ? 'checked' : '' }}>
                        {{ $value }}
                    </p>
                @endforeach
                @if('yes' === $data['record']['ORR'])
                    <p class="no-margin">
                        <span class="padding_left"> Name of MD: </span>
                        <span class="unit_input_text">{{ $data['record']['ORR-MD'] }}</span>
                    </p>
                @endif
            </div>
            <div class="col-xs-12 default_padding">
                <p class="no-margin">Cataract Surgery referral </p>
                @if(!isset($data['record']['surgery_referral']))
                    <?php $data['record']['surgery_referral'] = ''; ?>
                @endif
                @php
                    $surgeryReferral =  [
                        'no' => 'No',
                        'yes' => 'Yes',
                    ]

                @endphp

                @foreach($surgeryReferral as $name => $value)
                    <p class="no-margin padding_left">
                        <input type="checkbox" {{ ($name === $data['record']['surgery_referral']) ? 'checked' : '' }}>
                        {{ $value }}
                    </p>
                @endforeach
                @if('yes' === $data['record']['surgery_referral'])
                    <p class="no-margin">
                        <span class="padding_left"> Name of MD: </span>
                        <span class="unit_input_text">{{ (isset($data['record']['surgery_referral_md']))?$data['record']['surgery_referral_md']:'' }}</span>
                    </p>
                @endif
            </div>
        </div>
    </div>

</div>

<div class="row signature_box" >
    <div class="col-xs-2">
        <p class="signature_text"> Doctor Signature</p>
    </div>
    <div class="col-xs-9">

            <img src="data:image/png;base64,{{$data['signature']}}" alt="" class="signature_image">

    </div>
</div>

@endsection
