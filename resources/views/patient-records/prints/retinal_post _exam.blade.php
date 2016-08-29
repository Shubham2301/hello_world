@extends('patient-records.prints.master')

@section('imports')

<link rel="stylesheet" type="text/css" href="{{ public_path('/css/retinal_post_exam.css') }}">

@endsection
 
@section('content')
<div class="move_to_center"></div>

<div >
    <div class="row remove_padding_margin default_margin">

        <div class="col-xs-6">
            <p class="main_info_text"> Patient Name:<span class="unit_input_text">{{ $data['patient']['firstname'].' '.$data['patient']['lastname'] }}</span></p>
        </div>

        <div class="col-xs-2">
            <p></p>
        </div>

        <div class="col-xs-4">
            <p class="main_info_text">Exam performed :{{ date('Y-m-d') }}</p>
        </div>
    </div>

    <div class="row remove_padding_margin default_margin">

        <div class="col-xs-6 remove_padding_margin">

            <div class="row remove_padding_margin">
                <div class="col-xs-11 remove_padding_margin default_border no_right_border">
                    <p  class="cat_info_text">Diagnosis:</p>
                </div>
                <div class="col-xs-1 remove_padding_margin default_border no_right_border box_background_color">
                    <p style=""> OD</p>
                </div>
            </div>

            <div class="row remove_padding_margin">

                <div class="col-xs-1 remove_padding_margin default_border " >
                    <p>&nbsp;</p>
                </div>

                <div class="col-xs-10 remove_padding_margin default_border no_right_border no_top_border">
                    <p class="default_left_padding">Non diagnostic retinopathy:</p>
                </div>

                <div class="col-xs-1 remove_padding_margin default_border box_background_color">
                    <p>
                        @if(isset($data['record']['od_NDR']))
                        <span class="glyphicon glyphicon-ok"></span> @else &nbsp; @endif
                    </p>
                </div>
            </div>

            <div class="row remove_padding_margin" >

                <div class="col-xs-1 remove_padding_margin default_border" >
                    <p>&nbsp;</p>
                </div>

                <div class="col-xs-10 remove_padding_margin default_border">
                    <p class="default_left_padding">Non-proliferative diabetic retinopathy:</p>
                </div>

                <div class="col-xs-1 remove_padding_margin default_border box_background_color">
                    <p>&nbsp;</p>
                </div>

            </div>

            <div class="row remove_padding_margin" style="">

                <div class="col-xs-1 remove_padding_margin default_border">
                    <p>&nbsp;</p>
                </div>

                <div class="col-xs-10 remove_padding_margin default_border" >
                    <div>
                       
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
                        
                        <p class="default_left_margin">
                           
                            @foreach( $npdr as $name => $value)
                                <span>
                                    <input type="checkbox" 
                                       {{ ( $data['record']['os_NPDR'] === $name || $data['record']['od_NPDR'] === $name  )?'checked':'' }}>
                                       {{ $value }}
                                </span>
                            @endforeach
                        </p>
                    </div>
                </div>

                <div class="col-xs-1 remove_padding_margin default_border box_background_color no_top_border">
                    <p>&nbsp;</p>
                </div>
            </div>

            <div class="row remove_padding_margin" >

                <div class="col-xs-1 remove_padding_margin default_border">
                    <p> &nbsp;</p>
                </div>

                <div class="col-xs-10 remove_padding_margin default_border no_right_border no_bottom_border">
                    <p class="default_left_margin">Proliferative diabetic retinopathy</p>
                </div>

                <div class="col-xs-1 remove_padding_margin default_border box_background_color">
                    <p> 
                        @if(isset($data['record']['od_PDR']))
                        <span class="glyphicon glyphicon-ok" style=""></span>
                        @else
                        &nbsp;
                        @endif 
                    </p>
                </div>
            </div>

            <div class="row remove_padding_margin" >

                <div class="col-xs-1 remove_padding_margin default_border">
                    <p>
                        &nbsp;
                    </p>
                </div>

                <div class="col-xs-10 remove_padding_margin default_border no_bottom_border no_right_border">
                    <p class="default_left_margin">Clinically significant macular edema:</p>
                </div>

                <div class="col-xs-1 remove_padding_margin default_border box_background_color">
                    <p>  @if(isset($data['record']['od_CSME']))
                        <span class="glyphicon glyphicon-ok"></span>
                        @else
                        &nbsp;
                        @endif 
                        
                    </p>
                </div>
            </div>

        </div>
        <div class="col-xs-6 remove_padding_margin">

            <div class="row remove_padding_margin default_border">

                <div class="col-xs-1 remove_padding_margin default_border box_background_color no_top_border no_left_border" >
                    <p> OS</p>
                </div>

                <div class="col-xs-11 remove_padding_margin">
                    <p class="cat_info_text">Plan</p>
                </div>

            </div>

            <div class="row remove_padding_margin default_border" >

               
                @if(!isset($data['record']['plan']))
                <?php $data['record']['plan'] = ''; ?>
                @endif
              
               
                <div class="col-xs-1 remove_padding_margin default_border box_background_color">
                    <p> @if(isset($data['record']['os_NDR']))
                        <span class="glyphicon glyphicon-ok"></span> @else &nbsp; @endif
                        </p>
                </div>
                
                <div class="col-xs-11 remove_padding_margin">
                    <p class="default_left_margin">
                        <input type="checkbox" {{ ($data['record']['plan'] === 'monitor')?'checked':'' }} > Monitor
                    </p>
                </div>
            </div>
           
            <div class="row remove_padding_margin default_border">

                <div class="col-xs-1 remove_padding_margin" style="border-right:1px solid #000;background-color: darkseagreen;border-bottom:1px solid #000;">
                    <p>&nbsp;</p>
                </div>
                
                <div class="col-xs-11 remove_padding_margin" style="border-bottom:1px solid #000;">
                    <p style="margin-left:2em;">
                    <input type="checkbox" {{ ($data['record']['plan'] === 'additional-testing')?'checked':'' }} > Additional testing/ treatement recommendations
                    </p>
                </div>

            </div>


            <div class="row remove_padding_margin" style="border-bottom:2px solid #000;border-right:2px solid #000">

                <div class="col-xs-1 remove_padding_margin" style="border-right:1px solid #000;background-color: darkseagreen;border-bottom:1px solid #000;">
                    <p> &nbsp;</p>
                </div>

                <div class="col-xs-11 remove_padding_margin">
                    <p style="margin-left:2em;">
                        Follow up: <span class="unit_input_text">{{$data['record']['followup']}}</span> months
                    </p>
                </div>

            </div>
            
            <div class="row remove_padding_margin" style="border-right:2px solid #000">
                <div class="col-xs-1 remove_padding_margin" style="border-right:1px solid #000;background-color: darkseagreen;border-bottom:1px solid #000; padding-top:1px;">
                    <p> 
                        @if(isset($data['record']['os_PDR']))
                        <span class="glyphicon glyphicon-ok" style=""></span> @else &nbsp; @endif
                    </p>
                </div>

                <div class="col-xs-11 remove_padding_margin">
                    <p style="">Ophthalmology Retinal Referral</p>
                </div>

            </div>

            <div class="row remove_padding_margin" style="border-right:2px solid #000">
                <div class="col-xs-1 remove_padding_margin" style="border:1px solid #000;background-color: darkseagreen;">
                    <p> 
                        @if(isset($data['record']['os_CSME']))
                        <span class="glyphicon glyphicon-ok" style="padding-top:1px;"></span> @else &nbsp; @endif
                    </p>  
                </div>
                <div class="col-xs-11 remove_padding_margin">
                    @if(!isset($data['record']['ORR']))
                    <?php $data['record']['ORR'] = ''; ?>
                    @endif
                   
                    @php
                        $orr =  [
                            'no' => 'No',
                            'yes' => 'Yes',
                            
                        ]
                        
                    @endphp
                   
                    <p style="margin-left:2em;">
                           
                            @foreach($orr as $name => $value)
                                <span class="{{ ($name === $data['record']['ORR'])?'active_wrap_field' :''}}"> {{ $value }}</span>
                           
                            @endforeach
                       
                            @if('yes' === $data['record']['ORR'])
                            <span class=""> Name of Md</span>
                            <span class="unit_input_text">{{ $data['record']['ORR-MD'] }}</span>
                            @endif
                    </p>
                </div>
            </div>
        </div> 
    </div>
    <div class="row remove_padding_margin" style="border:2px solid #000;margin-left:4em;margin-right:4em;">
        <div class="col-xs-3 remove_padding_margin">
            <p>Doctor Signature</p>
        </div>
        <div class="col-xs-9 remove_padding_margin">
            <span class="unit_input_text" style="width:80%;">
                <img src="data:image/png;base64,{{$data['signature']}}" alt="" class="signature_image">
            </span> &nbsp;&nbsp; 
        </div>
    </div>

</div>

@endsection