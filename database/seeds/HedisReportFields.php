<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HedisReportFields extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('report_fields')->insert([
            [
                'name' => 'product_id',
                'display_name' => 'Product ID',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'secondary_insurance',
                'display_name' => 'SecondaryInsurance',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'member_key',
                'display_name' => 'MemberKey',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'member_hcid',
                'display_name' => 'MemberHCID',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'medicare_hic',
                'display_name' => 'Medicare HIC',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'medicaid_id',
                'display_name' => 'Medicaid ID',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'member_last_name',
                'display_name' => 'Member Last Name',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'member_first_name',
                'display_name' => 'Member First Name',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'address_1',
                'display_name' => 'Address 1',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'address_2',
                'display_name' => 'Address 2',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'city',
                'display_name' => 'City',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'state',
                'display_name' => 'State',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'zipcode',
                'display_name' => 'Zipcode',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'member_email',
                'display_name' => 'MemberEmail',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'member_telephone_1',
                'display_name' => 'MemberTelephone',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'member_telephone_2',
                'display_name' => 'MemberTelephone 2',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'gender',
                'display_name' => 'Gender',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'race',
                'display_name' => 'Race',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'dob',
                'display_name' => 'DOB',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'rendering_provider_key',
                'display_name' => 'Rendering Provider Key',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'rendering_provider_name',
                'display_name' => 'Rendering Provider Name',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'rendering_provider_npi',
                'display_name' => 'Rendering Provider NPI',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'rendering_provider_tax_id',
                'display_name' => 'Rendering Provider Tax ID',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'rendering_provider_speciality_1',
                'display_name' => 'Rendering Provider Specialty (1)',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'rendering_provider_speciality_2',
                'display_name' => 'Rendering Provider Specialty (2)',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'date_of_service',
                'display_name' => 'Date of Service',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'dxpri',
                'display_name' => 'DxPri',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'dxsec1',
                'display_name' => 'DxSec1',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'dxsec2',
                'display_name' => 'DxSec2',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'dxsec3',
                'display_name' => 'DxSec3',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'dxsec4',
                'display_name' => 'DxSec4',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'dxsec5',
                'display_name' => 'DxSec5',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'dxsec6',
                'display_name' => 'DxSec6',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'dxsec7',
                'display_name' => 'DxSec7',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'dxsec8',
                'display_name' => 'DxSec8',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'procedures',
                'display_name' => 'Procedure',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'loinc',
                'display_name' => 'LOINC',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'test_name',
                'display_name' => 'Test Name',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'test_result_value',
                'display_name' => 'Test Result Value',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'ndc',
                'display_name' => 'NDC',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'rx_fill_date',
                'display_name' => 'RxFillDate',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'rx_days_supply',
                'display_name' => 'RxDays Supply',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'bmi',
                'display_name' => 'BMI',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'bmi_percentile',
                'display_name' => 'BMIPercentile',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'height',
                'display_name' => 'Height',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'weight',
                'display_name' => 'Weight',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'systolic_blood_pressure',
                'display_name' => 'Systolic Blood Pressure',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'diastolic_blood_pressure',
                'display_name' => 'Diastolic Blood Pressure',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'dialated_eye_exam_performed_by_professional',
                'display_name' => 'DilatedEyeExam Performed by eye care professional',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'place_of_service',
                'display_name' => 'Place of Service',
                'report_name' => 'hedis_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

        ]);

    }
}
