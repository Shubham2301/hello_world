<?php

namespace myocuhub\Http\Controllers;

use Gate;
use Illuminate\Http\Request;

use myocuhub\Http\Requests;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Role;
use myocuhub\Permission;
use Auth;

class TestroleController extends Controller
{
    public function show() 
    {
    	Auth::loginUsingId(5); 

    	if(Gate::denies('edit-topic')) {
    		//abort('403', 'Sorry! But you need permission');
    		return 'Sorry! But you need permission'; 
    	}

    	return view('welcome');
    }

    public function start()
    {

    	$adminRole = new Role();
    	$adminRole->name = 'admin';
    	$adminRole->display_name = 'Administrator';
    	$adminRole->description = 'Administrator of the system';
    	$adminRole->save();

    	$sadminRole = new Role();
    	$sadminRole->name = 'super-admin';
    	$sadminRole->display_name = 'Super Administrator';
    	$sadminRole->description = 'GOD account';
    	$sadminRole->save();

    	$editPermission = new Permission();
    	$editPermission->name = 'edit-topic';
    	$editPermission->display_name = 'Edit Topic';
    	$editPermission->description = 'User will be able to edit topic';
    	$editPermission->save();

    	$delPermission = new Permission();
    	$delPermission->name = 'delete-topic';
    	$delPermission->display_name = 'Delete Topic';
    	$delPermission->description = 'User will be able to delete topic';
    	$delPermission->save();


    	$adminRole->assign($editPermission);
    	$sadminRole->assign(Permission::whereName('edit-topic')->first());
    	$sadminRole->assign($delPermission);

    	$admin = factory('myocuhub\User')->create();
    	$sadmin = factory('myocuhub\User')->create();

    	$admin->assign('admin');
    	$sadmin->assign('super-admin');

        $action = 'Save roles';
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

    	return "Initialized!!!";
    }
}
