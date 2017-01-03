<?php

namespace myocuhub\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use myocuhub\Models\OnboardPractice;

class OnboardingMiddleware
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
        $id = $request->input('id');
        $token = $request->input('token');
        $onboardPractice = OnboardPractice::find($id);
        if (!Auth::check() && $onboardPractice && $onboardPractice->token == $token) {
            return $next($request);
        }
        if (!$request->ajax()) {
            return redirect('/')->withErrors([
                'Error processing your request',
            ]);
        } else {
            Session::flash('success_msg', 'Success!');
            return json_encode(false);
        }
    }
}
