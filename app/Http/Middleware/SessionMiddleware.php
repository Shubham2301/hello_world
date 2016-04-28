<?php

namespace myocuhub\Http\Middleware;

use Closure;
use Event;
use Illuminate\Support\Facades\Auth;
use Session;
use myocuhub\Events\MakeAuditEntry;

class SessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $route = $request->route()->getPath();

        $user = Auth::user();
        
        Session::flush();

        $reponse = $next($request);

        if ($route == 'auth/login') {
            if(($user = Auth::user()) == null){
                return $response;
            }
            $action = 'User Logged In';
            $description = '';
            $filename = basename(__FILE__);
            $ip = $request->getClientIp();
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
        }
        
        if ($route == 'auth/logout' && $user != null) {
            if (($state = Auth::user()) != null) {
                return $response;
            }
            $action = 'User Logged out';
            $description = '';
            $filename = basename(__FILE__);
            $ip = $request->getClientIp();
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip, $user->id));
        }

        return $reponse;
    }
}
