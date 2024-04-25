<?php

namespace App\Http\Middleware\API;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Internal
{
    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @return Response
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->bearerToken() != config('auth.service_token')) {
            throw new AuthenticationException('Unauthenticated.');
        }
        return $next($request);
    }
}
