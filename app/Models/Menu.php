<?php

namespace myocuhub\Models;

use myocuhub\Permission;
use myocuhub\Role;
use myocuhub\User;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

	public static function renderForUser(\myocuhub\User $user, $parentId=0, $level=0){

		if(!$parentId){
			$menus = Menu::all();
		} else {
			$menus = Menu::where('parent_id', '=', $parentId);
		}

		foreach ($menus as $menu) {
			if(!$menu->permission_id){
				$returnMenus[] = $menu;
			} else {
				$permission = Permission::find($menu->permission_id);
				$roleHavingPermission = $permission->roles;
				if($user->hasRole($roleHavingPermission)) {
						$returnMenus[] = $menu;
				}
			}
		}

		return $returnMenus;
	}
}
