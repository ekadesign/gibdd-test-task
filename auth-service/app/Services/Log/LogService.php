<?php

namespace App\Services\Log;

use Throwable;

class LogService
{
    public static function spentSeconds(float $startedAt): float
    {
        $seconds = microtime(true) - $startedAt;

        if ($seconds > 100) {
            $precision = 0;
        } elseif ($seconds > 10) {
            $precision = 1;
        } elseif ($seconds > 1) {
            $precision = 2;
        } else {
            $precision = 3;
        }

        return round($seconds, $precision);
    }

    public static function defaultContext(bool $withTrace = false): array
    {
        $request = request();
        $command = $request->server('argv');
        if (is_array($command)) {
            $command = implode(' ', $command);
        }

        $result = [
            'command' => $command,
            'incomingRequestUrl' => $request->fullUrl(),
            'incomingRequestData' => $request->all(),
            'incomingRequestIp' => $request->ip(),
            //'incomingRequestHeaders' => (string) $request->headers,
            'incomingRequestUserAgent' => $request->userAgent(),
            'incomingRequestReferer' => url()->previous(),
            'incomingRequestUserId' => $request->user()?->id,
        ];

        if ($withTrace) {
            $result['trace'] = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        }

        return $result;
    }

    public static function exceptionContext(Throwable $e, bool $withDefaultContext = false): array
    {
        $context = ['exception' => $e];

        if (method_exists($e, 'context')) {
            $context = array_merge($context, $e->context());
        }

        if ($withDefaultContext) {
            $context += self::defaultContext();
        }

        return $context;
    }
}
