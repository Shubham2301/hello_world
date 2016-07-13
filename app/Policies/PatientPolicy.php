<?php

namespace myocuhub\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use myocuhub\Models\MessageTemplate;
use myocuhub\Patient;

class PatientPolicy
{
    use HandlesAuthorization;

    public function engage(Patient $patient, $type, $stage){
        $count = MessageTemplate::where('network_id', $patient->network()->id)
            ->where('type', $type)
            ->where('stage', $stage)
            ->count();
        return ($count == 0) ? false : true;
    }

    public function administration(){
    	$user = Auth::user();
    	try {
    		return $user->isSuperAdmin() ?: ($user->hasRole('patient-admin') ?: false);
    	} catch (Exception $e) {
    		Log::error($e);
    	}	
    }

}
