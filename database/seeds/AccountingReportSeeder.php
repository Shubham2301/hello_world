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


        ]);
    }
}
