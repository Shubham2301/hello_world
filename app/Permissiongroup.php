<?php

namespace myocuhub;

use Illuminate\Database\Eloquent\Model;

class Permissiongroup extends Model
{
    public function permissions()
    {
    	return $this->hasMany(Permisssion::class);
    }
}
