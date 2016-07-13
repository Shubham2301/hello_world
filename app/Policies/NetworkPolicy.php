<?php

namespace myocuhub\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class NetworkPolicy
{
    use HandlesAuthorization;

    public function administration(){
    	$user = Auth::user();
    	try {
    		return $user->isSuperAdmin() ?: ($user->hasRole('network-admin') ?: false);
    	} catch (Exception $e) {
    		Log::error($e);
    	}
    }

}
