<?php

namespace App\Services\Verify\Senders;

use App\ExternalServices\SmsAero\Client;
use Illuminate\Http\Client\RequestException;

class SmsAeroSenderVerifyPhone implements SenderVerifyPhoneInterface
{
    private Client $client;

    /**
     * @param  Client  $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param  string  $phone
     * @param  string  $phoneVerifiedCode
     * @return bool
     * @throws RequestException
     */
    public function handle(string $phone, string $phoneVerifiedCode): bool
    {
        $text = sprintf('Your code is %s', $phoneVerifiedCode);

        return $this->client->send(
            $phone,
            $text
        );
    }
}
