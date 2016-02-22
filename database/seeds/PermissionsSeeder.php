<?php

use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();

        DB::table('permissions')->insert([
	        [
	            'name' => 'create-user',
	            'display_name' => 'Create User',
	            'description' => 'Can create user',
	        ],
	        [
	            'name' => 'edit-user',
	            'display_name' => 'Edit User',
	            'description' => 'Can edit user',
	        ],
	        [
	            'name' => 'delete-user',
	            'display_name' => 'Delete User',
	            'description' => 'Can delete user',
	        ],
	        [
	            'name' => 'care-cordination',
	            'display_name' => 'Care Cordination',
	            'description' => 'Can see care cordination',
	        ],
	        [
	            'name' => 'assign-roles',
	            'display_name' => 'Assign User Roles',
	            'description' => 'Can assign user roles',
	        ],
	        [
	            'name' => 'add-practices',
	            'display_name' => 'Add Practices ',
	            'description' => 'Can add practices',
	        ],
	        [
	            'name' => 'edit-practices',
	            'display_name' => 'Edit Practices ',
	            'description' => 'Can edit practices',
	        ], 
	        [
	            'name' => 'delete-practices',
	            'display_name' => 'Delete Practices ',
	            'description' => 'Can delete practices',
	        ],
	        [
	            'name' => 'add-documents',
	            'display_name' => 'Add Documents ',
	            'description' => 'Can add documents',
	        ],
	        [
	            'name' => 'edit-documents',
	            'display_name' => 'Edit Documents ',
	            'description' => 'Can edit documents',
	        ], 
	        [
	            'name' => 'delete-documents',
	            'display_name' => 'Delete Documents ',
	            'description' => 'Can delete documents',
	        ], 
	        [
	            'name' => 'add-folders',
	            'display_name' => 'Add Folders',
	            'description' => 'Can add folders',
	        ],
	        [
	            'name' => 'edit-folders',
	            'display_name' => 'Edit Folders ',
	            'description' => 'Can edit folders',
	        ], 
	        [
	            'name' => 'delete-folders',
	            'display_name' => 'Delete Folders ',
	            'description' => 'Can delete folders',
	        ], 
	        [
	            'name' => 'add-announcement',
	            'display_name' => 'Add Announcement',
	            'description' => 'Can add announcement',
	        ],
	        [
	            'name' => 'edit-announcement',
	            'display_name' => 'Edit Announcement ',
	            'description' => 'Can edit announcement',
	        ], 
	        [
	            'name' => 'delete-announcement',
	            'display_name' => 'Delete Announcement ',
	            'description' => 'Can delete announcement',
	        ], 
	        [
	            'name' => 'bulk-import',
	            'display_name' => 'Bulk Import',
	            'description' => 'Can bulk import patients/practices',
	        ], 
        ]);
    }
}
