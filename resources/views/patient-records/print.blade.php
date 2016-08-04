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
                    <p> Date:<span class="unit_input_text" style="width;">
                        {{ (isset($data['record']['os_surgery_date']))?$data['record']['os_surgery_date']:'' }}   </span></p>
                </div>

            </div>

            <div class="row default_row_margin">
                <div class="col-xs-3">
                    <p> DOB:<span class="unit_input_text" style="">{{$data['patient']['birthdate']}}</span></p>
                </div>
                <div class="col-xs-4">
                    <p>Cataract Extraction/ IOL</p>
                </div>
                <div class="col-xs-5">
                    <p>
                        OD on <span class="unit_input_text" style="">12</span>/<span class="unit_input_text" style="">12</span>/<span class="unit_input_text" style="">12</span> 1 Day 2 Weeks 4 Weeks or
                        <span class="unit_input_text" style="">12</span>
                    </p>
                    <br>
                    <p>
                        OS on <span class="unit_input_text" style="">12</span>/<span class="unit_input_text" style="">12</span>/<span class="unit_input_text" style="">12</span> 1 Day 2 Weeks 4 Weeks or
                        <span class="unit_input_text" style="">12</span>
                    </p>
                </div>
            </div>

            <div class="row default_row_margin">
                <div class="col-xs-12">
                    <p> CC:<span class="unit_input_text" style="width:96%;">
                        {{ (isset($data['record']['os_cc-history'][0]))?$data['record']['os_cc-history'][0]:'' }}
                    </span></p>
                </div>
            </div>

            <div class="row default_row_margin">
                <div class="col-xs-12">
                    <p> OCULAR MEDS:<span class="unit_input_text" style="width:87%;"></span></p>
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
                    <p style="">
                        OD <span class="unit_input_text" style="width:40%;">
                        {{ (isset($data['record']['os_va_sc']))?$data['record']['os_va_sc']:'' }}
                        </span> Ph <span class="unit_input_text" style="width:40%;"></span>
                    </p>
                    <p>
                        OS <span class="unit_input_text" style="width:40%;">
                        {{ (isset($data['record']['od_va_sc']))?$data['record']['od_va_sc']:'' }}
                        </span> Ph <span class="unit_input_text" style="width:40%;"></span>
                    </p>
                </div>
                <div class="col-xs-6" 4 <p style="">
                OD <span class="unit_input_text" style="width:43%">asd</span> Ph <span class="unit_input_text" style="width:44%;">asd</span>
                </p>
            <p>
                OS <span class="unit_input_text" style="width:43%;">asd</span> Ph <span class="unit_input_text" style="width:44%">asd</span>
            </p>
        </div>
        </div>

    <div class="row default_row_margin border_box_input">
        <div class="col-xs-1">
            <p style="font-weight:bold;text-align:center;font-size:1.5em;margin-top:0.6em;"> MRX </p>
        </div>
        <div class="col-xs-6">
            <p style="">
                OD <span class="unit_input_text" style="width:26%;"></span>&nbsp;- &nbsp;<span class="unit_input_text" style="width:26%;"></span>&nbsp;X &nbsp;<span class="unit_input_text" style="width:26%;">
                </p>
            <p style="">
                OS <span class="unit_input_text" style="width:26%;"></span>&nbsp;- &nbsp;<span class="unit_input_text" style="width:26%;"></span>&nbsp;X &nbsp;<span class="unit_input_text" style="width:26%;">
                </p>
        </div>
        <div class="col-xs-2">
            <p>20/<span class="unit_input_text" style="width:79%;"></span></p>
            <p>20/<span class="unit_input_text" style="width:79%;"></span></p>
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
            <p> <span class="unit_input_text" style="width:100%;">asd</span> </p>
        </div>
    </div>

    <div class="row default_row_margin">
        <div class="col-xs-1">
            <p><span style="font-weight:bold;">Pupils</span></p>
        </div>
        <div class="col-xs-5">
            <p>
                <span class="unit_input_text" style="width:35%;">asd</span>
                <span>mm&nbsp;&nbsp;OD</span>
                <span class="unit_input_text" style="width:35%;">asd</span>
            </p>
        </div>
        <div class="col-xs-3">
            <p> <span> reactive</span> / <span> non-reactive</span> </p>
        </div>
        <div class="col-xs-3">
            <p><span style="font-weight:bold;">APD&nbsp;&nbsp;</span><span>present</span> / <span>absent</span35</p>
                </div>
        </div>

        <div class="row default_row_margin">
            <div class="col-xs-1">
                <p><span style="font-weight:bold;">EOM</span></p>
            </div>
            <div class="col-xs-11">
                <p>full / restricted (describe) <span class="unit_input_text" style="width:75%;">asd</span> </p>
            </div>
        </div>

        <div class="row default_row_margin">
            <div class="col-xs-1">
                <p><span style="font-weight:bold;">CVF</span></p>
            </div>
            <div class="col-xs-11">
                <p>full to confrontation / restricted (describe) <span class="unit_input_text" style="width:60%;">asd</span> </p>
            </div>
        </div>

        <div class="row default_row_margin">
            <div class="col-xs-12">
                <p><span class="unit_input_text" style="width:100%;">asd</span></p>
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
                            <p>intact / dehisced</p>
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
                                        <span class="active_wrap_field">0</span>
                                        <span>1+</span>
                                        <span>2+</span>
                                        <span>3+</span>
                                        <span class="active_wrap_field" >4+</span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    folds
                                </div>
                                <div class="col-xs-8">
                                    <p> <span>0</span>
                                        <span>1+</span>
                                        <span>2+</span>
                                        <span>3+</span>
                                        <span>4+</span>
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
                                    <p><span>0</span>
                                        <span>1+</span>
                                        <span>2+</span>
                                        <span>3+</span>
                                        <span>4+</span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    Flare
                                </div>
                                <div class="col-xs-8">
                                    <p> <span>0</span>
                                        <span>1+</span>
                                        <span>2+</span>
                                        <span>3+</span>
                                        <span>4+</span>
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
                                    pupil
                                </div>
                                <div class="col-xs-8">
                                    <p><span>round</span>
                                        <span>/</span>
                                        <span>dyscoric</span>
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
                            <p><span class="active_wrap_field">centered </span> / <span>decentered</span></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-4">
                            <p>Posterior Capsule</p>
                        </div>
                        <div class="col-xs-8">
                            <div class="row">
                                <div class="col-xs-6" style="padding-right:0;">
                                    clear / fibrotic
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
                                    <span>clear</span> / <span>NS</span> / <span>cortical PSC</span> / <span>aphakic</span>
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
                                    <p>Tapp<span class="unit_input_text" style="width:80%;">asd</span></p>
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
                            <p>intact / dehisced</p>
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
                                    <p><span>0</span>
                                        <span>1+</span>
                                        <span>2+</span>
                                        <span>3+</span>
                                        <span>4+</span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    folds
                                </div>
                                <div class="col-xs-8">
                                    <p> <span>0</span>
                                        <span>1+</span>
                                        <span>2+</span>
                                        <span>3+</span>
                                        <span>4+</span>
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
                                    <p><span>0</span>
                                        <span>1+</span>
                                        <span>2+</span>
                                        <span>3+</span>
                                        <span>4+</span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    Flare
                                </div>
                                <div class="col-xs-8">
                                    <p> <span>0</span>
                                        <span>1+</span>
                                        <span>2+</span>
                                        <span>3+</span>
                                        <span>4+</span>
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
                                    pupil
                                </div>
                                <div class="col-xs-8">
                                    <p><span>round</span>
                                        <span>/</span>
                                        <span>dyscoric</span>
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
                            <p>centered / decentered</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-4">
                            <p>Posterior Capsule</p>
                        </div>
                        <div class="col-xs-8">
                            <div class="row">
                                <div class="col-xs-6" style="padding-right:0;">
                                    clear / fibrotic
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
                                    <span>clear</span> / <span>NS</span> / <span>cortical PSC</span> / <span>aphakic</span>
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
                                    <p>Tapp<span class="unit_input_text" style="width:80%;">asd</span></p>
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
                <p>Antibiotic <span class="unit_input_text" style="width:60%;"></span></p>
                <p>Steroid <span class="unit_input_text" style="width:60%;"></span></p>
            </div>
            <div class="col-xs-4" style="padding-left:0px;">
                <p style="maring-bottom:0px;">OD <span class="unit_input_text" style="width:60%;"></span>/Other</p>
                <p>OS </p>
            </div>
            <div class="col-xs-3">
                <p style="maring-bottom:0px;">OD <span class="unit_input_text" style="width:40%;"></span></p>
                <p>OS </p>
            </div>
        </div>

        <div class="row default_row_margin">
            <div class="col-xs-6" style="padding-right:0px;">
                <p><span style="font-weight:bold;">Next Exam</span><span class="unit_input_text" style="width:75%;"></span> &nbsp;&nbsp;at</p>
                <p style="margin-left: 34%;margin-top: -1em;font-style: italic;">(date)</p>
            </div>
            <div class="col-xs-6" style="padding-left:0px;">
                <p> <span class="unit_input_text" style="width:90%;"></span> &nbsp;&nbsp;</p>
                <p style="margin-left: 34%;margin-top: -1em;font-style: italic;">(office name)</p>
            </div>
        </div>

        <div class="row default_row_margin">
            <div class="col-xs-6" style="padding-right:0px;">
                <p><span style="font-weight:bold;">Signature</span> <span class="unit_input_text" style="width:80%;"></span> &nbsp;&nbsp;</p>
            </div>
            <div class="col-xs-6" style="padding-left:0px;">
                <p> O.D. Print Name<span class="unit_input_text" style="width:72%;"></span> &nbsp;&nbsp;</p>
            </div>
        </div>

    </div>

    </body>

</html>
