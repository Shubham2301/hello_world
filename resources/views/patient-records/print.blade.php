<!DOCTYPE html>

<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="{{asset('lib/css/bootstrap.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{elixir('css/web_forms_print.css')}}">
        <link rel="stylesheet" type="text/css" href="{{public_path('css/web_forms_print.css')}}">
    </head>

    <body>

        <div class="pdf_header" style="">
            <img src="{{asset('images/web-forms/catpostop-header.png')}}" alt="" style="">
        </div>

        <div class="patient_info_section">

            <div class="row default_row_margin">
                <div class="col-xs-8">
                    <p> Patient Name:<span class="unit_input_text" style="width:40%;">{{$data['patient']['firstname'].' '.$data['patient']['lastname']}}</span></p>
                </div>
                <div class="col-xs-4">
                    <p> Date:<span class="unit_input_text" >
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
                        OD on <span class="unit_input_text">{{ (isset($data['record']['od_cataract_date']))?$data['record']['od_cataract_date']:'' }}  </span>


                        <span class = "{{(isset($data['record']['od_cataract_iol_unit']) && $data['record']['od_cataract_iol_unit'] =='1 day')?'active_wrap_field':''}}">1 Day </span>

                        <span class = "{{(isset($data['record']['od_cataract_iol_unit']) && $data['record']['od_cataract_iol_unit'] =='1 week')?'active_wrap_field':''}}">2 Weeks </span>

                        <span class = "{{( isset($data['record']['od_cataract_iol_unit']) && $data['record']['od_cataract_iol_unit'] =='4 weeks')?'active_wrap_field':''}}">4 Weeks </span>

                        or<span class="unit_input_text">
                        {{ (isset($data['record']['od_cataract_iol_unit_other']))?$data['record']['od_cataract_iol_unit_other']:'' }}
                        </span>
                    </p>
                    <br>
                    <p>
                        OS on <span class="unit_input_text">{{ (isset($data['record']['os_cataract_date']))?$data['record']['os_cataract_date']:'' }}</span>
                        <span class = "{{(isset($data['record']['os_cataract_iol_unit']) && $data['record']['os_cataract_iol_unit'] =='1 day')?'active_wrap_field':''}}"> 1 Day </span>
                        <span class = "{{(isset($data['record']['os_cataract_iol_unit']) && $data['record']['os_cataract_iol_unit'] =='1 week')?'active_wrap_field':''}}"> 2 Weeks </span>
                        <span class = "{{(isset($data['record']['os_cataract_iol_unit']) && $data['record']['os_cataract_iol_unit'] =='4 weeks')?'active_wrap_field':''}}"> 4 Weeks </span>
                        or<span class="unit_input_text" >
                        {{(isset($data['record']['os_cataract_iol_unit_other']))?$data['record']['os_cataract_iol_unit_other']:''}}</span>
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
                    <span style="font-weight:bold;text-align:center;font-size:1.5em;"> Vsc </span>
                </div>
                <div class="col-xs-5">
                    <p>
                        OD <span class="unit_input_text" style="width:40%;">
                        {{ (isset($data['record']['od_vsc_pre']))? $data['record']['od_vsc_pre']:''  }}

                        </span>


                        Ph <span class="unit_input_text" style="width:40%;">

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
                <div class="col-xs-6" >
                    <p style="">
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
                <p style="font-weight:bold;text-align:center;font-size:1.5em;margin-top:0.6em;"> MRX </p>
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
                <p style="">
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

                <p style="text-align:center;font-size:1.5em;margin-top:0.6em;"> ADD++ </p>
            </div>
        </div>








        <div class="row default_row_margin">
            <div class="col-xs-1">
                <p><span style="font-weight:bold;">External</span></p>
            </div>
            <div class="col-xs-11">
                <p> <span class="unit_input_text" style="width:100%;">
                    {{ (isset($data['record']['external'])) ? $data['record']['external'] :''  }}
                    </span> </p>
            </div>
        </div>

        <div class="row default_row_margin">
            <div class="col-xs-1">
                <p><span style="font-weight:bold;">Pupils</span></p>
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
                <p> <span class = "{{(isset($data['record']['os_pupils_effect']) && $data['record']['os_pupils_effect'] =='reactive')?'active_wrap_field':''}}" > reactive</span> / <span class = "{{(isset($data['record']['os_pupils_effect']) && $data['record']['os_pupils_effect'] =='non-reactive')?'active_wrap_field':''}}"> non-reactive</span> </p>
            </div>
            <div class="col-xs-3">
                <p><span style="font-weight:bold;">APD&nbsp;&nbsp;</span>
                    <span class = "{{(isset($data['record']['os_pupils_attend']) && $data['record']['os_pupils_attend'] =='present')?'active_wrap_field':''}}">present</span> /
                    <span class = "{{(isset($data['record']['os_pupils_attend']) && $data['record']['os_pupils_attend'] =='absent')?'active_wrap_field':''}}">absent</span></p>
            </div>
        </div>

        <div class="row default_row_margin">
            <div class="col-xs-1">
                <p><span style="font-weight:bold;">EOM</span></p>
            </div>
            <div class="col-xs-11">
                <p>full / restricted (describe) <span class="unit_input_text" style="width:75%;">

                    {{ (isset($data['record']['eom']))?$data['record']['eom'] :''  }}

                    </span> </p>
            </div>
        </div>

        <div class="row default_row_margin">
            <div class="col-xs-1">
                <p><span style="font-weight:bold;">CVF</span></p>
            </div>
            <div class="col-xs-11">
                <p>full to confrontation / restricted (describe) <span class="unit_input_text" style="width:60%;">

                    {{ (isset($data['record']['cvf']))?$data['record']['cvf'] :''  }}


                    </span> </p>
            </div>
        </div>

        <div class="row default_row_margin">
            <div class="col-xs-12">
                <p><span class="unit_input_text" style="width:100%;"></span></p>
            </div>
        </div>

        <div class="row default_row_margin">
            <div class="col-xs-5">
                <div style="border:1px solid #000; padding-left:4px;">

                    <div class="row">
                        <div class="col-xs-12">
                            <p style="font-weight:bold;">SLE</p>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-xs-4">
                            <p>Wound</p>
                        </div>
                        <div class="col-xs-6">
                            <p>
                                <span class = "{{(isset($data['record']['od_wound_state']) && $data['record']['od_wound_state'] =='intact')?'active_wrap_field':''}}">intact </span>
                                /
                                <span class = "{{(isset($data['record']['od_wound_state']) && $data['record']['od_wound_state'] =='dehisced')?'active_wrap_field':''}}">dehisced </span></p>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-xs-4">
                            <p>cornea</p>
                        </div>
                        <div class="col-xs-8">
                            <div class="row">
                                <div class="col-xs-4">
                                    edema
                                </div>
                                <div class="col-xs-8">
                                    <p>
                                        <span class="{{ (isset($data['record']['od_comea_edema']) && in_array('0', $data['record']['od_comea_edema']))?'active_wrap_field' :''}}">0</span>

                                        <span class="{{ (isset($data['record']['od_comea_edema']) && in_array('1', $data['record']['od_comea_edema']))?'active_wrap_field' :''}}">1+</span>

                                        <span class="{{ (isset($data['record']['od_comea_edema']) && in_array('2', $data['record']['od_comea_edema']))?'active_wrap_field' :''}}">2+</span>

                                        <span class="{{ (isset($data['record']['od_comea_edema']) && in_array('3', $data['record']['od_comea_edema']))?'active_wrap_field' :''}}">3+</span>

                                        <span class="{{ (isset($data['record']['od_comea_edema']) && in_array('4', $data['record']['od_comea_edema']))?'active_wrap_field' :''}}" >4+</span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    folds
                                </div>
                                <div class="col-xs-8">
                                    <p>
                                        <span class="{{ (isset($data['record']['od_comea_folds']) && in_array('0', $data['record']['od_comea_folds']))?'active_wrap_field' :''}}">0</span>

                                        <span class="{{ (isset($data['record']['od_comea_folds']) && in_array('1', $data['record']['od_comea_folds']))?'active_wrap_field' :''}}">1+</span>

                                        <span class="{{ (isset($data['record']['od_comea_folds']) && in_array('2', $data['record']['od_comea_folds']))?'active_wrap_field' :''}}">2+</span>

                                        <span class="{{ (isset($data['record']['od_comea_folds']) && in_array('3', $data['record']['od_comea_folds']))?'active_wrap_field' :''}}">3+</span>

                                        <span class="{{ (isset($data['record']['od_comea_folds']) && in_array('4', $data['record']['od_comea_folds']))?'active_wrap_field' :''}}">4+</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-xs-4">
                            <p>A/C</p>
                        </div>
                        <div class="col-xs-8">
                            <div class="row">
                                <div class="col-xs-4">
                                    cells
                                </div>
                                <div class="col-xs-8">
                                    <p>
                                        <span class="{{ (isset($data['record']['od_a-c_cell']) && in_array('0', $data['record']['od_a-c_cell']))?'active_wrap_field' :''}}" >0</span>

                                        <span class="{{ (isset($data['record']['od_a-c_cell']) && in_array('1', $data['record']['od_a-c_cell']))?'active_wrap_field' :''}}">1+</span>

                                        <span class="{{ (isset($data['record']['od_a-c_cell']) && in_array('2', $data['record']['od_a-c_cell']))?'active_wrap_field' :''}}">2+</span>

                                        <span class="{{ (isset($data['record']['od_a-c_cell']) && in_array('3', $data['record']['od_a-c_cell']))?'active_wrap_field' :''}}">3+</span>

                                        <span class="{{ (isset($data['record']['od_a-c_cell']) && in_array('4', $data['record']['od_a-c_cell']))?'active_wrap_field' :''}}">4+</span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    Flare
                                </div>
                                <div class="col-xs-8">
                                    <p>
                                        <span class="{{ (isset($data['record']['od_a-c_flare']) && in_array('0', $data['record']['od_a-c_flare']))?'active_wrap_field' :''}}">0</span>

                                        <span class="{{ (isset($data['record']['od_a-c_flare']) && in_array('1', $data['record']['od_a-c_flare']))?'active_wrap_field' :''}}">1+</span>

                                        <span class="{{ (isset($data['record']['od_a-c_flare']) && in_array('2', $data['record']['od_a-c_flare']))?'active_wrap_field' :''}}">2+</span>

                                        <span class="{{ (isset($data['record']['od_a-c_flare']) && in_array('3', $data['record']['od_a-c_flare']))?'active_wrap_field' :''}}">3+</span>

                                        <span class="{{ (isset($data['record']['od_a-c_flare']) && in_array('4', $data['record']['od_a-c_flare']))?'active_wrap_field' :''}}">4+</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-xs-4">
                            <p>Iris</p>
                        </div>
                        <div class="col-xs-8">
                            <div class="row">
                                <div class="col-xs-4">
                                    <span class = "{{(isset($data['record']['od_iris']) && $data['record']['od_iris'] =='pupil')?'active_wrap_field':''}}">pupil</span>
                                </div>
                                <div class="col-xs-8">
                                    <p><span class = "{{(isset($data['record']['od_iris']) && $data['record']['od_iris'] =='round')?'active_wrap_field':''}}" >round</span>
                                        <span>/</span>
                                        <span class = "{{(isset($data['record']['od_iris']) && $data['record']['od_iris'] =='dyscoric')?'active_wrap_field':''}}">dyscoric</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-4">
                            <p>IOL</p>
                        </div>
                        <div class="col-xs-7">
                            <p>
                                <span class = "{{(isset($data['record']['od_iol']) && $data['record']['od_iol'] =='centered')?'active_wrap_field':''}}">centered </span>
                                /
                                <span class = "{{(isset($data['record']['od_iol']) && $data['record']['od_iol'] =='decentered')?'active_wrap_field':''}}">decentered</span>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-4">
                            <p>Posterior Capsule</p>
                        </div>
                        <div class="col-xs-8">
                            <div class="row">
                                <div class="col-xs-6" style="padding-right:0;">
                                    <span>clear </span>  / <span>fibrotic</span>
                                </div>
                                <div class="col-xs-5" style="padding-left:0; padding-right:0;">
                                    <p><span>1+</span>
                                        <span>2+</span>
                                        <span>3+</span>
                                        <span>4+</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-3">
                            <p>Lens</p>
                        </div>
                        <div class="col-xs-9">
                            <div class="row">
                                <div class="col-xs-12" style="padding-right:0;">
                                    <span class = "{{(isset($data['record']['od_lens']) && $data['record']['od_lens'] =='clear')?'active_wrap_field':''}}">clear</span> /
                                    <span class = "{{(isset($data['record']['od_lens']) && $data['record']['od_lens'] =='ns')?'active_wrap_field':''}}">NS</span> /
                                    <span class = "{{(isset($data['record']['od_lens']) && $data['record']['od_lens'] =='cortical psc')?'active_wrap_field':''}}">cortical PSC</span> /
                                    <span class = "{{(isset($data['record']['od_lens']) && $data['record']['od_lens'] =='aphakic')?'active_wrap_field':''}}">aphakic</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-4">
                            <p style="font-weight:bold; font-size:1em;">FUNDUS IMPRESSION
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
                <div style="border:1px solid; padding-left:4px;">

                    <div class="row">
                        <div class="col-xs-12">
                            <p style="font-weight:bold;">SLE</p>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-xs-4">
                            <p>Wound</p>
                        </div>
                        <div class="col-xs-6">
                            <p>

                                <span class = "{{(isset($data['record']['os_wound_state']) && $data['record']['os_wound_state'] =='intact')?'active_wrap_field':''}}">intact</span> /


                                <span class = "{{(isset($data['record']['os_wound_state']) && $data['record']['os_wound_state'] =='dehisced')?'active_wrap_field':''}}">dehisced </span></p>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-xs-4">
                            <p>cornea</p>
                        </div>
                        <div class="col-xs-8">
                            <div class="row">
                                <div class="col-xs-4">
                                    edema
                                </div>
                                <div class="col-xs-8">
                                    <p>

                                        <span class="{{ (isset($data['record']['os_comea_edema']) && in_array('0', $data['record']['os_comea_edema']))?'active_wrap_field' :''}}">0</span>

                                        <span class="{{ (isset($data['record']['os_comea_edema']) && in_array('1', $data['record']['os_comea_edema']))?'active_wrap_field' :''}}">1+</span>

                                        <span class="{{ (isset($data['record']['os_comea_edema']) && in_array('2', $data['record']['os_comea_edema']))?'active_wrap_field' :''}}">2+</span>

                                        <span class="{{ (isset($data['record']['os_comea_edema']) && in_array('3', $data['record']['os_comea_edema']))?'active_wrap_field' :''}}">3+</span>

                                        <span class="{{ (isset($data['record']['os_comea_edema']) && in_array('4', $data['record']['os_comea_edema']))?'active_wrap_field' :''}}">4+</span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    folds
                                </div>
                                <div class="col-xs-8">
                                    <p>
                                        <span class="{{ (isset($data['record']['os_comea_folds']) && in_array('0', $data['record']['os_comea_folds']))?'active_wrap_field' :''}}">0</span>

                                        <span class="{{ (isset($data['record']['os_comea_folds']) && in_array('1', $data['record']['os_comea_folds']))?'active_wrap_field' :''}}">1+</span>

                                        <span class="{{ (isset($data['record']['os_comea_folds']) && in_array('2', $data['record']['os_comea_folds']))?'active_wrap_field' :''}}">2+</span>

                                        <span class="{{ (isset($data['record']['os_comea_folds']) && in_array('3', $data['record']['os_comea_folds']))?'active_wrap_field' :''}}">3+</span>

                                        <span class="{{ (isset($data['record']['os_comea_folds']) && in_array('4', $data['record']['os_comea_folds']))?'active_wrap_field' :''}}">4+</span>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-xs-4">
                            <p>A/C</p>
                        </div>
                        <div class="col-xs-8">
                            <div class="row">
                                <div class="col-xs-4">
                                    cells
                                </div>
                                <div class="col-xs-8">
                                    <p>

                                        <span class="{{ (isset($data['record']['os_a-c_cell']) && in_array('0', $data['record']['os_a-c_cell']))?'active_wrap_field' :''}}">0</span>

                                        <span class="{{ (isset($data['record']['os_a-c_cell']) && in_array('1', $data['record']['os_a-c_cell']))?'active_wrap_field' :''}}">1+</span>

                                        <span class="{{ (isset($data['record']['os_a-c_cell']) && in_array('2', $data['record']['os_a-c_cell']))?'active_wrap_field' :''}}">2+</span>

                                        <span class="{{ (isset($data['record']['os_a-c_cell']) && in_array('3', $data['record']['os_a-c_cell']))?'active_wrap_field' :''}}">3+</span>

                                        <span class="{{ (isset($data['record']['os_a-c_cell']) && in_array('4', $data['record']['os_a-c_cell']))?'active_wrap_field' :''}}">4+</span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    Flare
                                </div>
                                <div class="col-xs-8">
                                    <p>
                                        <span class="{{ (isset($data['record']['os_a-c_flare']) && in_array('0', $data['record']['os_a-c_flare']))?'active_wrap_field' :''}}">0</span>

                                        <span class="{{ (isset($data['record']['os_a-c_flare']) && in_array('1', $data['record']['os_a-c_flare']))?'active_wrap_field' :''}}">1+</span>

                                        <span class="{{ (isset($data['record']['os_a-c_flare']) && in_array('2', $data['record']['os_a-c_flare']))?'active_wrap_field' :''}}">2+</span>

                                        <span class="{{ (isset($data['record']['os_a-c_flare']) && in_array('3', $data['record']['os_a-c_flare']))?'active_wrap_field' :''}}">3+</span>

                                        <span class="{{ (isset($data['record']['os_a-c_flare']) && in_array('4', $data['record']['os_a-c_flare']))?'active_wrap_field' :''}}">4+</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-xs-4">
                            <p>Iris</p>
                        </div>
                        <div class="col-xs-8">
                            <div class="row">
                                <div class="col-xs-4">
                                    <span class = "{{(isset($data['record']['os_iris']) && $data['record']['os_iris'] =='pupil')?'active_wrap_field':''}}">  pupil</span>
                                </div>
                                <div class="col-xs-8">
                                    <p><span class = "{{(isset($data['record']['os_iris']) && $data['record']['os_iris'] =='round')?'active_wrap_field':''}}">round</span>
                                        <span>/</span>
                                        <span class = "{{(isset($data['record']['os_iris']) && $data['record']['os_iris'] =='dyscoric')?'active_wrap_field':''}}">dyscoric</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-4">
                            <p>IOL</p>
                        </div>
                        <div class="col-xs-6">
                            <p>

                                <span class = "{{(isset($data['record']['os_iol']) && $data['record']['os_iol'] =='centered')?'active_wrap_field':''}}">centered</span>

                                /


                                <span class = "{{(isset($data['record']['os_iol']) && $data['record']['os_iol'] =='decentered')?'active_wrap_field':''}}">decentered </span></p>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-xs-4">
                            <p>Posterior Capsule</p>
                        </div>
                        <div class="col-xs-8">
                            <div class="row">
                                <div class="col-xs-6" style="padding-right:0;">
                                    <span>clear</span> / <span>fibrotic</span>
                                </div>
                                <div class="col-xs-5" style="padding-left:0; padding-right:0;">
                                    <p> <span>1+</span>
                                        <span>2+</span>
                                        <span>3+</span>
                                        <span>4+</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-3">
                            <p>Lens</p>
                        </div>
                        <div class="col-xs-9">
                            <div class="row">
                                <div class="col-xs-12" style="padding-right:0;">
                                    <span class = "{{(isset($data['record']['os_lens']) && $data['record']['os_lens'] =='clear')?'active_wrap_field':''}}">clear</span>
                                    /
                                    <span class = "{{(isset($data['record']['os_lens']) && $data['record']['os_lens'] =='ns')?'active_wrap_field':''}}">NS</span>
                                    /
                                    <span class = "{{(isset($data['record']['os_lens']) && $data['record']['os_lens'] =='cortical psc')?'active_wrap_field':''}}">cortical PSC</span>
                                    /
                                    <span class = "{{(isset($data['record']['os_lens']) && $data['record']['os_lens'] =='aphakic')?'active_wrap_field':''}}">aphakic</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-4">
                            <p style="font-weight:bold; font-size:1em;">FUNDUS IMPRESSION
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
                <p><span style="font-weight:bold;">Plan</span></p>
            </div>
            <div class="col-xs-3" style="padding-right:0px;">
                <p>Antibiotic <span class="unit_input_text" style="width:60%;">
                    {{ (isset($data['record']['od_plan_antibiotic']))?$data['record']['od_plan_antibiotic'] :''  }}
                    </span></p>
                <p>Steroid <span class="unit_input_text" style="width:60%;">

                    {{ (isset($data['record']['os_plan_steroid']))?$data['record']['os_plan_steroid'] :''  }}
                    </span></p>
            </div>
            <div class="col-xs-4" style="padding-left:0px;">
                <p style="maring-bottom:0px;">OD <span class="unit_input_text" style="width:60%;"></span>/Other</p>
                <p>OS </p>
            </div>
            <div class="col-xs-3">
                <p style="maring-bottom:0px;">OD <span class="unit_input_text" style="width:40%;">
                    {{ (isset($data['record']['od_plan_other']))?$data['record']['od_plan_other'] :''  }}

                    </span></p>
                <p>OS </p>
            </div>
        </div>

        <div class="row default_row_margin">
            <div class="col-xs-6" style="padding-right:0px;">
                <p><span style="font-weight:bold;">Next Exam</span><span class="unit_input_text" style="width:75%;">

                    {{ (isset($data['record']['next_exam_date']))?$data['record']['next_exam_date'] :''  }}

                    </span> &nbsp;&nbsp;at</p>
                <p style="margin-left: 34%;margin-top: -1em;font-style: italic;">(date)</p>
            </div>
            <div class="col-xs-6" style="padding-left:0px;">
                <p> <span class="unit_input_text" style="width:90%;">

                    {{ (isset($data['record']['next_exam_office']))?$data['record']['next_exam_office'] :''  }}
                    </span> &nbsp;&nbsp;</p>
                <p style="margin-left: 34%;margin-top: -1em;font-style: italic;">(office name)</p>
            </div>
        </div>

        <div class="row default_row_margin">
            <div class="col-xs-6" style="padding-right:0px;">
                <p><span style="font-weight:bold;">Signature</span> <span class="unit_input_text" style="width:80%;"></span> &nbsp;&nbsp;</p>
            </div>
            <div class="col-xs-6" style="padding-left:0px;">
                <p> O.D. Print Name<span class="unit_input_text" style="width:72%;">
                    {{ (isset($data['record']['od_print_name']))?$data['record']['od_print_name'] :''  }}

                    </span> &nbsp;&nbsp;</p>
            </div>
        </div>



        </div>

    </body>
</html>
