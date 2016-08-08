<?php

use Illuminate\Database\Seeder;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('menus')->delete();

        DB::table('menus')->insert([
            [
                'name' => 'direct-mail',
                'display_name' => 'Direct Mail',
                'description' => 'Direct Mail',
                'url' => '/directmail',
                'icon_path' => '/images/sidebar/messages',
                'level' => '0',
            ],
            [
                'name' => 'file-exchange',
                'display_name' => 'File Exchange',
                'description' => 'File Exchange',
                'url' => '/file_exchange',
                'icon_path' => '/images/sidebar/file_update',
                'level' => '0',
            ],
            [
                'name' => 'announcements',
                'display_name' => 'Announcements',
                'description' => 'Announcements',
                'url' => '/home#',
                'icon_path' => '/images/sidebar/announcements',
                'level' => '0',
            ],
            [
                'name' => 'schedule-patient',
                'display_name' => 'Schedule Patient',
                'description' => 'Schedule Patient',
                'url' => '/home',
                'icon_path' => '/images/sidebar/schedule',
                'level' => '0',
            ],
            [
                'name' => 'patient-records',
                'display_name' => 'Patient Records',
                'description' => 'Patient Records',
                'url' => '/home#',
                'icon_path' => '/images/sidebar/records',
                'level' => '0',
            ],
            [
                'name' => 'care-console',
                'display_name' => 'Care Console',
                'description' => 'Care Console',
                'url' => '/careconsole',
                'icon_path' => '/images/sidebar/care-coordination',
                'level' => '0',
            ],
            [
                'name' => 'administration',
                'display_name' => 'Administration',
                'description' => 'Administration',
                'url' => '/administration/practices',
                'icon_path' => '/images/sidebar/administration',
                'level' => '0',
            ],
        ]);
    }
}
