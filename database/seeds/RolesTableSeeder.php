<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert(
        [
            'name'=>'patient-record',
            'display_name'=> 'Patient Record',
            'description' => 'A member of this role can access health records',
        ]);
    }
}
