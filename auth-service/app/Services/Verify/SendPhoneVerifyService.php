<?php

namespace App\Services\Verify;

use App\Models\UserPhoneVerification;
use App\Services\Verify\Senders\SenderVerifyPhoneInterface;

/**
 * Отправка кода верификации по телефону
 */
class SendPhoneVerifyService
{
    public function __construct(
        private SenderVerifyPhoneInterface $senderVerifyPhone,
        private CodeVerificationGeneratorInterface $codeVerificationGenerator,
    ){}

    public function handle(string $phone, $ttl = '1h'): bool
    {
        /** @var UserPhoneVerification $verification */
        $verification = UserPhoneVerification::query()->firstOrNew([
            'phone' => $phone,
        ]);

        // при повторном вызове код верификации обновится, а верификация сбросится
        $verification->fill([
            'code' => $this->codeVerificationGenerator->generate(),
            'verified_at' => null,
            'expired_at' => now()->add($ttl),
        ])->save();

        // Отправка кода верифа пользователю
        return $this->senderVerifyPhone->handle(
            $verification->phone,
            $verification->code
        );
    }
}
