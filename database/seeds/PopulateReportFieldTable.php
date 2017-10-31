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

            [
                'name' => 'patient_name',
                'display_name' => 'Patient Name',
                'report_name' => 'practice_appointment_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'appointment_date',
                'display_name' => 'Appointment Date',
                'report_name' => 'practice_appointment_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'appointment_type',
                'display_name' => 'Appointment Type',
                'report_name' => 'practice_appointment_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'scheduled_to_practice_location',
                'display_name' => 'Location Name',
                'report_name' => 'practice_appointment_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'scheduled_to_provider',
                'display_name' => 'Provder Name',
                'report_name' => 'practice_appointment_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'scheduled_to_practice',
                'display_name' => 'Practice Name',
                'report_name' => 'practice_appointment_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'user_name',
                'display_name' => 'User Name',
                'report_name' => 'call_center_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'patient_name',
                'display_name' => 'Patient Name',
                'report_name' => 'call_center_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'action_date_time',
                'display_name' => 'Action Date/Time',
                'report_name' => 'call_center_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'action_name',
                'display_name' => 'Action',
                'report_name' => 'call_center_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'action_result_name',
                'display_name' => 'Action Result',
                'report_name' => 'call_center_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'notes',
                'display_name' => 'Notes',
                'report_name' => 'call_center_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'appointment_date',
                'display_name' => 'Appointment Date',
                'report_name' => 'call_center_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'appointment_type',
                'display_name' => 'Appointment Type',
                'report_name' => 'call_center_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'scheduled_to_practice_location',
                'display_name' => 'Location Name',
                'report_name' => 'call_center_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'scheduled_to_provider',
                'display_name' => 'Provder Name',
                'report_name' => 'call_center_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'scheduled_to_practice',
                'display_name' => 'Practice Name',
                'report_name' => 'call_center_export',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],




        ]);
    }
}
