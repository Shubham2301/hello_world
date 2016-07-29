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
    		return $user->isSuperAdmin();
    	} catch (Exception $e) {
    		Log::error($e);
    	}
    }

}
