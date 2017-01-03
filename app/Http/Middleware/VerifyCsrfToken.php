<?php

namespace myocuhub\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
       '/auth/login',
       '/password/reset/*',
       '/errors/directmail/',
       'auth/verifyotp',
       '/auth/resendotp',
        '/onboarding/*'
    ];

}
