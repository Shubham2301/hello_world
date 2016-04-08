<?php

namespace myocuhub\Http\Middleware;

use Closure;
use  Auth;
use  myocuhub\Usertype;

class RoleMiddleware {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $role, $userLevel = 4, $userType = '') {

		$userTypeData = Usertype::find(Auth::user()->usertype_id);

		$userTypeName = 'User';

		if($userTypeData)
			$userTypeName = $userTypeData->name;

		//dd($userType.' ', $userTypeName);
		$canAccess = str_contains($userType, $userTypeName);

		$isStaff = str_contains($userType, 'Staff');
		$isAdmin = str_contains($userType, 'Administrator');


		if(!$canAccess && $userType != '' )
			return redirect('/home');

		if(session('user-level') <= $userLevel )
			return $next($request);

		if($isStaff && $request->user()->hasRole($role) )
			return $next($request);

		if($request->user()->hasRole($role))
			return $next($request);

		if($isAdmin && $request->user()->hasRole($role))
			return $next($request);

		if(session('user-level') == 1 && $role == 'network-admin')
			return $next($request);


			$request->session()->flash('failure', 'Unauthorized Access!');
			return redirect('/home');


	}
}
