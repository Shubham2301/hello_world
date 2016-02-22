<?php

namespace myocuhub\Http\Controllers\Admin;

use Event;
use Illuminate\Http\Request;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Permission;
use myocuhub\Role;

class RoleController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$roles = Role::all();
		return view('admin.roles.index')->with('roles', $roles);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$permissions = Permission::all();
		return view('admin.roles.create')->with('permissions', $permissions);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$role = new Role;

		$role->name = $request->input('name');
		$role->display_name = $request->input('display_name');
		$role->description = $request->input('description');

		$role->save();

		$permissions = $request->input('permissions');
		foreach ($permissions as $permission_id) {
			$permission = Permission::findOrFail($permission_id);
			$role->assign($permission);
		}

		if ($role) {
			$request->session()->flash('success', 'Role created Successfully!');
			$action = 'New role created';
			$description = '';
			$filename = basename(__FILE__);
			$ip = $request->getClientIp();
			Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
			return redirect('roles');
		} else {
			return redirect()->back();
		}

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$role = Role::find($id);

		$permissions = $role->permissions();
		return view('admin.roles.edit')->with(['role' => $role, 'permissions' => $permissions]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
	}
}
