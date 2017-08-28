<?php

namespace myocuhub\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Auth;
use myocuhub\Patient;

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

    public function canSchedule($patientID)
    {
        $user = Auth::user();
        $userNetwork = $user->userNetwork;
        $patient = Patient::find($patientID);
        if (sizeof($userNetwork) == 0 || !$patient || !$patient->careConsole) {
            return false;
        }
        foreach ($userNetwork as $network) {
            if ($network->network_id == $patient->careConsole->importHistory->network_id) {
                return true;
            }
        }
        return false;
    }

    public function updateNetwork()
    {
        $user = Auth::user();
        try {
            return ($user->isSuperAdmin());
        } catch (Exception $e) {
            Log::error($e);
        }
        return false;
    }

}
