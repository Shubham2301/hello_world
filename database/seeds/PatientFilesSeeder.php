<?php

use Illuminate\Database\Seeder;

class PatientFilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('patientfiletypes')->delete();

		DB::table('patientfiletypes')->insert([

			[
				'name' => 'pre-surgery',
				'display_name' => 'Pre-surgery Sheet',
				'description' => 'Pre- surgery referral sheet',
			],
			[
				'name' => 'patient-consent-form',
				'display_name' => 'Patient Form',
				'description' => 'Signed patient consent form for co-management',
			],
			[
				'name' => 'patient-questionnaire',
				'display_name' => 'Patient-questionnaire',
				'description' => 'Pre-surgery patient questionnaire',
			]

		]);
    }
}
