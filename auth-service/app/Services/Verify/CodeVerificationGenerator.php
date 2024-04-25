<?php

namespace App\Services\Verify;

use Illuminate\Support\Str;

class CodeVerificationGenerator implements CodeVerificationGeneratorInterface
{
    public function __construct(
        private readonly int $length = 4,
    ){}

    public function generate(): string
    {
        if ($this->length < 1 || $this->length > 10) {
            throw new \InvalidArgumentException('incorrect verification code length');
        }

        $min = '1' . str_repeat('0', $this->length - 1);
        $max = str_repeat('9', $this->length);
        return str_pad(mt_rand((int) $min, (int) $max), $this->length, '0', STR_PAD_LEFT);
    }
}
