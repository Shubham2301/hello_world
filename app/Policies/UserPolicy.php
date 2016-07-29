<?php

namespace myocuhub\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    public function administration(){
    	$user = Auth::user();
    	try {
    		return ($user->isSuperAdmin() || $user->hasRole('user-admin'));
    	} catch (Exception $e) {
    		Log::error($e);
    	}
    	return false;
    }

}
