<?php

namespace myocuhub\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {
	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		\Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
		\myocuhub\Http\Middleware\EncryptCookies::class,
		\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
		\Illuminate\Session\Middleware\StartSession::class,
		\Illuminate\View\Middleware\ShareErrorsFromSession::class,
		\myocuhub\Http\Middleware\VerifyCsrfToken::class,
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth' => \myocuhub\Http\Middleware\Authenticate::class,
		'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
		'guest' => \myocuhub\Http\Middleware\RedirectIfAuthenticated::class,
		'role' => \myocuhub\Http\Middleware\RoleMiddleware::class,
		'session.flush' => \myocuhub\Http\Middleware\SessionMiddleware::class,
		'onboarding' => \myocuhub\Http\Middleware\OnboardingMiddleware::class,
	];
}
