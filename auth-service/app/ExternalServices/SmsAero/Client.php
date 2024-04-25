<?php

namespace App\ExternalServices\SmsAero;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class Client
{
    private string $token;
    private string $userName;

    /**
     * @param  string  $token
     * @param  string  $userName Логин в системе
     *
     */
    public function __construct(string $token, string $userName)
    {
        $this->token = $token;
        $this->userName = $userName;
    }

    /**
     * @param  string  $number Куда отправить
     * @param  string  $text Текст
     * @return bool
     * @throws RequestException
     */
    public function send(string $number, string $text): bool
    {
        $req = Http::withBasicAuth(
            $this->userName,
            $this->token
        )->get(
            'https://gate.smsaero.ru/v2/sms/send',
            [
                'number' => $number,
                'sign' => 'SMS Aero',
                'text' => $text
            ]
        )->throw();

        return $req->status() === Response::HTTP_OK;
    }

}
