<?php

namespace myocuhub\Http\Middleware;

use Auth;
use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role, $userLevel = 1, $userType = '')
    {

        if (session('user-level') == 1) {
            return $next($request);
        }

        if ($role == 'admin_report') {
            if (session('user-level') == 1) {
                return $next($request);
            }
            else {
                return redirect('/home');
            }
        }

        if (!$request->user()->hasRole($role) && session('user-level') >= $userLevel) {
            $request->session()->flash('failure', 'Unauthorized Access!');
            if (!$this->redirectToLandingPage()) {
                return redirect('/referraltype');
            }

            return redirect('/home');

        }
        return $next($request);
    }

    public function redirectToLandingPage()
    {

        if (Auth::user()->menu_id == 6 && !Auth::user()->hasRole('care-console')) {
            return false;
        }

        return true;

    }
}
