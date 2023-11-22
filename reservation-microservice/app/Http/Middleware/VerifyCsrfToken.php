<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'auth/token',
        'oauth/clients',
        'oauth/authorize',
        'http://localhost:8000/register' //This is the url that I dont want Csrf for postman.
    ];
}
