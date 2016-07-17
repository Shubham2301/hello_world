<?php

namespace myocuhub\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use myocuhub\Network;
use myocuhub\User;

class CareconsolePolicy
{
    use HandlesAuthorization;

    public function accessConsole(){
    	$user = Auth::user();
    	$careconsoleStages = Network::find(User::getNetwork($user->id)->network_id)->careconsoleStages;

    	return ($user->checkUserLevel('Network') && sizeof($careconsoleStages) > 0);
    }
}
