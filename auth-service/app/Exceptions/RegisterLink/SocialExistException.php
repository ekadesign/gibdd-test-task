<?php

namespace App\Exceptions\RegisterLink;


use App\Exceptions\ReportException;
use Symfony\Component\HttpFoundation\Response;

class SocialExistException extends ReportException
{
    protected $message = 'social already exist';
    protected int $httpStatus = Response::HTTP_CONFLICT;
}
