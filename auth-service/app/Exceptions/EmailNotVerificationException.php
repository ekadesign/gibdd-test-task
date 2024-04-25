<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class EmailNotVerificationException extends Exception
{
    protected $message = 'email verification failed';
    protected int $httpStatus = Response::HTTP_FORBIDDEN;
}
