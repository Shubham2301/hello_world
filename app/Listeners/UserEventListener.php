<?php

namespace myocuhub\Listeners;

use Illuminate\Support\Facades\Auth;
use myocuhub\User;

class UserEventListener {
	/**
	 * Handle user login events.
	 */
	public function onUserLogin($event) {

		session()->flush();

		$user = Auth::user();

		if ($network = User::getNetwork($user->id)) {
			session(['network-id' => $network->network_id]);
		}

		session(['user-level' => $user->level]);
		session(['user-type' => $user->usertype_id]);

	}

	/**
	 * Handle user logout events.
	 */
	public function onUserLogout($event) {
		session()->flush();
	}

	/**
	 * Register the listeners for the subscriber.
	 *
	 * @param  Illuminate\Events\Dispatcher  $events
	 */
	public function subscribe($events) {
		$events->listen(
			'auth.login',
			'myocuhub\Listeners\UserEventListener@onUserLogin'
		);

		$events->listen(
			'auth.logout',
			'myocuhub\Listeners\UserEventListener@onUserLogout'
		);
	}

}
