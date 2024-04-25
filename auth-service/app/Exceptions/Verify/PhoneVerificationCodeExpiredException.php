<?php

namespace App\Exceptions\Verify;

use App\Exceptions\ReportException;
use Symfony\Component\HttpFoundation\Response;

class PhoneVerificationCodeExpiredException extends ReportException
{
    protected $message = 'verification code is expired';
    protected int $httpStatus = Response::HTTP_FORBIDDEN;
}
