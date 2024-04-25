<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ReportException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Verification\ConfirmPhoneCodeRequest;
use App\Http\Requests\API\Verification\EmailRequest;
use App\Http\Requests\API\Verification\PhoneRequest;
use App\Jobs\SendEmailVerificationJob;
use App\Jobs\SendPhoneVerificationJob;
use App\Services\Verify\ConfirmPhoneService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;

class VerificationController extends Controller
{
    /**
     * Отправка письма для верификации емайла
     *
     * @param  EmailRequest  $request
     * @return JsonResponse
     */
    public function email(EmailRequest $request): JsonResponse
    {
        dispatch_sync(new SendEmailVerificationJob($request->user()));

        return response()->json([
            'success' => true,
        ]);
    }
}
