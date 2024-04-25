<?php

namespace App\Exceptions\Verify;

use App\Exceptions\ReportException;
use Symfony\Component\HttpFoundation\Response;

class PhoneVerificationCodeAlreadyVerifiedException extends ReportException
{
    protected $message = 'verification code already verified';
    protected int $httpStatus = Response::HTTP_FORBIDDEN;
}
