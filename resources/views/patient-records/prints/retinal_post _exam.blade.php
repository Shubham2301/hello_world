@extends('patient-records.prints.master') @section('content')
<div style="height:20em;"></div>

<div>
    <div class="row">

        <div class="col-xs-6">
            <p style="margin-left:4em;"> Patient Name:<span class="unit_input_text">{{ $data['patient']['firstname'].' '.$data['patient']['lastname'] }}</span></p>
        </div>

        <div class="col-xs-2">
            <p>Exam performed </p>
        </div>

        <div class="col-xs-4">
            <p>Date :{{ date('Y-m-d') }}</p>
        </div>
    </div>

    <div class="row remove_padding_margin" style="border:1px solid #000;margin-left:4em;margin-right:4em;">

        <div class="col-xs-6 remove_padding_margin">

            <div class="row remove_padding_margin" style="border-bottom:1px solid #000;border-right:1px solid #000">
                <div class="col-xs-11 remove_padding_margin">
                    <p style="align:left;">Diagnosis:</p>
                </div>
                <div class="col-xs-1 remove_padding_margin" style="border-left:1px solid #000;background-color: darkseagreen;border-bottom:1px solid #000;">
                    <p> OD</p>
                </div>
            </div>

            <div class="row remove_padding_margin" style="border-right:2px solid #000">

                <div class="col-xs-1 remove_padding_margin">
                    <p></p>
                </div>

                <div class="col-xs-10 remove_padding_margin" style="border-left:2px solid #000;border-bottom:2px solid #000;">
                    <p style="margin-left:2em;">Non diagnostic retinopathy:</p>
                </div>

                <div class="col-xs-1 remove_padding_margin" style="border-left:2px solid #000;border-bottom:1px solid #000;background-color: darkseagreen;">
                    <p style="">
                        @if(isset($data['record']['od_NDR']))
                        <span class="glyphicon glyphicon-ok" style="padding-top:1px;"></span> @else &nbsp; @endif
                    </p>
                </div>
            </div>

            <div class="row remove_padding_margin" style="border-right:2px solid #000">

                <div class="col-xs-1 remove_padding_margin">
                    <p></p>
                </div>

                <div class="col-xs-10 remove_padding_margin" style="border-left:1px solid #000;">
                    <p style="margin-left:2em;">Non-proliferative diabetic retinopathy:</p>
                </div>

                <div class="col-xs-1 remove_padding_margin" style="border-left:1px solid #000;background-color: darkseagreen;">
                    <p>
                        &nbsp;
                    </p>
                </div>

            </div>

            <div class="row remove_padding_margin" style="border-right:1px solid #000">

                <div class="col-xs-1 remove_padding_margin">
                    <p></p>
                </div>

                <div class="col-xs-10 remove_padding_margin" style="border-left:1px solid #000;border-bottom:1px solid #000;">
                    <div>
                       
                        @if(!isset($data['record']['od_NPDR']))
                        <?php $data['record']['od_NPDR'] = []; ?>
                        @endif
                        @if(!isset($data['record']['os_NPDR']))
                        <?php $data['record']['os_NPDR'] = []; ?>
                        @endif
                        <p style="margin-left:2em;">
                            <span><input type="checkbox" 
                                {{ (in_array("Mild", array_merge($data['record']['od_NPDR'],$data['record']['os_NPDR'])))?'checked':'' }}>
                           Mild
                            </span>
                            <span>
                                <input type="checkbox" {{  (in_array("Moderate", array_merge($data['record']['od_NPDR'],$data['record']['os_NPDR'])))?'checked':'' }}> Moderate
                            </span>
                            <span>
                                <input type="checkbox" {{  (in_array("Severe", array_merge($data['record']['od_NPDR'],$data['record']['os_NPDR'])))?'checked':'' }}> Severe
                            </span>
                        </p>
                    </div>
                </div>

                <div class="col-xs-1 remove_padding_margin" style="border-left:1px solid #000;
                    background-color: darkseagreen; border-bottom:1px solid #000;">
                    <p>&nbsp;</p>
                </div>
            </div>

            <div class="row remove_padding_margin" style="border-right:1px solid #000">

                <div class="col-xs-1 remove_padding_margin">
                    <p></p>
                </div>

                <div class="col-xs-10 remove_padding_margin" style="border-left:1px solid #000;border-bottom:1px solid #000;">
                    <p style="margin-left:2em;">Proliferative diabetic retinopathy</p>
                </div>

                <div class="col-xs-1 remove_padding_margin" style="border-left:1px solid #000;background-color: darkseagreen;border-bottom: 1px solid #000; padding-top:1px;">
                    <p> 
                        @if(isset($data['record']['od_PDR']))
                        <span class="glyphicon glyphicon-ok" style="padding-top:1px;"></span>
                        @else
                        &nbsp;
                        @endif 
                    </p>
                </div>
            </div>

            <div class="row remove_padding_margin" style="border-right:1px solid #000">

                <div class="col-xs-1 remove_padding_margin">
                    <p></p>
                </div>

                <div class="col-xs-10 remove_padding_margin" style="border-left:1px solid #000">
                    <p style="margin-left:2em;">Clinically significant macular edema:</p>
                </div>

                <div class="col-xs-1 remove_padding_margin" style="border-left:1px solid #000;background-color: darkseagreen; border-bottom:1px solid #000;">
                    <p>  @if(isset($data['record']['od_CSME']))
                        <span class="glyphicon glyphicon-ok" style="padding-top:1px;"></span>
                        @else
                        &nbsp;
                        @endif 
                        
                    </p>
                </div>
            </div>

        </div>

        <div class="col-xs-6 remove_padding_margin">

            <div class="row remove_padding_margin" style="border-right:1px solid #000">

                <div class="col-xs-1 remove_padding_margin" style="border-right:1px solid #000;background-color: darkseagreen; border-bottom:1px solid #000">
                    <p> OS</p>
                </div>

                <div class="col-xs-11 remove_padding_margin">
                    <p style="align:left;">Plan</p>
                </div>

            </div>

            <div class="row remove_padding_margin" style="border-right:1px solid #000">

               
                @if(!isset($data['record']['od_Plan']))
                <?php $data['record']['od_Plan'] = []; ?>
                @endif
                @if(!isset($data['record']['os_Plan']))
                <?php $data['record']['os_Plan'] = []; ?>
                @endif
               
                <div class="col-xs-1 remove_padding_margin" style="border-right:1px solid #000;background-color: darkseagreen; border-bottom:1px solid #000; padding-top:1px;">
                    <p> @if(isset($data['record']['os_NDR']))
                        <span class="glyphicon glyphicon-ok" style="padding-top:1px;"></span> @else &nbsp; @endif
                        </p>
                </div>

                <div class="col-xs-11 remove_padding_margin">
                    <p style="margin-left:2em;">
                        <input type="checkbox" {{ (in_array("Monitor", array_merge($data['record']['od_Plan'],$data['record']['os_Plan'])))?'checked':'' }} > Monitor
                    </p>
                </div>

            </div>

            <div class="row remove_padding_margin" style="border-right:2px solid #000">

                <div class="col-xs-1 remove_padding_margin" style="border-right:1px solid #000;background-color: darkseagreen;border-bottom:1px solid #000;">
                    <p>&nbsp;</p>
                </div>

                <div class="col-xs-11 remove_padding_margin" style="border-bottom:1px solid #000;">
                    <p style="margin-left:2em;">
                        <input type="checkbox" {{ (in_array("Additional testing/ treatement recommendations", array_merge($data['record']['od_Plan'],$data['record']['os_Plan'])))?'checked':''}}  > Additional testing/ treatement recommendations
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

            <div class="row remove_padding_margin" style="border-bottom:2px solid #000;border-right:2px solid #000">
                <div class="col-xs-1 remove_padding_margin" style="border-right:1px solid #000;background-color: darkseagreen;">
                    <p> 
                    
                        @if(isset($data['record']['os_CSME']))
                        <span class="glyphicon glyphicon-ok" style="padding-top:1px;"></span> @else &nbsp; @endif
                    </p>  
                </div>
                <div class="col-xs-11 remove_padding_margin">
                    <p style="margin-left:2em;">
<span class="{{ (isset($data['record']['ORR']) && 'yes'=== $data['record']['ORR'])?'active_wrap_field' :''}}"> Yes</span>

<span class=" {{ (isset($data['record']['ORR']) && 'no' === $data['record']['ORR'])?'active_wrap_field' :''}}"> NO</span>
                       
                        @if((isset($data['record']['ORR']) && 'yes' === $data['record']['ORR']))
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