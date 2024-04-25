<?php

namespace App\Http\Resources\API;

use App\Models\User;
use App\Models\UserSocial;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->loadMissing(['socials']);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'nickname' => $this->nickname,
            'role' => $this->role,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'status' => $this->status,
            'status_kyc' => $this->status_kyc,
            'country' => $this->country,
            'currency' => $this->currency,
            'language' => $this->language,
            'timezone' => $this->timezone,
            'date_of_birth' => $this->date_of_birth,
            'phone' => $this->phone,
            //'avatar' => $this->avatar?->getUrl(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'phone_verified_at' => $this->phone_verified_at,
            'pwa_installed_at' => $this->pwa_installed_at,
            'socials' => $this->socials?->map(function (UserSocial $userSocial) {
                return [
                    'provider_name' => $userSocial->provider_name,
                    'provider_id' => $userSocial->provider_id,
                ];
            })
        ];
    }
}
