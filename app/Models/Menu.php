<?php

namespace myocuhub\Models;

use myocuhub\Permission;
use myocuhub\Role;
use myocuhub\User;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public function roles(){
		return $this->belongsToMany(Role::class);
	}

	public function permission(){
		return $this->belongsTo(Permission::class);
	}

	public function hasRole($role) {
		if (is_string($role)) {
			return $this->roles->contains('name', $role);
		}

		return !!$role->intersect($this->roles)->count();
	}

	public function hasPermission($permission) {
		if (is_string($permission)) {
			return $this->permissions->contains('name', $permission);
		}

		return !!$permission->intersect($this->permissions)->count();
	}

	// public static function renderForUser(\myocuhub\User $user, $menuId=0, $level=0){
	public function renderForUser($userId, $menuId=0, $level=0){
		$menus = Menu::all();
		$user = User::find($userId);
		// return "hello";

		// $returnMenus[] = array();

		foreach ($menus as $menu) {		
			$func = function($menu) use ($user) {
						return $user->hasRole($menu->permission->roles);
				};

			// If there is no roles associated or the roles on menu and user matches
			if(!$menu->permission || $func($menu)){
				$returnMenus[] = $menu;
			}
		}
		return $returnMenus;
	}
}
