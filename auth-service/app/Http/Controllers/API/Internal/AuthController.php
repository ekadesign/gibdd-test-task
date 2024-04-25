<?php

namespace App\Http\Controllers\API\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Internal\Auth\ImpersonateRequest;
use App\Models\User;
use App\Services\Auth\AuthService;

class AuthController extends Controller
{
    public function impersonate(AuthService $authService, ImpersonateRequest $request)
    {
        $token = $authService->getTokenUser(User::findOrFail($request->input('user_id')));

        return response()->json([
            'data' => $token->plainTextToken,
        ]);
    }
}
