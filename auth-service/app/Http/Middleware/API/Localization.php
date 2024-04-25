<?php

namespace App\Http\Middleware\API;

use App;
use Closure;
use Illuminate\Http\Request;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->hasHeader('X-Locale')) {
            App::setlocale($request->header('X-Locale'));
        } else {
            App::setLocale('ru');
        }
        return $next($request);
    }
}
