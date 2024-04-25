<?php

namespace App\Http\Controllers\WEB;

use App\Exceptions\EmailNotVerificationException;
use App\Services\Verify\ConfirmEmailService;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class EmailVerificationController
{
    /**
     * @param  string  $hash
     * @param  ConfirmEmailService  $confirmEmailService
     * @return RedirectResponse
     */
    public function confirm(string $hash, ConfirmEmailService $confirmEmailService): RedirectResponse
    {
        try {
            $confirmEmailService->handle($hash);
        } catch (EmailNotVerificationException $e) {
            abort(Response::HTTP_FORBIDDEN, 'hash is not actual');
        }

        return redirect()->away(config('app.front_url'));
    }
}
