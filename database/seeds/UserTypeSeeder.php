<?php

use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('usertypes')->delete();

        DB::table('usertypes')->insert([
	        [
	            'name' => 'Administrator',
	        ],
	        [
	            'name' => 'User',
	        ],
        ]);
    }
}
