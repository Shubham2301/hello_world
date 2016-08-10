<?php

use Illuminate\Database\Seeder;

class ProviderTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('provider_types')->insert([
            [
                'id' => 18,
                'name' => 'Ophthalmologist',
            ],
            [
                'id' => 41,
                'name' => 'Optometrist',
            ],
            [
                'id' => 01,
                'name' => 'Primary Care Physician',
            ],
        ]);
    }
}
