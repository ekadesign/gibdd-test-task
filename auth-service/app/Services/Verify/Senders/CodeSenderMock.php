<?php

namespace App\Services\Verify\Senders;
use Illuminate\Support\Facades\Log;

class CodeSenderMock implements SenderVerifyPhoneInterface
{
    /**
     * @param  string  $phone
     * @param  string  $phoneVerifiedCode
     * @return bool
     */
    public function handle(string $phone, string $phoneVerifiedCode): bool
    {
        $text = sprintf('Your code is %s', $phoneVerifiedCode);

        Log::info($text);

        return true;
    }
}
