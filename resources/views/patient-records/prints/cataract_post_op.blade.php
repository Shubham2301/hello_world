@extends('patient-records.prints.master')

@section('imports')

<link rel="stylesheet" type="text/css" href="{{ public_path('css/cataract_post_op.css') }}">

@endsection

@section('content')

<div class="pdf_content">
    <div class="pdf_header" >
        <img class="pdf_logo" src="{{asset('images/web-forms/chu_vision_logo.png')}}">
    </div>
    <div class="pdf_body">
        <div class="row">
            <div class="col-xs-6">
                <p class="section_header">Referring Doctor</p>
                <p><span class="section_title">Name</span> {{isset($data['record']['doctor_name']) ? $data['record']['doctor_name'] : ''}}</p>
                <p><span class="section_title">Phone</span> {{isset($data['record']['doctor_phone']) ? $data['record']['doctor_phone'] : ''}}</p>
                <p><span class="section_title">Location</span> {{isset($data['record']['doctor_location']) ? $data['record']['doctor_location'] : ''}}</p>
                <p><span class="section_title">Date of Exam</span> {{isset($data['record']['date_of_exam']) ? $data['record']['date_of_exam'] : ''}}</p>
            </div>
            <div class="col-xs-6">
                <p class="section_header">Patient Information</p>
                <p><span class="section_title">Name</span> {{isset($data['record']['patient_name']) ? $data['record']['patient_name'] : ''}}</p>
                <p><span class="section_title">Date of Birth</span> {{isset($data['record']['patient_dob']) ? $data['record']['patient_dob'] : ''}}</p>
                <div class="row">
                    <div class="col-xs-4">
                        <p><span class="section_title">Date of Surgery</span></p>
                    </div>
                    <div class="col-xs-6">
                        <p><span class="section_title">OD</span> {{isset($data['record']['date_of_surgery_od']) ? $data['record']['date_of_surgery_od'] : ''}}</p>
                        <p><span class="section_title">OS</span> {{isset($data['record']['date_of_surgery_os']) ? $data['record']['date_of_surgery_os'] : ''}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="inline_element">
                    <p class="no-margin">
                        <span class="section_title">Type of surgery:</span> &nbsp; &nbsp;
                    </p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data['record']['type_of_surgery']) && $data['record']['type_of_surgery'] == 'cataract') ? 'checked' : '' }}>Cataract &nbsp; &nbsp;
                    </p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        with &nbsp; &nbsp;
                    </p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data['record']['iol']) && $data['record']['iol'] == 'Crystalens') ? 'checked' : '' }}>Crystalense &nbsp; &nbsp;
                    </p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data['record']['iol']) && $data['record']['iol'] == 'Toric') ? 'checked' : '' }}>Toric &nbsp; &nbsp;
                    </p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data['record']['iol']) && $data['record']['iol'] == 'Multifocal') ? 'checked' : '' }}>Multifocal &nbsp; &nbsp;
                    </p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data['record']['iol']) && $data['record']['iol'] == 'Standard') ? 'checked' : '' }}>Standard &nbsp; &nbsp;
                    </p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data['record']['iol']) && $data['record']['iol'] == 'Other') ? 'checked' : '' }}>Other &nbsp; &nbsp;
                    </p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        <span class="text_underline">{{ (isset($data['record']['iol']) && isset($data['record']['iol_other']) && $data['record']['iol'] == 'Other') ? $data['record']['iol_other'] : '' }}</span> &nbsp; &nbsp;
                    </p>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="inline_element">
                    <p class="no-margin">
                        <span class="section_title">Eye(s):</span> &nbsp; &nbsp;
                    </p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data['record']['eye']) && in_array('OD', $data['record']['eye'])) ? 'checked' : '' }}>OD &nbsp; &nbsp;
                    </p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data['record']['eye']) && in_array('OU', $data['record']['eye'])) ? 'checked' : '' }}>OU &nbsp; &nbsp;
                    </p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data['record']['eye']) && in_array('OS', $data['record']['eye'])) ? 'checked' : '' }}>OS &nbsp; &nbsp;
                    </p>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="inline_element">
                    <p class="no-margin">
                        <span class="section_title">Duration of Visit:</span> &nbsp; &nbsp;
                    </p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data['record']['duration']) && $data['record']['duration'] == '1 Day') ? 'checked' : '' }}>1 day &nbsp; &nbsp;
                    </p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data['record']['duration']) && $data['record']['duration'] == '1 Week') ? 'checked' : '' }}>1 week &nbsp; &nbsp;
                    </p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data['record']['duration']) && $data['record']['duration'] == '1 Month') ? 'checked' : '' }}>1 month &nbsp; &nbsp;
                    </p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data['record']['duration']) && $data['record']['duration'] == '3 Months') ? 'checked' : '' }}>3 months &nbsp; &nbsp;
                    </p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data['record']['duration']) && $data['record']['duration'] == 'Other') ? 'checked' : '' }}>Other &nbsp; &nbsp;
                    </p>
                </div>
                <div class="inline_element">
                    <p class="no-margin">
                        <span class="text_underline">{{ (isset($data['record']['duration']) && isset($data['record']['duration_other']) && $data['record']['duration'] == 'Other') ? $data['record']['duration_other'] : '' }}</span> &nbsp; &nbsp;
                    </p>
                </div>
            </div>
        </div>
        <div class="row pdf_box">
            <div class="col-xs-12">
                <p class="section_header">Visual Acuities</p>
            </div>
            <div class="col-xs-4">
                <div class="inline_element">
                    <p class="no-margin">
                        <span class="section_title">OD:</span> 20/<span class="text_underline">{{isset($data['record']['visual_od']) ? $data['record']['visual_od'] : '   '}}</span> &nbsp; &nbsp;
                    </p>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="inline_element">
                    <p class="no-margin">
                        <span class="section_title">OS:</span> 20/<span class="text_underline">{{isset($data['record']['visual_os']) ? $data['record']['visual_os'] : '   '}}</span> &nbsp; &nbsp;
                    </p>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="inline_element">
                    <p class="no-margin">
                        <span class="section_title">OU:</span> 20/<span class="text_underline">{{isset($data['record']['visual_ou']) ? $data['record']['visual_ou'] : '   '}}</span> &nbsp; &nbsp;
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6 circle_container">
                    <p>MRX</p>
                    <div class="circle"></div>
                </div>
                <div class="col-xs-6 circle_container">
                    <p>MRX</p>
                    <div class="circle"></div>
                </div>
            </div>
            <div class="section_break"></div>
            <div class="col-xs-2">
                <div class="inline_element">
                    <p class="text_bold"><span class="section_title">Pressures</span></p>
                </div>
            </div>
            <div class="col-xs-2">
                <p><span class="section_title">OD</span> {{isset($data['record']['pressure_od']) ? $data['record']['pressure_od'] : ''}} mmHg</p>
            </div>
            <div class="col-xs-2">
                <p><span class="section_title">OS</span> {{isset($data['record']['pressure_os']) ? $data['record']['pressure_os'] : ''}} mmHg</p>
            </div>
            <div class="col-xs-2">
                <p><span class="section_title">at time:</span> {{isset($data['record']['pressure_time']) ? $data['record']['pressure_time'] : ''}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <p class="section_header">Assessment Notes:</p>
                <p>{{isset($data['record']['assessment_notes']) ? $data['record']['assessment_notes'] : ''}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <p class="section_header">Plan:</p>
                <p>{{isset($data['record']['plan']) ? $data['record']['plan'] : ''}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <p class="section_header">Medication:</p>
            </div>
            <div class="col-xs-4">
                <div class="inline_element">
                    <p class="text_underline">{{ (isset($data['record']['medication_1_text']) && $data['record']['medication_1_text'] != "") ? $data['record']['medication_1_text'] : str_repeat('&nbsp;', 5)}}</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_1']) && $data['record']['medication_1'] == 'QD') ? 'medicine_active' : ''}}">QD</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_1']) && $data['record']['medication_1'] == 'BID') ? 'medicine_active' : ''}}">BID</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_1']) && $data['record']['medication_1'] == 'TID') ? 'medicine_active' : ''}}">TID</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_1']) && $data['record']['medication_1'] == 'QID') ? 'medicine_active' : ''}}">QID</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_1']) && $data['record']['medication_1'] == 'PRN') ? 'medicine_active' : ''}}">PRN</span></p>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="inline_element">
                    <p class="text_underline">{{ (isset($data['record']['medication_2_text']) && $data['record']['medication_2_text'] != "") ? $data['record']['medication_2_text'] : str_repeat('&nbsp;', 5)}}</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_2']) && $data['record']['medication_2'] == 'QD') ? 'medicine_active' : ''}}">QD</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_2']) && $data['record']['medication_2'] == 'BID') ? 'medicine_active' : ''}}">BID</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_2']) && $data['record']['medication_2'] == 'TID') ? 'medicine_active' : ''}}">TID</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_2']) && $data['record']['medication_2'] == 'QID') ? 'medicine_active' : ''}}">QID</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_2']) && $data['record']['medication_2'] == 'PRN') ? 'medicine_active' : ''}}">PRN</span></p>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="inline_element">
                    <p class="text_underline">{{ (isset($data['record']['medication_3_text']) && $data['record']['medication_3_text'] != "") ? $data['record']['medication_3_text'] : str_repeat('&nbsp;', 5)}}</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_3']) && $data['record']['medication_3'] == 'QD') ? 'medicine_active' : ''}}">QD</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_3']) && $data['record']['medication_3'] == 'BID') ? 'medicine_active' : ''}}">BID</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_3']) && $data['record']['medication_3'] == 'TID') ? 'medicine_active' : ''}}">TID</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_3']) && $data['record']['medication_3'] == 'QID') ? 'medicine_active' : ''}}">QID</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_3']) && $data['record']['medication_3'] == 'PRN') ? 'medicine_active' : ''}}">PRN</span></p>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="inline_element">
                    <p class="text_underline">{{ (isset($data['record']['medication_4_text']) && $data['record']['medication_4_text'] != "") ? $data['record']['medication_4_text'] : str_repeat('&nbsp;', 5)}}</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_4']) && $data['record']['medication_4'] == 'QD') ? 'medicine_active' : ''}}">QD</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_4']) && $data['record']['medication_4'] == 'BID') ? 'medicine_active' : ''}}">BID</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_4']) && $data['record']['medication_4'] == 'TID') ? 'medicine_active' : ''}}">TID</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_4']) && $data['record']['medication_4'] == 'QID') ? 'medicine_active' : ''}}">QID</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_4']) && $data['record']['medication_4'] == 'PRN') ? 'medicine_active' : ''}}">PRN</span></p>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="inline_element">
                    <p class="text_underline">{{ (isset($data['record']['medication_5_text']) && $data['record']['medication_4_text'] != "") ? $data['record']['medication_5_text'] : str_repeat('&nbsp;', 5)}}</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_5']) && $data['record']['medication_5'] == 'QD') ? 'medicine_active' : ''}}">QD</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_5']) && $data['record']['medication_5'] == 'BID') ? 'medicine_active' : ''}}">BID</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_5']) && $data['record']['medication_5'] == 'TID') ? 'medicine_active' : ''}}">TID</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_5']) && $data['record']['medication_5'] == 'QID') ? 'medicine_active' : ''}}">QID</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_5']) && $data['record']['medication_5'] == 'PRN') ? 'medicine_active' : ''}}">PRN</span></p>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="inline_element">
                    <p class="text_underline">{{ (isset($data['record']['medication_6_text']) && $data['record']['medication_6_text'] != "") ? $data['record']['medication_6_text'] : str_repeat('&nbsp;', 5)}}</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_6']) && $data['record']['medication_6'] == 'QD') ? 'medicine_active' : ''}}">QD</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_6']) && $data['record']['medication_6'] == 'BID') ? 'medicine_active' : ''}}">BID</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_6']) && $data['record']['medication_6'] == 'TID') ? 'medicine_active' : ''}}">TID</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_6']) && $data['record']['medication_6'] == 'QID') ? 'medicine_active' : ''}}">QID</span>/</p>
                </div>
                <div class="inline_element">
                    <p><span class="{{ (isset($data['record']['medication_6']) && $data['record']['medication_6'] == 'PRN') ? 'medicine_active' : ''}}">PRN</span></p>
                </div>
            </div>
        </div>
        <div class="row pdf_box">
            <div class="col-xs-2">
                <p class="signature_text"><span class="section_title">Doctor Signature</span></p>
            </div>
            <div class="col-xs-9">
                <img src="data:image/png;base64,{{$data['signature']}}" alt="" class="signature_image">
            </div>
        </div>
    </div>
</div>




@endsection
