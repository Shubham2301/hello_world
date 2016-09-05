@extends('patient-records.prints.master')

@section('imports')

<link rel="stylesheet" type="text/css" href="{{ public_path('css/retinal_post_exam.css') }}">
<link rel="stylesheet" type="text/css" href="{{ elixir('css/retinal_post_exam.css') }}">

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

 <div class="row default_margin default_border default_fontsize" >


    <div class="col-xs-6 update_col_default_padding" >

        <div class="row">
            <div class="col-xs-1 border_bottom default_padding">
                <p class="cat_info_text">Diagnosis</p>
            </div>
            <div class="col-xs-10 border_bottom default_padding">
                <p>&nbsp;</p>
            </div>
            <div class="col-xs-1 od_box_border default_padding">
                <p>OD</p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 default_padding">
                <span> &nbsp;</span>
            </div>
            <div class="col-xs-10  border_left border_bottom default_padding">
                <p> Non diagnostic retinopathy:</p>
            </div>
            <div class="col-xs-1 od_box_border default_padding">
                <p> @if(isset($data['record']['od_NDR']))
                    <span class="glyphicon glyphicon-ok"></span>
                    @else
                    &nbsp;
                    @endif
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 default_padding">
                <span> &nbsp;</span>
            </div>
            <div class="col-xs-10 border_left no_bottom_border default_padding">
                <p> Non-proliferative diabetic retinopathy:</p>
            </div>
            <div class="col-xs-1 od_box_border no_bottom_border default_padding">
                <p> &nbsp;</p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 default_padding">
                <span> &nbsp;</span>

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

            </div>
            <div class="col-xs-10 border_left border_bottom default_padding npdr_padding">
                <p class="npdr_checkbox">
                    @foreach( $npdr as $name => $value)
                    <span>
                        <input type="checkbox" {{ ( $data['record']['os_NPDR'] === $name || $data['record']['od_NPDR'] === $name  )?'checked':'' }}>&nbsp;&nbsp;{{ $value }}&nbsp;
                    </span>
                    @endforeach
                </p>
            </div>
            <div class="col-xs-1 od_box_border default_padding">
                <p class="blank_p_margin_bottom"> &nbsp;</p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 default_padding">
                <span> &nbsp;</span>
            </div>
            <div class="col-xs-10 border_left border_bottom default_padding">
                <p> Proliferative diabetic retinopathy:</p>
            </div>
            <div class="col-xs-1 od_box_border default_padding">
                <p>
                    @if(isset($data['record']['od_PDR']))
                    <span class="glyphicon glyphicon-ok" style=""></span>
                    @else
                    &nbsp;
                    @endif
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 default_padding">
                <span> &nbsp;</span>
            </div>
            <div class="col-xs-10 border_left border_bottom default_padding">
                <p> Clinically significant macular edema:</p>
            </div>
            <div class="col-xs-1 od_box_border default_padding">
                <p>
                    @if(isset($data['record']['od_CSME']))
                    <span class="glyphicon glyphicon-ok"></span>
                    @else
                    &nbsp;
                    @endif
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 default_padding">
                <span> &nbsp;</span>
            </div>
            <div class="col-xs-10 border_left border_bottom default_padding">
                <p> Cataract</p>
            </div>
            <div class="col-xs-1 od_box_border default_padding">
                <p>   @if(isset($data['record']['od_cataract']))
                        <span class="glyphicon glyphicon-ok" style=""></span>
                      @else
                        &nbsp;
                      @endif
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 default_padding">
                <span> &nbsp;</span>
            </div>
            <div class="col-xs-10 border_left  default_padding">
                <p> Open Angle Glaucoma</p>
            </div>
            <div class="col-xs-1 od_box_border  default_padding">
                <p>
                    @if(isset($data['record']['od_OAG']))
                    <span class="glyphicon glyphicon-ok" style=""></span>
                    @else
                    &nbsp;
                    @endif
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-1 default_padding">
                <span> &nbsp;</span>
            </div>
            <div class="col-xs-10 border_left  default_padding">
                <p> &nbsp;</p>
            </div>
            <div class="col-xs-1 od_box_border no_bottom_border default_padding">
                <p> &nbsp;</p>
            </div>
        </div>

    </div>

    <div class="col-xs-6" style="">

        <div class="row">
            <div class="col-xs-1 os_box_border default_padding">
                <p>OS</p>
            </div>
            <div class="col-xs-11 default_padding">
                <p class="cat_info_text">Plan</p>
                @if(!isset($data['record']['plan']))
                <?php $data['record']['plan'] = ''; ?>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 os_box_border default_padding">
                <p>
                    @if(isset($data['record']['os_NDR']))
                    <span class="glyphicon glyphicon-ok"></span>
                    @else
                    &nbsp;
                    @endif
                </p>
            </div>
            <div class="col-xs-11 default_padding">
                <span> <input type="checkbox" {{ ($data['record']['plan'] === 'monitor')?'checked':'' }} > &nbsp; &nbsp; Monitor
                </span>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 os_box_border no_bottom_border default_padding">
                <p> &nbsp;</p>
            </div>
            <div class="col-xs-11 default_padding">
                <span>
                    <input type="checkbox" {{ ($data['record']['plan'] === 'additional-testing')?'checked':'' }}> &nbsp; Additional treatment recommendations
                </span>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 os_box_border default_padding">
                <p class="blank_p_margin_bottom"> &nbsp;</p>
            </div>
            <div class="col-xs-11 border_bottom default_padding">
                <p class="blank_p_margin_bottom"> &nbsp;</p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 os_box_border default_padding">
                <p>
                    @if(isset($data['record']['os_PDR']))
                        <span class="glyphicon glyphicon-ok"></span>
                    @else
                        &nbsp;
                    @endif
                </p>
            </div>
            <div class="col-xs-11 border_bottom default_padding ">
                <p class="default_p_margin_bottom">
                    Follow up: <span class="unit_input_text">{{$data['record']['followup']}}</span> months
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 os_box_border  default_padding">
                <p>
                    @if(isset($data['record']['os_CSME']))
                        <span class="glyphicon glyphicon-ok"></span>
                    @else
                        &nbsp;
                    @endif
                </p>
            </div>
            <div class="col-xs-11 no_bottom_border default_padding">
                <p class="default_p_margin_bottom">
                    Ophthalmology Retinal Referral
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 os_box_border  default_padding">
                <p>
                    @if(isset($data['record']['os_cataract']))
                    <span class="glyphicon glyphicon-ok" style=""></span>
                    @else
                    &nbsp;
                    @endif
                </p>
            </div>
            <div class="col-xs-11 default_padding border_bottom">
                <p>
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
                    <span class="{{ ($name === $data['record']['ORR'])?'active_wrap_field' :''}}">     {{ $value }}
                    </span>
                    @endforeach
                    @if('yes' === $data['record']['ORR'])
                    <span class=""> Name of Md</span>
                    <span class="unit_input_text">{{ $data['record']['ORR-MD'] }}</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 os_box_border  default_padding">
                <p>
                    @if(isset($data['record']['os_OAG']))
                    <span class="glyphicon glyphicon-ok" style=""></span>
                    @else
                    &nbsp;
                    @endif

                </p>
            </div>
            <div class="col-xs-11 default_padding">
                <p class="">Surgery referral </p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 os_box_border no_bottom_border default_padding">
                <p>  &nbsp;
                </p>
            </div>
            <div class="col-xs-11 default_padding">
                <p>
                    @if(!isset($data['record']['surgery_referral']))
                        <?php $data['record']['surgery_referral'] = ''; ?>
                    @endif
                    @php
                        $surgeryReferral =  [
                            'yes' => 'Yes',
                            'no' => 'No',

                        ]

                    @endphp

                    @foreach($surgeryReferral as $name => $value)
                        <span class="{{ ($name === $data['record']['surgery_referral'])?'active_wrap_field' :''}}">     {{ $value }}
                        </span>
                    @endforeach
                </p>
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
