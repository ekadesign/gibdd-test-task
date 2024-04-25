<?php

use App\Http\Controllers\API;
use App\Http\Controllers\WEB\EmailVerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', [API\IndexController::class, 'index']);

Route::get('social/{provider}/redirect', [API\SocialController::class, 'redirect']);
Route::get('social/{provider}/callback', [API\SocialController::class, 'callback']);

Route::get('email/confirm/{hash}', [EmailVerificationController::class, 'confirm'])->name('email.confirm');

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [API\AuthController::class, 'login']);
    Route::post('refresh', [API\AuthController::class, 'refresh']);
    // 1 req per 7.5 seconds
    Route::post('get-phone-code', [API\AuthController::class, 'getPhoneCode'])->middleware('throttle:1,0.125');
    Route::post('confirm-phone-code', [API\AuthController::class, 'confirmPhoneCode']);
    Route::post('register', [API\AuthController::class, 'register']);

    Route::post('reset-send', [API\AuthController::class, 'resetSend']);
    Route::post('reset-password-update', [API\AuthController::class, 'resetPasswordUpdate']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('me', [API\AuthController::class, 'me']);
        Route::post('logout', [API\AuthController::class, 'logout']);
    });
});

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::group(['prefix' => 'session'], function () {
        Route::get('/', [API\SessionController::class, 'index']);
        Route::delete('/', [API\SessionController::class, 'destroyAll']);
        Route::delete('{token}', [API\SessionController::class, 'destroy']);
    });
});

// Internal API
Route::group(['prefix' => 'internal', 'middleware' => 'api_internal'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('impersonate', [API\Internal\AuthController::class, 'impersonate']);
    });
});
