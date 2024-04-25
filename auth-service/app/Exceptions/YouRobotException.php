<?php

namespace App\Exceptions;

use Exception;

class YouRobotException extends Exception
{
    public function getResponseBody(): array
    {
        return [
            'message' => 'Are you robot?'
        ];
    }

    public function getResponseCode(): int
    {
        return 401;
    }

    public function report(): bool
    {
        return false;
    }
}
