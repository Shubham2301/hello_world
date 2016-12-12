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
        $network = $patient->network();
        if($network == null){
            return false;
        }
        $count = MessageTemplate::where('network_id', $network->id)
            ->where('type', $type)
            ->where('stage', $stage)
            ->count();
        return ($count == 0) ? false : true;
    }

    public function validPhone(Patient $patient){
    	$phone = $patient->getPhone();
    	return ($phone != null && $phone != '-');
    }

    public function administration(){
    	$user = Auth::user();
    	try {
    		return ($user->isSuperAdmin() || $user->hasRole('patient-admin'));
    	} catch (Exception $e) {
    		Log::error($e);
    	}
        return false;
    }

    public function canUpdate($patientID) {
        $user = Auth::user();
        $patient = Patient::find($patientID);

        try {
            if (session('user-level') == 1) {
                return true;
            }
            else if ((session('user-level') == 2) && $user->hasRole('patient-admin')) {
                return ($patient->careconsole->importHistory) ? session('network-id') == $patient->careconsole->importHistory->network_id : false;
            }
            else if (session('user-level') == 3 && $user->hasRole('patient-admin')) {
                return ($user->userPractice && $patient->practicePatient) ? $user->userPractice->practice_id == $patient->practicePatient->practice_id : false;
            }
        } catch (Exception $e) {
    		Log::error($e);
    	}

        return false;

    }

}
