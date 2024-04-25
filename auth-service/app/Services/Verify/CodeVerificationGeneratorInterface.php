<?php

namespace App\Services\Verify;

interface CodeVerificationGeneratorInterface
{
    public function generate(): string;
}
