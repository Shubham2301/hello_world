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

		if($request->user()->hasRole($role) )
			return $next($request);

		$userTypeData = Usertype::find(Auth::user()->usertype_id);

		$userTypeName = 'User';

		if($userTypeData)
			$userTypeName = $userTypeData->name;

		//dd($userType.' ', $userTypeName);
		$canAccess = str_contains($userType, $userTypeName);

		$isStaff = str_contains($userTypeName, 'Staff');
		$isAdmin = str_contains($userTypeName, 'Administrator');


		if(!$canAccess && $userType != '' ){

			if(!$this->redirectToLandingPage($isStaff, $isAdmin))
				return redirect('/file_exchange');

			return redirect('/home');
		}

		if(session('user-level') <= $userLevel )
			return $next($request);

		if($isAdmin && $role != 'care-console')
			return $next($request);

		if(session('user-level') == 1 && $role == 'network-admin')
			return $next($request);

		$request->session()->flash('failure', 'Unauthorized Access!');
		if(!$this->redirectToLandingPage($isStaff, $isAdmin))
			return redirect('/file_exchange');

		return redirect('/home');


	}


	public function redirectToLandingPage($isStaff, $isAdmin ){
		if(!$isStaff && $isAdmin )
		{
			if(Auth::user()->menu_id == 7)
				return false;
		}
		if(Auth::user()->menu_id == 6 && !Auth::user()->hasRole('care-console') )
			return false;

		return true;

	}
}
