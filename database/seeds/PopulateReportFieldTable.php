<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PopulateReportFieldTable extends Seeder
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
                'name' => 'total_member',
                'display_name' => 'Total Member',
                'report_name' => 'network_state_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'member_called',
                'display_name' => 'Member Called',
                'report_name' => 'network_state_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'member_reached',
                'display_name' => 'Member Reached',
                'report_name' => 'network_state_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'members_archived',
                'display_name' => 'Members Archived',
                'report_name' => 'network_state_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'in_network_past_appointments',
                'display_name' => 'In Network Past Appointments',
                'report_name' => 'network_state_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'out_network_past_appointments',
                'display_name' => 'Out Network Past Appointments',
                'report_name' => 'network_state_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'in_network_future_appointment',
                'display_name' => 'In Network Future Appointment',
                'report_name' => 'network_state_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'out_network_future_appointment',
                'display_name' => 'Out Network Future Appointment',
                'report_name' => 'network_state_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'appointments_kept',
                'display_name' => 'Appointments Kept',
                'report_name' => 'network_state_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'appointments_rescheduled',
                'display_name' => 'Appointments Rescheduled',
                'report_name' => 'network_state_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

        ]);
    }
}
