<?php

namespace App\Services\Verify;

use App\Events\UserVerifiedPhoneEvent;
use App\Exceptions\Verify\PhoneNotValidCodeException;
use App\Exceptions\Verify\PhoneVerificationCodeAlreadyVerifiedException;
use App\Exceptions\Verify\PhoneVerificationCodeExpiredException;
use App\Exceptions\Verify\PhoneVerificationNotFoundException;
use App\Models\UserPhoneVerification;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;

/**
 * Сервис подвреждения прохождения верификии телефона
 */
class ConfirmPhoneService
{
    private Dispatcher $dispatcher;

    /**
     * @param  Dispatcher  $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param  string  $phone
     * @param  string  $verificationCode
     * @return bool
     * @throws PhoneNotValidCodeException
     * @throws PhoneVerificationCodeAlreadyVerifiedException
     * @throws PhoneVerificationCodeExpiredException
     * @throws PhoneVerificationNotFoundException
     * @throws \Exception
     */
    public function handle(string $phone, string $verificationCode): bool
    {
        /** @var UserPhoneVerification $verification */
        $verification = UserPhoneVerification::query()->firstWhere([
            'phone' => $phone,
        ]);

        if (is_null($verification)) {
            throw new PhoneVerificationNotFoundException();
        }

        if ($verification->isVerified()) {
            throw new PhoneVerificationCodeAlreadyVerifiedException();
        }

        if ($verification->isExpired()) {
            throw new PhoneVerificationCodeExpiredException();
        }

        if ($verification->code !== $verificationCode) {
            throw new PhoneNotValidCodeException();
        }

        $this->dispatcher->dispatch(new UserVerifiedPhoneEvent($phone));

        $verifiedAt = now();
        DB::beginTransaction();
        try {
            $verification->verified_at = $verifiedAt;
            $verification->save();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();

        return true;
    }
}
