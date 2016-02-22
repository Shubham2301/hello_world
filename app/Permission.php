<?php

namespace myocuhub;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public function roles() 
    {
    	return $this->belongsToMany(Role::class);
    }

   /* public function menus()
    {
    	return $this->hasMany(\myocuhub\Model\Menu::class);
    }*/
}
