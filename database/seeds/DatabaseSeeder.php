<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call(UserTableSeeder::class);
        $this->call('MenuTableSeeder');
        $this->call('UserLevelSeeder');
        $this->call('UserTypeSeeder');
        $this->call('RolesSeeder');
        $this->call('PermissionsSeeder');
        $this->call('RolesTableSeeder');


        Model::reguard();
    }
}
