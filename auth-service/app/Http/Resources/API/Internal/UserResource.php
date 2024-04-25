<?php

namespace App\Http\Resources\API\Internal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        if (!$this->resource) {
            return [];
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'nickname' =>$this->nickname,
            'role' => $this->role,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'status' => $this->status,
            'status_kyc' => $this->status_kyc,
            'country' => $this->country,
            'currency' => $this->currency,
            'language' => $this->language,
            'timezone' => $this->timezone,
            'avatar' => $this->avatar?->getUrl(),
        ];
    }
}
