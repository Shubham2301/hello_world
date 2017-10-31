<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AccountingReportSeeder extends Seeder
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
                'name' => 'practice_name',
                'display_name' => 'Practice Name',
                'report_name' => 'accounting_provider_billing',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'practice_networks',
                'display_name' => 'Networks',
                'report_name' => 'accounting_provider_billing',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'manually_added',
                'display_name' => 'Manually Added',
                'report_name' => 'accounting_provider_billing',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'practice_state',
                'display_name' => 'State',
                'report_name' => 'accounting_provider_billing',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'practice_first_appointment_date',
                'display_name' => 'Date of First Appointment',
                'report_name' => 'accounting_provider_billing',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'appointment_count',
                'display_name' => 'Appointment Count',
                'report_name' => 'accounting_provider_billing',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'location_count',
                'display_name' => 'Number of Locations',
                'report_name' => 'accounting_provider_billing',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'loc',
                'display_name' => '4PC Location Codes',
                'report_name' => 'accounting_provider_billing',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'location_names',
                'display_name' => 'Names of Locations',
                'report_name' => 'accounting_provider_billing',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'provider_count',
                'display_name' => 'Number of Providers',
                'report_name' => 'accounting_provider_billing',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'provider_names',
                'display_name' => 'Names of Providers',
                'report_name' => 'accounting_provider_billing',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'contract_start_date',
                'display_name' => 'Contract Start Date',
                'report_name' => 'accounting_provider_billing',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'contract_cancelled_date',
                'display_name' => 'Contract Cancelled Date',
                'report_name' => 'accounting_provider_billing',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'practice_discount',
                'display_name' => 'Practice Discount',
                'report_name' => 'accounting_provider_billing',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'scheduled_for_appointment',
                'display_name' => 'Scheduled for Appointment',
                'report_name' => 'accounting_payer_billing',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'appointment_attended',
                'display_name' => 'Appointment Attended',
                'report_name' => 'accounting_payer_billing',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'clinical_findings_available',
                'display_name' => 'Clinical Findings Available',
                'report_name' => 'accounting_payer_billing',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'subscriber-id',
                'display_name' => 'Subscriber ID',
                'report_name' => 'accounting_payer_billing_patient_detail',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'patient-name',
                'display_name' => 'Patient Name',
                'report_name' => 'accounting_payer_billing_patient_detail',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'appointment-date',
                'display_name' => 'Appointment Date',
                'report_name' => 'accounting_payer_billing_patient_detail',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'provider-name',
                'display_name' => 'Provider Name',
                'report_name' => 'accounting_payer_billing_patient_detail',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'appointment-status',
                'display_name' => 'Appointment Status',
                'report_name' => 'accounting_payer_billing_patient_detail',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'clinical-findings-status',
                'display_name' => 'Clinical Findings Status',
                'report_name' => 'accounting_payer_billing_patient_detail',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

        ]);
    }
}
