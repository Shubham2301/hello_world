@extends('patient-records.prints.master') @section('imports')

<link rel="stylesheet" type="text/css" href="{{ public_path('css/cataract_post_op.css') }}"> @endsection @section('content')

<div class="pdf_content">
    <div class="pdf_body">
        <div class="row">
            <div class="col-xs-12">
                <p class="section_header">Patient Information</p>
            </div>
            <div class="col-xs-4">
                <p><span class="section_title">Name</span> {{isset($data['record']['patient_name']) ? $data['record']['patient_name'] : ''}}</p>
            </div>
            <div class="col-xs-4">
                <p><span class="section_title">Date of Birth</span> {{isset($data['record']['patient_dob']) ? $data['record']['patient_dob'] : ''}}</p>
            </div>
            <div class="col-xs-4">
                <p><span class="section_title">Date of Visit</span> {{isset($data['record']['patient_dob']) ? $data['record']['patient_date_of_visit'] : ''}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <p class="section_header">Patient Reported Outcomes</p>
            </div>
            <div class="col-xs-12">
                <div class="">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'reported_outcome_diabetic_aware'])) ? 'checked' : '' }}>Patient aware of diabetic diagnosis &nbsp; &nbsp;
                    </p>
                </div>
                <div class="">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'reported_outcome_diabetic_unaware'][ 'duration'])) ? 'checked' : '' }}>Patient unaware of diabetic diagnosis &nbsp; &nbsp;
                    </p>
                </div>
                <div class="">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'reported_outcome_diabetic_medication'])) ? 'checked' : '' }}>Patient currently taking diabetic related prescription &nbsp; &nbsp;
                    </p>
                </div>
                <div class="">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'reported_outcome_diabetic_foot_exam_performed'])) ? 'checked' : '' }}>Patient's last diabetic foot exam &nbsp; &nbsp; <span style="font-weight:bold;">{{isset($data['record']['daiabetic_foot_date']) ? $data['record']['daiabetic_foot_date'] : ''}}</span>
                    </p>
                </div>
                <div class="">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'reported_outcome_micro_neurography_preformed'])) ? 'checked' : '' }}>Patient's last micro-neurography performed &nbsp; &nbsp; <span style="font-weight:bold;">{{isset($data['record']['micro-neurography_date']) ? $data['record']['micro-neurography_date'] : ''}}</span>
                    </p>
                </div>
                <div class="">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'reported_outcome_used_tobacco'])) ? 'checked' : '' }}>Patient currently used tobacco &nbsp; &nbsp;
                    </p>
                </div>
                <div class="">
                    <p class="no-margin">
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'reported_outcome_unhealthy_body_weight'])) ? 'checked' : '' }}>Screening for unhealth body weight conducted &nbsp; &nbsp;
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <p class="section_header">Examination</p>
                <span class="text_bold">Refractions:</span>
            </div>
        </div>
        <div class="row refraction_table">

            <div class="col-xs-12 border-top border-right border-left border-bottom" style="padding:0;margin-bottom:.3em;">
                <div class="col-xs-4"></div>
                <div class="col-xs-2 border-left text_bold">
                    <p>Distance</p>
                </div>
                <div class="col-xs-2"></div>
                <div class="col-xs-2"></div>
                <div class="col-xs-2 border-left text_bold">
                    <p>Near</p>
                </div>

                <div class="col-xs-4"></div>
                <div class="col-xs-2 border-top border-left text_bold">
                    <p>Right</p>
                </div>
                <div class="col-xs-2 border-top text_bold">
                    <p>Left</p>
                </div>
                <div class="col-xs-2 border-top text_bold">
                    <p>Both</p>
                </div>
                <div class="col-xs-2 border-top border-left text_bold">
                    <p>Both</p>
                </div>

                <div class="col-xs-4 border-top text_bold">
                    <p>Visual Unaided Acuity</p>
                </div>
                <div class="col-xs-2 border-top border-left">
                    <p>20/<u>{{isset($data['record']['unaided_visual_acutiy_distance_right']) ? $data['record']['unaided_visual_acutiy_distance_right'] : ''}}</u></p>
                </div>
                <div class="col-xs-2 border-top">
                    <p>20/<u>{{isset($data['record']['unaided_visual_acutiy_distance_left']) ? $data['record']['unaided_visual_acutiy_distance_left'] : ''}}</u></p>
                </div>
                <div class="col-xs-2 border-top">
                    <p>20/<u>{{isset($data['record']['unaided_visual_acutiy_distance_both']) ? $data['record']['unaided_visual_acutiy_distance_both'] : ''}}</u></p>
                </div>
                <div class="col-xs-2 border-top border-left">
                    <p>20/<u>{{isset($data['record']['unaided_visual_acutiy_near_both']) ? $data['record']['unaided_visual_acutiy_near_both'] : ''}}</u></p>
                </div>

                <div class="col-xs-4 border-top text_bold">
                    <p>Best Corrected Visual Acuity</p>
                </div>
                <div class="col-xs-2 border-top border-left">
                    <p>20/<u>{{isset($data['record']['correct_visual_acutiy_distance_right']) ? $data['record']['correct_visual_acutiy_distance_right'] : ''}}</u></p>
                </div>
                <div class="col-xs-2 border-top">
                    <p>20/<u>{{isset($data['record']['correct_visual_acutiy_distance_left']) ? $data['record']['correct_visual_acutiy_distance_left'] : ''}}</u></p>
                </div>
                <div class="col-xs-2 border-top">
                    <p>20/<u>{{isset($data['record']['correct_visual_acutiy_distance_both']) ? $data['record']['correct_visual_acutiy_distance_both'] : ''}}</u></p>
                </div>
                <div class="col-xs-2 border-top border-left">
                    <p>20/<u>{{isset($data['record']['correct_visual_acutiy_near_both']) ? $data['record']['correct_visual_acutiy_near_both'] : ''}}</u></p>
                </div>
            </div>

            <div class="col-xs-5">
                <p class="text_bold">Was refraction performed with cyclopedic agents?</p>
            </div>
            <div class="col-xs-7">
                <p>
                    <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'refraction_preformed_with_cyclopedic_agent']) && ($data[ 'record'][ 'refraction_preformed_with_cyclopedic_agent']=='Y' )) ? 'checked' : '' }}>Yes &nbsp; &nbsp;
                    <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'refraction_preformed_with_cyclopedic_agent']) && ($data[ 'record'][ 'refraction_preformed_with_cyclopedic_agent']=='N' )) ? 'checked' : '' }}>No &nbsp; &nbsp;
                </p>
            </div>

            <div class="col-xs-12">
                <p><span class="text_bold">Blood Presurres </span>&nbsp; &nbsp;<span><u>{{isset($data['record']['blood_pressure_1']) ? $data['record']['blood_pressure_1'] : ''}}</u></span>&nbsp;over&nbsp;<span><u>{{isset($data['record']['blood_pressure_2']) ? $data['record']['blood_pressure_2'] : ''}}</u></span></p>
            </div>
            <div class="col-xs-12">
                <p><span class="text_bold">IOP</span>&nbsp; &nbsp;<span>{{isset($data['record']['iop']) ? $data['record']['iop'] : ''}}</span>mm</p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <p class="section_header">Clinical Findings</p>
            </div>
            <div class="col-xs-12">
                <p class="no-margin text_bold">
                    <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'clinical_finding_diabetes_related'])) ? 'checked' : '' }}>Diabetes related &nbsp; &nbsp;
                </p>
            </div>
            @if(isset($data['record']['clinical_finding_diabetes_related']))
            <div class="col-xs-offset-1 col-xs-11 border-left clinical_finding_subsection">
                <div class="col-xs-12">
                    <p class="text_bold">Medications</p>
                </div>
                <div class="col-xs-offset-1 col-xs-12">
                    @if(isset($data['record']['diabetes_related_medication']) )
                        @foreach($data['record']['diabetes_related_medication'] as $medication)
                            <p class="">{{$medication}}</p>
                        @endforeach
                    @endif
                </div>
                <div class="col-xs-12">
                    <p class="text_bold">Diagnosis</p>
                </div>
                <div class="col-xs-offset-1 col-xs-11">
                    @if(isset($data['record']['diabetes_related_diagnosis']) )
                        <p class="text_bold">ICD-10 Codes</p>
                    @endif
                </div>
                <div class="col-xs-offset-2 col-xs-10">
                    @if(isset($data['record']['diabetes_related_diagnosis']) )
                        @foreach($data['record']['diabetes_related_diagnosis'] as $diagnosis)
                            <p class="">{{$diagnosis}}</p>
                        @endforeach
                    @endif
                </div>

                <div class="col-xs-offset-1 col-xs-5">
                    <p>No diabetic retinopathy</p>
                </div>
                <div class="col-xs-2">
                    <p>
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'diabetes_related_no_diabetic_retinopathy_OD'])) ? 'checked' : '' }}>OD &nbsp; &nbsp;
                    </p>
                </div>
                <div class="col-xs-4">
                    <p>
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'diabetes_related_no_diabetic_retinopathy_OS'])) ? 'checked' : '' }}>OS &nbsp; &nbsp;
                    </p>
                </div>


                <div class="col-xs-offset-1 col-xs-11">
                    <p>Non-proliferative Diabetic Retinopathy</p>
                </div>

                <div class="col-xs-offset-2 col-xs-4">
                    <p>Mild</p>
                </div>
                <div class="col-xs-2">
                    <p>
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'diabetes_related_non_proliferative_retinopathy_mild_OD'])) ? 'checked' : '' }}>OD &nbsp; &nbsp;
                    </p>
                </div>
                <div class="col-xs-4">
                    <p>
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'diabetes_related_non_proliferative_retinopathy_mild_OS'])) ? 'checked' : '' }}>OS &nbsp; &nbsp;
                    </p>
                </div>

                <div class="col-xs-offset-2 col-xs-4">
                    <p>Moderate</p>
                </div>
                <div class="col-xs-2">
                    <p>
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'diabetes_related_non_proliferative_retinopathy_moderate_OD'])) ? 'checked' : '' }}>OD &nbsp; &nbsp;
                    </p>
                </div>
                <div class="col-xs-4">
                    <p>
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'diabetes_related_non_proliferative_retinopathy_moderate_OS'])) ? 'checked' : '' }}>OS &nbsp; &nbsp;
                    </p>
                </div>

                <div class="col-xs-offset-2 col-xs-4">
                    <p>Severe</p>
                </div>
                <div class="col-xs-2">
                    <p>
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'diabetes_related_non_proliferative_retinopathy_severe_OD'])) ? 'checked' : '' }}>OD &nbsp; &nbsp;
                    </p>
                </div>
                <div class="col-xs-4">
                    <p>
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'diabetes_related_non_proliferative_retinopathy_severe_OS'])) ? 'checked' : '' }}>OS &nbsp; &nbsp;
                    </p>
                </div>

                <div class="col-xs-offset-1 col-xs-5">
                    <p>Proliferative Diabetic Retinopathy</p>
                </div>
                <div class="col-xs-2">
                    <p>
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'diabetes_related_proliferative_retinopathy_OD'])) ? 'checked' : '' }}>OD &nbsp; &nbsp;
                    </p>
                </div>
                <div class="col-xs-4">
                    <p>
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'diabetes_related_proliferative_retinopathy_OS'])) ? 'checked' : '' }}>OS &nbsp; &nbsp;
                    </p>
                </div>

                <div class="col-xs-offset-1 col-xs-5">
                    <p>Clinically Significant macular Edema</p>
                </div>
                <div class="col-xs-2">
                    <p>
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'diabetes_related_significant_mascular_edema_mild_OD'])) ? 'checked' : '' }}>OD &nbsp; &nbsp;
                    </p>
                </div>
                <div class="col-xs-4">
                    <p>
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'diabetes_related_significant_mascular_edema_mild_OS'])) ? 'checked' : '' }}>OS &nbsp; &nbsp;
                    </p>
                </div>

                <div class="col-xs-12">
                    <p class="text_bold">Plan</p>
                </div>
                <div class="col-xs-offset-1 col-xs-11">
                    <p>
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'diabetes_related_plan']) && $data[ 'record'][ 'diabetes_related_plan']=='monitor' ) ? 'checked' : '' }}>Monitor Only &nbsp; &nbsp;
                    </p>
                </div>
                <div class="col-xs-offset-1 col-xs-11">
                    <p>
                        <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'diabetes_related_plan']) && $data[ 'record'][ 'diabetes_related_plan']=='additional_testing' ) ? 'checked' : '' }}>Additional Testing/Treatment Recommended &nbsp; &nbsp;
                    </p>
                </div>
                <div class="col-xs-offset-1 col-xs-11">
                    <p>
                        {{(isset($data['record']['diabetes_related_plan']) && $data['record']['diabetes_related_plan'] == 'additional_testing') ? $data['record']['diabetes_related_plan_addional_text'] : ''}}
                    </p>
                </div>

            </div>
            @endif
            <div class="col-xs-12">
                <p class="no-margin text_bold">
                    <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'clinical_finding_age_related_muscular_degeneration'][ 'duration'])) ? 'checked' : '' }}>Age-related macular degeneration &nbsp; &nbsp;
                </p>
            </div>
            @if(isset($data['record']['clinical_finding_age_related_muscular_degeneration']))
            <div class="col-xs-12  border-left clinical_finding_subsection">Age-related macular degeneration</div>
            @endif
            <div class="col-xs-12">
                <p class="no-margin text_bold">
                    <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'clinical_finding_retinopathy'])) ? 'checked' : '' }}>Retinopathy &nbsp; &nbsp;
                </p>
            </div>
            @if(isset($data['record']['clinical_finding_retinopathy']))
            <div class="col-xs-12  border-left clinical_finding_subsection">Retinopathy</div>
            @endif
            <div class="col-xs-12">
                <p class="no-margin text_bold">
                    <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'clinical_finding_glaucoma'])) ? 'checked' : '' }}>Glaucoma &nbsp; &nbsp;
                </p>
            </div>
            @if(isset($data['record']['clinical_finding_glaucoma']))
            <div class="col-xs-12  border-left clinical_finding_subsection">Glaucoma</div>
            @endif
            <div class="col-xs-12">
                <p class="no-margin text_bold">
                    <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'clinical_finding_cataract'])) ? 'checked' : '' }}>Cataract &nbsp; &nbsp;
                </p>
            </div>
            @if(isset($data['record']['clinical_finding_cataract']))
            <div class="col-xs-12  border-left clinical_finding_subsection">Cataract</div>
            @endif
        </div>
        <div class="row">
            <div class="col-xs-12">
                <p class="section_header">Patient Follow-up</p>
            </div>
            <div class="col-xs-12">
                <p class="no-margin">
                    <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'follow-up_patient_educated_about_condition'])) ? 'checked' : '' }}>Patient educated in detail about their condition &nbsp; &nbsp;
                </p>
            </div>
            <div class="col-xs-12">
                <p class="no-margin">
                    <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'follow-up_refer_to_retinology'][ 'duration'])) ? 'checked' : '' }}>Refer to retinology &nbsp; &nbsp;
                </p>
            </div>
            <div class="col-xs-12">
                <p class="no-margin">
                    <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'follow-up_monitor_for_progression'])) ? 'checked' : '' }}>Monitor at clinic for progression &nbsp; &nbsp;
                </p>
            </div>
            <div class="col-xs-12">
                <p class="no-margin">
                    <input type="checkbox" style="margin-right:10px;" {{ (isset($data[ 'record'][ 'follow-up_report_sent_to_pcp'])) ? 'checked' : '' }}>Report was sent to PCP and/or endocrinologist &nbsp; &nbsp;
                </p>
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