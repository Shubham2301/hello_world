<?php

namespace myocuhub\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use myocuhub\User;
use Illuminate\Http\Request;

class ReportPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function accessReachReport(){
    	return $this->networkReportAccessPolicy();
    }

    public function accessCallCenterReport(){
    	return $this->networkReportAccessPolicy();
    }

    public function accessCareconsoleReport(){
    	return $this->networkReportAccessPolicy();
    }

    public function accessBillReport(){
        $user = Auth::user();
    	return ($user->checkUserLevel('Ocuhub'));
    }

    public function networkReportAccessPolicy() {
        $user = Auth::user();
    	return ($user->checkUserLevel('Network') && $user->hasRole('care-console') && $user->hasRole('reports'));
    }
}
