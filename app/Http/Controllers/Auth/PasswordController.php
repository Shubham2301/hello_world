<?php

namespace myocuhub\Http\Controllers\Auth;

use Event;
use Auth;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;


class PasswordController extends Controller {
	/*
	|--------------------------------------------------------------------------
	| Password Reset Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for handling password reset requests
	| and uses a simple trait to include this behavior. You're free to
	| explore this trait and override any methods you wish to tweak.
	|
	 */

	use ResetsPasswords;

	/**
	 * Create a new password controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('guest');
	}

	protected function getResetFailureResponse(Request $request, $response)
    {

    	$action = 'Password Reset Invalid for '. $request->email;
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }

    protected function getResetSuccessResponse($response)
    {
    	$action = 'Password Reset Successfull';
        $description = '';
        $filename = basename(__FILE__);
        $ip = '';
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
        if(Auth::user()){
        	$action = 'User Logged In after Password Reset';
        	Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
        }
        

        return redirect($this->redirectPath())->with('status', trans($response));
    }

	protected function getSendResetLinkEmailSuccessResponse($response)
	{
		return redirect()->back()->with('success', trans($response));
	}

}
