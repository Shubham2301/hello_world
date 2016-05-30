<?php

use Illuminate\Database\Seeder;

class PatientEngagementSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('actions')->insert([
            'id' => 35,
            'name' => 'request-patient-email',
            'display_name' => 'Contact by Email',
        ]);
        DB::table('actions')->insert([
            'id' => 36,
            'name' => 'request-patient-phone',
            'display_name' => 'Contact by Phone',
        ]);
        DB::table('actions')->insert([
            'id' => 37,
            'name' => 'request-patient-sms',
            'display_name' => 'Contact by SMS',
        ]);

        DB::table('stage_action')->insert([
            'action_id' => 35,
            'stage_id' => 1,
        ]);
        DB::table('stage_action')->insert([
            'action_id' => 36,
            'stage_id' => 1,
        ]);
        DB::table('stage_action')->insert([
            'action_id' => 37,
            'stage_id' => 1,
        ]);
    }
}
