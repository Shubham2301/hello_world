<?php

namespace myocuhub\Http\Controllers\Auth;

use Event;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Auth;
use Validator;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function authenticated($request, $user)
    {
        if ($user->active) {
            return redirect()->intended($this->redirectPath());
        } else {
            return redirect('/auth/logout');
        }
    }

    protected function sendFailedLoginResponse(Request $request)
    {   
        $action = 'Failed Login for' . $request->input('email');

        $description = '';
        $filename = basename(__FILE__);
        $ip = '';
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

        return redirect()->back()
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
    }

    protected function handleUserWasAuthenticated(Request $request, $throttles)
    {

        $action = 'User Logged In';
        $description = '';
        $filename = basename(__FILE__);
        $ip = '';
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

        if ($throttles) {
            $this->clearLoginAttempts($request);
        }

        if (method_exists($this, 'authenticated')) {
            return $this->authenticated($request, Auth::guard($this->getGuard())->user());
        }

        return redirect()->intended($this->redirectPath());
    }

}
