<?php

namespace App\Exceptions\Verify;

use App\Exceptions\ReportException;
use Symfony\Component\HttpFoundation\Response;

class PhoneNotValidCodeException extends ReportException
{
    protected $message = 'code is invalid';
    protected int $httpStatus = Response::HTTP_FORBIDDEN;
}
