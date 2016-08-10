<?php

use Illuminate\Database\Seeder;

class UserLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('userlevels')->delete();

        DB::table('userlevels')->insert([
            [
                'name' => 'Ocuhub',
            ],
            [
                'name' => 'Network',
            ],
            [
                'name' => 'Provider',
            ],
            [
                'name' => 'Location',
            ],
        ]);
    }
}
