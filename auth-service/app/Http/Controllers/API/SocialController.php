<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;

class SocialController extends Controller
{
    public function redirect(AuthService $authService, string $provider)
    {
        return response()->json([
            'data' => [
                'redirect_url' => $authService->redirect($provider),
            ],
        ]);
    }

    public function callback(AuthService $authService, string $provider)
    {
        $user = $authService->user(
            $provider,
            auth()->user() ?? null
        );

        $token = $authService->getTokenUser($user);

        return response()->json([
            'access_token' => $token->plainTextToken,
            'token_type' => 'bearer',
            'expires_in' => $token->accessToken->expires_at
        ]);
    }
}
