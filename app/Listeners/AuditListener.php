<?php

namespace myocuhub\Listeners;

use ReflectionClass;
use Illuminate\Support\Facades\Auth;
use myocuhub\Events\PatientEngaged;
use myocuhub\Models\AuditLog;
use myocuhub\User;

class AuditListener {

	public function handle($event){
		$audit = new AuditLog;
		$audit->user_id = ($user = Auth::user()) ? $user->id : null;
		$audit->action = $event->getAction();
        $audit->description = $event->getDescription();
        $audit->filename = (new ReflectionClass($event))->getShortName();
        $audit->ip = $event->getIp();

        $audit->save();
	}

}
