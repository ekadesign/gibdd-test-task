<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class EmailExistsException extends ReportException
{
    protected $message = 'email exists';
    protected int $httpStatus = Response::HTTP_BAD_REQUEST;
}
