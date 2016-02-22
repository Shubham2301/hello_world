<?php

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();

        DB::table('roles')->insert([
	        [
	            'name' => 'user-admin',
	            'display_name' => 'User Admin',
	            'description' => 'A member of this role can create and manage users in the portal (providers & staff).',
	        ],
	        [
	            'name' => 'provider-staff',
	            'display_name' => 'Provider Staff',
	            'description' => 'This role represents the staff working in a practice.',
	        ],
	        [
	            'name' => 'provider',
	            'display_name' => 'Provider',
	            'description' => 'This role represents a provider in the portal.',
	        ],
	        [
	            'name' => 'practice-admin',
	            'display_name' => 'Practice Admin',
	            'description' => 'A member of this role can create and manage practices.',
	        ],
	        [
	            'name' => 'patient-admin',
	            'display_name' => 'Patient Admin',
	            'description' => 'A member of this role can create and manage patients across practices.',
	        ],	
	        [
	            'name' => 'files-admin',
	            'display_name' => 'Files Admin',
	            'description' => 'Can administrate all OcuHub files/folders.',
	        ],
	        [
	            'name' => 'bulk-import',
	            'display_name' => 'Bulk Import Admin',
	            'description' => 'Ocuhub Bulk Import Admin',
	        ],	
	        [
	            'name' => 'announcements-admin',
	            'display_name' => 'Announcements Admin',
	            'description' => 'Members of this role can create new announcements.',
	        ],	
	        [
	            'name' => 'care-console',
	            'display_name' => 'Care Console',
	            'description' => '',
	        ],	
	        [
	            'name' => 'administrator',
	            'display_name' => 'Administrators',
	            'description' => 'Administrators are super users who can do anything.',
	        ],		        
        ]);
    }
}
