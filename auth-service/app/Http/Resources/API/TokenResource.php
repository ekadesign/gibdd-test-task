<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $token
 */
class TokenResource extends JsonResource
{

    public static $wrap = null;

    public function toArray($request): array
    {
        return [
            'token' => $this->resource['token'],
            'token_type' => 'bearer',
            'expires_in' => $this->resource['expired_at'],
        ];
    }
}
