<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ReportException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\EmailRegisterRequest;
use App\Http\Requests\API\Auth\PhoneLoginRequest;
use App\Http\Requests\API\Auth\ResetPasswordUpdateRequest;
use App\Http\Requests\API\Auth\ResetSendRequest;
use App\Http\Requests\API\Verification\ConfirmPhoneCodeRequest;
use App\Http\Requests\API\Verification\PhoneRequest;
use App\Http\Resources\API\ProfileResource;
use App\Http\Resources\API\TokenResource;
use App\Jobs\SendPhoneVerificationJob;
use App\Services\Auth\AuthService;
use App\Services\Verify\ConfirmPhoneService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     */
    public function login(LoginRequest $request, AuthService $authService)
    {
            $credentials = $request->only(['email', 'password']);
            $credentials['email'] = mb_strtolower($credentials['email']);

        if (!auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();

        $token = $authService->getTokenUser($user);

        return new TokenResource([
            'token' => $token->plainTextToken,
            'expired_at' => $token->accessToken->expires_at,
        ]);
    }

    public function refresh(Request $request, AuthService $authService)
    {

        $newToken = $authService->getTokenUser(auth()->user());

        return new TokenResource([
            'token' => $newToken->plainTextToken,
            'expired_at' => $newToken->accessToken->expires_at,
        ]);
    }

    public function register(EmailRegisterRequest $request, AuthService $authService)
    {
        $user = $authService->createUserIfNotExist($request->validated());

        $token = $authService->getTokenUser($user);

        return new TokenResource([
            'token' => $token->plainTextToken,
            'expired_at' => $token->accessToken->expires_at,
        ]);
    }

    /**
     * Get the authenticated User.
     */
    public function me(AuthService $authService): JsonResponse|ProfileResource
    {
        $user = auth()->user();

        return new ProfileResource($user);
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function resetSend(ResetSendRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'), function ($user, $token){
            Log::info($token);
        });

        return response()->json([
            'status' => __($status),
        ]);
    }

    public function resetPasswordUpdate(ResetPasswordUpdateRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->input('password')),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return response()->json([
            'status' => __($status),
        ]);
    }


    /**
     * Отправка кода верификации телефона
     *
     * @param  PhoneRequest  $request
     * @return JsonResponse
     */
    public function getPhoneCode(PhoneLoginRequest $request, AuthService $authService): JsonResponse
    {
        $authService->createUserIfNotExist($request->validated());
        dispatch(new SendPhoneVerificationJob($request->validated('phone')));

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Подтверждение верификации кода для телефона
     *
     * @param  ConfirmPhoneCodeRequest  $request
     * @param  ConfirmPhoneService  $service
     * @return JsonResponse
     */
    public function confirmPhoneCode(ConfirmPhoneCodeRequest $request, ConfirmPhoneService $service, AuthService $authService)
    {
        $key = 'phone-code-verification:' . $request->validated('phone');
        $attempts = (int) config('verification.attempts');
        $decay = (int) config('verification.decay_time');

        $response = RateLimiter::attempt($key, $attempts, function() use ($request, $service, $key, $authService) {
            try {
                $service->handle(
                    $request->validated('phone'),
                    $request->validated('code'),
                );
            } catch (ReportException $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], Response::HTTP_FORBIDDEN);
            }

            RateLimiter::clear($key);

            $user = $authService->createUserIfNotExist($request->validated());
            $token = $authService->getTokenUser($user);

            return new TokenResource([
                'token' => $token->plainTextToken,
                'expired_at' => $token->accessToken->expires_at,
            ]);
        }, $decay);

        if ($response === false) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => 'try again in ' . $seconds . ' seconds.',
            ], Response::HTTP_FORBIDDEN);
        }

        return $response;
    }



}
