<?php

namespace myocuhub\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use myocuhub\User;

class Authenticate {
	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth) {
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {

		if ($this->auth->guest()) {
			if ($request->ajax()) {
				return response('Unauthorized.', 401);
			} else {
				return redirect()->guest('/');
			}
		}

		$user = Auth::user();

		if ($user->two_factor_auth == 1 && session('two-factor-auth') != true) {
			return redirect('/auth/twofactorauth');
		}

        if($user->level < 3 && $user->userNetwork->first()) {
            session(['network-id' => $user->userNetwork->first()->network_id]);
        }

		session(['user-level' => $user->level]);
		session(['user-type' => $user->usertype_id]);

		return $next($request);
	}
}
