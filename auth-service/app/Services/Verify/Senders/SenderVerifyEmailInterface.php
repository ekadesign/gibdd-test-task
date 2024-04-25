<?php

namespace App\Services\Verify\Senders;

interface SenderVerifyEmailInterface
{
    /**
     * Обработчик отправки емайла верификации
     *
     * @param  string  $to Емайл кому отправляем
     * @param  string  $emailVerifiedHash Хэш для потврежения емайла - вставляем в шаблон
     * @return bool
     */
    public function handle(string $to, string $emailVerifiedHash): bool;
}
