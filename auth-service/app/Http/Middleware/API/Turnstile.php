<?php

namespace App\Http\Middleware\API;

use App\Exceptions\YouRobotException;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Str;

class Turnstile
{
    /**
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (config('services.turnstile.enabled')) {
            $turnstileResponse = $request->get('cf-turnstile-response');
            if (!$turnstileResponse) {
                throw new YouRobotException;
            }

            $response = Http::asJson()
                ->timeout(10)
                ->connectTimeout(5)
                ->throw()
                ->post(config('services.turnstile.url'), [
                    'secret' => config('services.turnstile.secret_key'),
                    'response' => $turnstileResponse,
                    'idempotency_key' => Str::uuid()
                ]);

            if (!$response->json('success')) {
                throw new YouRobotException;
            }
        }

        return $next($request);
    }

}
