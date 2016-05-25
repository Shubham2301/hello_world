<?php

use Illuminate\Database\Seeder;

class PatientEngagementMessages extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('message_templates')->insert([
            'type' => 1,
            'message' => 'Email Patient',
            'network_id' => null,
            'stage' => 1,
            'language' => 1,
            'referral_type_id' => null,
        ]);
        
        DB::table('message_templates')->insert([
            'type' => 2,
            'message' => 'Phone Patient',
            'network_id' => null,
            'stage' => 1,
            'language' => 1,
            'referral_type_id' => null,
        ]);

        DB::table('message_templates')->insert([
            'type' => 3,
            'message' => 'Phone Patient',
            'network_id' => null,
            'stage' => 1,
            'language' => 1,
            'referral_type_id' => null,
        ]);
    }
}
