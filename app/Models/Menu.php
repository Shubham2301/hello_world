<?php

namespace myocuhub\Models;

use myocuhub\Permission;
use myocuhub\Role;
use myocuhub\User;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /*public function roles(){
		return $this->belongsToMany(Role::class);
	}*/

	/*public function permission(){
		return $this->belongsTo(Permission::class);
	}*/

	/*public function hasRole($role) {
		if (is_string($role)) {
			return $this->roles->contains('name', $role);
		}

		return !!$role->intersect($this->roles)->count();
	}*/

	/*public function hasPermission($permission) {
		if (is_string($permission)) {
			return $this->permissions->contains('name', $permission);
		}

		return !!$permission->intersect($this->permissions)->count();
	}*/

	public static function renderForUser(\myocuhub\User $user, $parentId=0, $level=0){
	// public static function renderForUser($userId, $menuId=0, $level=0){
		if(!$parentId){
			$menus = Menu::all();
		} else {
			$menus = Menu::where('parent_id', '=', $parentId);
		}
		// $user = User::find($userId);

		// $returnMenus[] = array();

		/*
		$func = function($menu) use ($user) {
			return $user->hasRole($menu->permission->roles);
		};

		foreach ($menus as $menu) {
			// If there is no roles associated or the roles on menu and user matches
			if(!$menu->permission || $func($menu)){
				$returnMenus[] = $menu;
			}
		}*/
		foreach ($menus as $menu) {
			// If there is no roles associated or the roles on menu and user matches
			if(!$menu->permission_id){
				$returnMenus[] = $menu;
			} else {
				$permission = Permission::find($menu->permission_id);
				$roleHavingPermission = $permission->roles;
				if($user->hasRole($roleHavingPermission)) {
						$returnMenus[] = $menu;
						// return;
				}
				/*foreach ($roleHavingPermission as $role) {
					if($user->hasRole($role->name)) {
						$returnMenus[] = $menu;
						return;
					}
				}*/
			}
		}

		return $returnMenus;
	}
}
