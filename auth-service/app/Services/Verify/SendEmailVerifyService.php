<?php

namespace App\Services\Verify;

use App\Models\User;
use App\Services\Verify\Senders\SenderVerifyEmailInterface;
use Illuminate\Support\Str;

/**
 * Отправка письма для верификации емайла
 */
class SendEmailVerifyService
{
    private SenderVerifyEmailInterface $senderVerifyEmail;

    /**
     * @param  SenderVerifyEmailInterface  $senderVerifyEmail
     */
    public function __construct(SenderVerifyEmailInterface $senderVerifyEmail)
    {
        $this->senderVerifyEmail = $senderVerifyEmail;
    }

    /**
     * @param  User  $user
     * @return bool
     */
    public function handle(User $user): bool
    {
        if (! $user->email) {
            return false;
        }

        // Генерируем хэщ для потврдения почты
        $user->email_verified_hash = Str::random(50);
        $user->save();

        // Отправляем письмо
        return $this->senderVerifyEmail->handle(
            $user->email,
            $user->email_verified_hash
        );
    }
}
