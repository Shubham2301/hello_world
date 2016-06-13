<?php

namespace myocuhub\Http\Controllers\Auth;

use myocuhub\Http\Controllers\Controller;
use myocuhub\Http\Controllers\Traits\TwoFactorAuthentication;

/**
* 	Controller for Two Factor Authentication
*/
class TwoFactorAuthController extends Controller
{

	use TwoFactorAuthentication;
	
}