<?php

namespace myocuhub\Http\Middleware;

use Closure;

class RoleMiddleware {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $role, $userLevel = 4) {

		if(session('user-level') == 1)
			return $next($request);

		if (!$request->user()->hasRole($role) && session('user-level') > $userLevel ) {
			$request->session()->flash('failure', 'Unauthorized Access!');
			return redirect('/home');
		}
		return $next($request);
	}
}
