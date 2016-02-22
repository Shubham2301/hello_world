<?php

namespace myocuhub\Http\Controllers;

use Auth;
use Event;
use Gate;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Permission;
use myocuhub\Role;
use myocuhub\Models\Menu;
use myocuhub\User;

class TestroleController extends Controller {
	public function show() {
		Auth::loginUsingId(5);

		if (Gate::denies('edit-topic')) {
			//abort('403', 'Sorry! But you need permission');
			return 'Sorry! But you need permission';
		}

		return view('welcome');
	}

	public function start() {

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

	public function menuTest(){
		Auth::loginUsingId(5);
		$user = \myocuhub\User::find(3);

		$menus = Menu::renderForUser($user);
		foreach ($menus as $menu) {
			echo "<br> Menu name: " . $menu->name;
		}
	}
}
