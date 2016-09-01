@extends('patient-records.prints.master')

@section('imports')

<link rel="stylesheet" type="text/css" href="{{ public_path('/css/pre_op_form.css') }}">

@endsection

@section('content')
<div class="pdf_header" >
    <img src="{{asset('images/web-forms/catpostop-header.png')}}">
</div>

<div class="patient_info_section">

    <div class="row default_row_margin">
        <div class="col-xs-8">
            <p> Patient Name:<span class="unit_input_text" style="width:40%;">{{$data['patient']['firstname'].' '.$data['patient']['lastname']}}</span></p>
        </div>
        <div class="col-xs-4">
            <p> Date:<span class="unit_input_text">
                {{ (isset($data['record']['creation_date']))?$data['record']['creation_date']:'' }}   </span></p>
        </div>

    </div>

    <div class="row default_row_margin">
        <div class="col-xs-3">
            <p> DOB:<span class="unit_input_text">{{date('m/d/Y', strtotime($data['patient']['birthdate']))}}</span></p>
        </div>
        <div class="col-xs-4">
            <p>Cataract Extraction/ IOL</p>
        </div>
        <div class="col-xs-5">
            <p>
                OD on
                <span class="unit_input_text">{{ (isset($data['record']['od_cataract_date']))?$data['record']['od_cataract_date']:'' }}
                </span>

                @php

                $CIUnits =  [
                    '1 day' => '1 Day',
                    '1 week' => '1 Weeks',
                    '4 weeks' => '4 Weeks'
                    ]

                @endphp

                @foreach( $CIUnits as $CIUnitName => $CIUnitValue )
                <span class="{{(isset($data['record']['od_cataract_iol_unit']) && $data['record']['od_cataract_iol_unit'] == $CIUnitName )?'active_wrap_field':''}}"> {{ $CIUnitValue }} </span>
                @endforeach
                or
                <span class="unit_input_text">
                    {{ (isset($data['record']['od_cataract_iol_unit_other']))?$data['record']['od_cataract_iol_unit_other']:'' }}
                </span>
            </p>
            <br>
            <p>
                OS on

            <span class="unit_input_text">{{ (isset($data['record']['os_cataract_date']))?$data['record']['os_cataract_date']:'' }}</span>

                @foreach( $CIUnits as $CIUnitName => $CIUnitValue )
                <span class="{{(isset($data['record']['os_cataract_iol_unit']) && $data['record']['os_cataract_iol_unit'] == $CIUnitName )?'active_wrap_field':''}}"> {{ $CIUnitValue }} </span>
                @endforeach

                 or
                <span class="unit_input_text">
                   {{(isset($data['record']['os_cataract_iol_unit_other']))?$data['record']['os_cataract_iol_unit_other']:''}}
                </span>
            </p>
        </div>
    </div>

    <div class="row default_row_margin">
        <div class="col-xs-12">
            <p> CC:<span class="unit_input_text" style="width:96%;">{{ (isset($data['record']['cc-history']))? $data['record']['cc-history']:''  }}</span></p>
        </div>
    </div>

    <div class="row default_row_margin">
        <div class="col-xs-12">
            <p> OCULAR MEDS:<span class="unit_input_text" style="width:87%;">
                {{ (isset($data['record']['ocular_meds']))? $data['record']['ocular_meds']:''  }}
                </span></p>
        </div>
    </div>

    <div class="row default_row_margin">
        <div class="col-xs-12">
        </div>
    </div>

    <div class="row default_row_margin">
        <div class="col-xs-1">
            <span class="vsc_header"> Vsc </span>
        </div>
        <div class="col-xs-5">
            <p>
                OD <span class="unit_input_text" style="width:40%;">
                {{ (isset($data['record']['od_vsc_pre']))? $data['record']['od_vsc_pre']:''  }}

                </span> Ph <span class="unit_input_text" style="width:40%;">

                {{ (isset($data['record']['od_vsc_ph']))? $data['record']['od_vsc_ph']:''  }}
                </span>
            </p>
            <p>
                OS <span class="unit_input_text" style="width:40%;">
                {{ (isset($data['record']['os_vsc_pre']))? $data['record']['os_vsc_pre']:''  }}

                </span> Ph
                <span class="unit_input_text" style="width:40%;">
                    {{ (isset($data['record']['os_vsc_ph']))? $data['record']['os_vsc_ph']:''  }}
                </span>
            </p>
        </div>
        <div class="col-xs-6">
            <p>
                OD <span class="unit_input_text" style="width:43%">

                {{ (isset($data['record']['od_vsc_pre']))? $data['record']['od_vsc_pre']:''  }}


                </span> Ph <span class="unit_input_text" style="width:44%;">
                {{ (isset($data['record']['od_vsc_ph']))? $data['record']['od_vsc_ph']:''  }}
                </span>
            </p>

            OS <span class="unit_input_text" style="width:43%;">

            {{ (isset($data['record']['os_vsc_pre']))? $data['record']['os_vsc_pre']:''  }}
            </span> Ph <span class="unit_input_text" style="width:44%">
            {{ (isset($data['record']['os_vsc_ph'] ))? $data['record']['os_vsc_ph'] :''  }}

            </span>
            </p>
    </div>
    </div>

    <div class="row default_row_margin border_box_input">
    <div class="col-xs-1">
        <p class="mrx_header"> MRX </p>
    </div>
    <div class="col-xs-6">
        <p style="">
            OD <span class="unit_input_text" style="width:26%;">

            {{ (isset($data['record']['od_mrx_pre'] ))? $data['record']['od_mrx_pre'] :''  }}

            </span>&nbsp;- &nbsp;<span class="unit_input_text" style="width:26%;">


            {{ (isset($data['record']['od_mrx_-'] ))? $data['record']['od_mrx_-'] :''  }}
            </span>&nbsp;X &nbsp;<span class="unit_input_text" style="width:26%;">

            {{ (isset($data['record']['od_mrx_x']))? $data['record']['od_mrx_x'] :''  }}

            </span>
        </p>
        <p>
            OS <span class="unit_input_text" style="width:26%;">
            {{ (isset($data['record']['os_mrx_pre']))? $data['record']['os_mrx_pre'] :''  }}

            </span>&nbsp;- &nbsp;<span class="unit_input_text" style="width:26%;">

            {{ (isset($data['record']['os_mrx_-']))? $data['record']['os_mrx_-'] :''  }}

            </span>&nbsp;X &nbsp;<span class="unit_input_text" style="width:26%;">
            {{ (isset($data['record']['os_mrx_x']))?$data['record']['os_mrx_x'] :''  }}
            </span>
        </p>
    </div>
    <div class="col-xs-2">
        <p>20/<span class="unit_input_text" style="width:79%;">
            {{ (isset($data['record']['od_mrx_20/']))?$data['record']['od_mrx_20/'] :''  }}
            </span></p>
        <p>20/<span class="unit_input_text" style="width:79%;">
            {{ (isset($data['record']['os_mrx_20/']))?$data['record']['os_mrx_20/'] :''  }}
            </span></p>
    </div>
    <div class="col-xs-1">
        <p class="mrx_header"> ADD++ </p>
    </div>
</div>

    <div class="row default_row_margin">
        <div class="col-xs-1">
            <p><span class="text_bold">External</span></p>
        </div>
        <div class="col-xs-11">
            <p>
               <span class="unit_input_text" style="width:100%;">
                {{ (isset($data['record']['external'])) ? $data['record']['external'] :''  }}
                </span>
            </p>
        </div>
    </div>

    <div class="row default_row_margin">
    <div class="col-xs-1">
        <p><span class="text_bold">Pupils</span></p>
    </div>
    <div class="col-xs-5">
        <p>
            <span class="unit_input_text" style="width:35%;">
                {{ (isset($data['record']['od_pupils_mm']))?$data['record']['od_pupils_mm'] :''  }}

            </span>
            <span>mm&nbsp;&nbsp;OD</span>
            <span class="unit_input_text" style="width:25%;">

                {{ (isset($data['record']['os_pupils_mm']))?$data['record']['os_pupils_mm'] :''  }}
            </span>
            <span>mm&nbsp;&nbsp;OS</span>
        </p>
    </div>
    <div class="col-xs-3">

        @php
        $pupils = [
            'reaction' => [
                'reactive' => 'reactive',
                'non-reactive' => 'non-reactive',
            ],

            'apd' => [
                'present' => 'present',
                'absent' => 'absent',
            ]
        ]
        @endphp
        <p>
          @foreach($pupils['reaction'] as $name => $value)
            <span class="{{(isset($data['record']['os_pupils_effect']) && $data['record']['os_pupils_effect'] == $name)?'active_wrap_field':''}}"> {{ $value }}
            </span>
            <span>/</span>
          @endforeach
        </p>
    </div>
    <div class="col-xs-3">

       @php

       @endphp


        <p><span class="text_bold">APD&nbsp;&nbsp;</span>
            @foreach($pupils['apd'] as $name => $value)
                <span class="{{(isset($data['record']['os_pupils_attend']) && $data['record']['os_pupils_attend'] == $name)?'active_wrap_field':''}}"> {{ $value }}
                </span>
                <span>/</span>
            @endforeach
        </p>
    </div>
</div>

    <div class="row default_row_margin">
    <div class="col-xs-1">
        <p><span class="text_bold">EOM</span></p>
    </div>
    <div class="col-xs-11">
        <p>full / restricted (describe) <span class="unit_input_text" style="width:75%;">

            {{ (isset($data['record']['eom']))?$data['record']['eom'] :''  }}

            </span>
        </p>
    </div>
</div>

    <div class="row default_row_margin">
    <div class="col-xs-1">
        <p><span class="text_bold">CVF</span></p>
    </div>
    <div class="col-xs-11">
        <p>full to confrontation / restricted (describe) <span class="unit_input_text" style="width:60%;">

            {{ (isset($data['record']['cvf']))?$data['record']['cvf'] :''  }}
            </span>
        </p>
    </div>
</div>

    <div class="row default_row_margin">
    <div class="col-xs-12">
        <p><span class="unit_input_text" style="width:100%;"></span></p>
    </div>
    </div>

    <div class="row default_row_margin">

    <div class="col-xs-5">
        <div class="box_border">

            <div class="row">
                <div class="col-xs-12">
                    <p class="text_bold">SLE</p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <p class="group_text">Wound</p>
                </div>
                <div class="col-xs-6">
                   @php

                    $woundStates =  [
                        'intact' => 'intact',
                        'dehisced' => 'dehisced',
                    ]
                    @endphp
                    <p>
                        @foreach($woundStates as $name => $value )
                        <span class="{{(isset($data['record']['od_wound_state']) && $data['record']['od_wound_state'] ==$name)?'active_wrap_field':''}}"> {{ $value }} </span> /
                        @endforeach
                    </p>
                </div>
            </div>
            <div class="row">

                <div class="col-xs-4">
                    <p class="group_text">cornea</p>
                </div>
                <div class="col-xs-8">
                    <div class="row">
                        <div class="col-xs-4">
                            edema
                        </div>
                        <div class="col-xs-8">
                            <p>
                                @for($i = 0; $i < config('print_records.edema_upper_limit'); $i++)
                                   <span class="{{ (isset($data['record']['od_comea_edema']) && in_array($i, $data['record']['od_comea_edema'] ))?'active_wrap_field' :''}}" >
                                   {{ ($i==0)? $i: $i.'+' }}
                                   </span>

                                @endfor
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            folds
                        </div>
                        <div class="col-xs-8">
                            <p>

                                @for($i = 0; $i < config('print_records.edema_upper_limit'); $i++)
                                   <span class="{{ (isset($data['record']['od_comea_folds']) && in_array($i, $data['record']['od_comea_folds'] ))?'active_wrap_field' :''}}" >
                                    {{ ($i==0)? $i: $i.'+' }}
                                   </span>
                                @endfor
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <p class="group_text">A/C</p>
                </div>
                <div class="col-xs-8">
                    <div class="row">
                        <div class="col-xs-4">
                            cells
                        </div>
                        <div class="col-xs-8">
                            <p>
                                @for($i = 0; $i < config('print_records.edema_upper_limit'); $i++)
                                    <span class="{{ (isset($data['record']['od_a-c_cell']) && in_array($i, $data['record']['od_a-c_cell'] ))?'active_wrap_field' :''}}" >
                                {{ ($i==0)? $i: $i.'+' }}
                                </span>
                                @endfor
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            Flare
                        </div>
                        <div class="col-xs-8">
                            <p>
                                    @for($i = 0; $i < config('print_records.edema_upper_limit'); $i++)
                                     <span class="{{ (isset($data['record']['od_a-c_flare']) && in_array($i, $data['record']['od_a-c_flare'] ))?'active_wrap_field' :''}}" >
                                        {{ ($i==0)? $i: $i.'+' }}
                                    </span>
                                    @endfor
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <p class="group_text">Iris</p>

                    @php
                        $Iris =  [
                            'round' => 'round',
                            'dyscoric' => 'dyscoric',
                        ]

                    @endphp

                </div>
                <div class="col-xs-8">
                    <div class="row">
                        <div class="col-xs-4">
                            pupil
                        </div>

                        <div class="col-xs-8">
                            <p>
                                @foreach($Iris as $name=> $value)
                                <span class="{{(isset($data['record']['od_iris']) && $data['record']['od_iris'] ==$name)?'active_wrap_field':''}}">{{ $value }}</span>
                                @endforeach
                                <span>/</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <p class="group_text">IOL </p>
                        @php

                        $IOL =  [
                            'centered' => 'centered',
                            'decentered' => 'decentered',
                        ]
                        @endphp

                </div>
                <div class="col-xs-7">
                    <p>
                        @foreach($IOL as $name => $value)
                        <span class="{{ (isset($data['record']['od_iol']) && $data['record']['od_iol'] == $name)?'active_wrap_field':'' }}"> {{ $value }} </span> /
                        @endforeach
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <p class="group_text">Posterior Capsule</p>

                    @php
                    $posteriorCapsule = [ '1+', '2+', '3+', '4+' ]
                    @endphp

                </div>
                <div class="col-xs-8">
                    <div class="row">
                        <div class="col-xs-6 no_right_padding">
                            <span>clear </span> / <span>fibrotic</span>
                        </div>
                        <div class="col-xs-5 no_right_padding no_left_padding" >
                            <p>
                                @for($i =0; $i < sizeof($posteriorCapsule); $i++)
                                  <span> {{ $posteriorCapsule[$i] }} </span>
                                @endfor
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-3">
                    <p class="group_text">Lens</p>

                    @php
                    $lens = [
                        'clear' => 'clear',
                        'ns' => 'NS',
                        'cortical psc' => 'cortical PSC',
                        'aphakic' => 'aphakic',
                    ]
                    @endphp


                </div>
                <div class="col-xs-9">
                    <div class="row">
                        <div class="col-xs-12 no_right_padding">

                           @foreach($lens as $name => $value )

                            <span class="{{(isset($data['record']['od_lens']) && $data['record']['od_lens'] == $name)?'active_wrap_field':''}}"> {{ $value }}</span>
                           @endforeach
                           <span>/</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <p class="group_text">FUNDUS IMPRESSION
                    </p>
                </div>
                <div class="col-xs-8">
                    <div class="row">
                        <div class="col-xs-1">
                        </div>
                        <div class="col-xs-11">
                            <p>Tapp<span class="unit_input_text" style="width:80%;">

                                {{ (isset($data['record']['od_fundus_impression_tap']))?$data['record']['od_fundus_impression_tap'] :''  }}


                                </span></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-xs-1"></div>
    <div class="col-xs-5">
        <div class="box_border">

            <div class="row">
                <div class="col-xs-12">
                    <p class="text_bold">SLE</p>
                </div>

            </div>

            <div class="row">
                <div class="col-xs-4">
                    <p class="group_text">Wound</p>
                </div>
                <div class="col-xs-6">
                    <p>
                        @foreach($woundStates as $name => $value )
                        <span class="{{(isset($data['record']['os_wound_state']) && $data['record']['os_wound_state'] ==$name)?'active_wrap_field':''}}"> {{ $value }} </span>
                        @endforeach
                        <span>/</span>
                    </p>
                </div>
            </div>

            <div class="row">

                <div class="col-xs-4">
                    <p class="group_text">cornea</p>
                </div>
                <div class="col-xs-8">
                    <div class="row">
                        <div class="col-xs-4">
                            edema
                        </div>
                        <div class="col-xs-8">
                            <p>

                                @for($i = 0; $i < config('print_records.edema_upper_limit'); $i++)
                                    <span class="{{ (isset($data['record']['os_comea_edema']) && in_array($i, $data['record']['os_comea_edema'] ))?'active_wrap_field' :''}}" >
                                {{ ($i==0)? $i: $i.'+' }}
                                </span>
                                @endfor
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            folds
                        </div>
                        <div class="col-xs-8">
                            <p>
                                @for($i = 0; $i < config('print_records.edema_upper_limit'); $i++)
                                    <span class="{{ (isset($data['record']['os_comea_folds']) && in_array($i, $data['record']['os_comea_folds'] ))?'active_wrap_field' :''}}" >
                                {{ ($i==0)? $i: $i.'+' }}
                                </span>
                                @endfor
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-xs-4">
                    <p class="group_text">A/C</p>
                </div>
                <div class="col-xs-8">
                    <div class="row">
                        <div class="col-xs-4">
                            cells
                        </div>
                        <div class="col-xs-8">
                            <p>

                                @for($i = 0; $i < config('print_records.edema_upper_limit'); $i++)
                                    <span class="{{ (isset($data['record']['os_a-c_cell']) && in_array($i, $data['record']['os_a-c_cell'] ))?'active_wrap_field' :''}}" >
                                    {{ ($i==0)? $i: $i.'+' }}
                                    </span>
                                @endfor
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            Flare
                        </div>
                        <div class="col-xs-8">
                            <p>
                                @for($i = 0; $i < config('print_records.edema_upper_limit'); $i++)
                                        <span class="{{ (isset($data['record']['os_a-c_flare']) && in_array($i, $data['record']['os_a-c_flare'] ))?'active_wrap_field' :''}}" >
                                        {{ ($i==0)? $i: $i.'+' }}
                                        </span>
                                @endfor
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-xs-4">
                    <p class="group_text">Iris</p>
                </div>
                <div class="col-xs-8">
                    <div class="row">
                        <div class="col-xs-4">
                            <p> pupil </p>
                        </div>
                        <div class="col-xs-8">
                            <p>
                                @foreach($Iris as $name=> $value)
                                <span class="{{(isset($data['record']['os_iris']) && $data['record']['os_iris'] ==$name)?'active_wrap_field':''}}">{{ $value }}</span>
                                @endforeach
                                <span>/</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4">
                    <p class="group_text">IOL</p>
                </div>
                <div class="col-xs-6">
                    <p>
                        @foreach($IOL as $name => $value)
                        <span class="{{ (isset($data['record']['os_iol']) && $data['record']['os_iol'] == $name)?'active_wrap_field':'' }}"> {{ $value }} </span>
                        @endforeach

                        <span>/</span>

                    </p>
                </div>
            </div>


            <div class="row">
                <div class="col-xs-4">
                    <p class="group_text">Posterior Capsule</p>
                </div>
                <div class="col-xs-8">
                    <div class="row">
                        <div class="col-xs-6 no_right_padding">
                            <span>clear</span> / <span>fibrotic</span>
                        </div>
                        <div class="col-xs-5 no-padding" >
                            <p>
                               @for($i =0; $i < sizeof($posteriorCapsule); $i++)
                                 <span> {{ $posteriorCapsule[$i] }} </span>
                                @endfor
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-3">
                    <p class="group_text">Lens</p>
                </div>
                <div class="col-xs-9">
                    <div class="row">
                        <div class="col-xs-12 no_right_padding">

                            @foreach($lens as $name => $value )
                            <span class="{{(isset($data['record']['os_lens']) && $data['record']['os_lens'] == $name)?'active_wrap_field':''}}"> {{ $value }}</span>
                            @endforeach
                             <span>/</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4">
                    <p class="group_text">FUNDUS IMPRESSION
                    </p>
                </div>
                <div class="col-xs-8">
                    <div class="row">
                        <div class="col-xs-1">
                        </div>
                        <div class="col-xs-11">
                            <p>Tapp<span class="unit_input_text" style="width:80%;">
                                {{ (isset($data['record']['os_fundus_impression_tap']))?$data['record']['os_fundus_impression_tap'] :''  }}

                                </span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="row default_row_margin">
    <div class="col-xs-1">
        <p><span class="text_bold">Plan</span></p>
    </div>
        <div class="col-xs-3 no_right_padding">
        <p>Antibiotic <span class="unit_input_text" style="width:60%;">
            {{ (isset($data['record']['od_plan_antibiotic']))?$data['record']['od_plan_antibiotic'] :''  }}
            </span></p>
        <p>Steroid <span class="unit_input_text" style="width:60%;">

            {{ (isset($data['record']['os_plan_steroid']))?$data['record']['os_plan_steroid'] :''  }}
            </span></p>
    </div>
    <div class="col-xs-4 no_left_padding" >
        <p class= "no_bottom_margin">OD <span class="unit_input_text" style="width:60%;"></span>/Other</p>
        <p>OS </p>
    </div>
    <div class="col-xs-3">
        <p class= "no_bottom_margin">OD <span class="unit_input_text" style="width:40%;">
            {{ (isset($data['record']['od_plan_other']))?$data['record']['od_plan_other'] :''  }}

            </span></p>
        <p>OS </p>
    </div>
</div>

    <div class="row default_row_margin">
        <div class="col-xs-6 no_right_padding" >
            <p><span class="text_bold">Next Exam</span><span class="unit_input_text" style="width:75%;">

            {{ (isset($data['record']['next_exam_date']))?$data['record']['next_exam_date'] :''  }}

            </span> &nbsp;&nbsp;at</p>
            <p class="show_hint">(date)</p>
    </div>
        <div class="col-xs-6 no_left_padding" >
        <p> <span class="unit_input_text" style="width:90%;">

            {{ (isset($data['record']['next_exam_office']))?$data['record']['next_exam_office'] :''  }}
            </span> &nbsp;&nbsp;</p>
        <p class="show_hint">(office name)</p>
    </div>
</div>

    <div class="row default_row_margin">
    <div class="col-xs-6">
        <p><span class="text_bold">Signature</span>
            <span class="unit_input_text" style="width:80%;">
                <img src="data:image/png;base64,{{$data['signature']}}" alt="" class="signature_image">
            </span> &nbsp;&nbsp;
        </p>
    </div>
        <div class="col-xs-6 no_left_padding" >
        <p> O.D. Print Name<span class="unit_input_text" style="width:72%;">
            {{ (isset($data['record']['od_print_name']))?$data['record']['od_print_name'] :''  }}

            </span> &nbsp;&nbsp;</p>
    </div>
</div>

</div>
@endsection
