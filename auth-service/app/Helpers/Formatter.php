<?php

namespace App\Helpers;

use Propaganistas\LaravelPhone\PhoneNumber;

class Formatter
{
    public static function phone(string|null $phone): string|null
    {
        if (is_null($phone)) {
            return null;
        }

        try {
            $phone = (new PhoneNumber($phone, config('phone-countries')))->formatE164();
        } catch (\Exception) {}

        return $phone;
    }
}
