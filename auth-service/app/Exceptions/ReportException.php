<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class ReportException extends Exception
{
    protected int $httpStatus = 500;

    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'error' => $this->getMessage(),
            'code' => $this->getCode(),
        ], $this->httpStatus);
    }
}
