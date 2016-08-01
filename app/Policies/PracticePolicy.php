<?php

namespace myocuhub\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class PracticePolicy
{
    use HandlesAuthorization;

    public function administration(){
    	$user = Auth::user();
    	try {
    		return ($user->isSuperAdmin() || $user->hasRole('practice-admin'));
    	} catch (Exception $e) {
    		Log::error($e);
    	}
    	return false;
    }

}
