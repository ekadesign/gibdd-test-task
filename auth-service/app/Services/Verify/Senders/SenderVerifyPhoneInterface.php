<?php

namespace App\Services\Verify\Senders;

interface SenderVerifyPhoneInterface
{
    /**
     * @param  string  $phone Телефон куда отправить код
     * @param  string  $phoneVerifiedCode Код подверждения номера
     * @return bool
     */
    public function handle(string $phone, string $phoneVerifiedCode): bool;
}
