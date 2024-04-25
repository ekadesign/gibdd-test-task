<?php

namespace App\Exceptions\Verify;

use App\Exceptions\ReportException;
use Symfony\Component\HttpFoundation\Response;

class PhoneVerificationNotFoundException extends ReportException
{
    protected $message = 'phone verification request not found';
    protected int $httpStatus = Response::HTTP_NOT_FOUND;
}
