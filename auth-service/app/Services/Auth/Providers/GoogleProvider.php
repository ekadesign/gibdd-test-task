<?php

namespace App\Services\Auth\Providers;

use Laravel\Socialite\AbstractUser;

class GoogleProvider extends AbstractProvider
{

    protected array $scopes = [
        'openid',
        'profile',
        'email',
    ];

    public function getRedirectUrl(): string
    {
        return $this->getProvider()->redirect()->getTargetUrl();
    }

    public function user(): AbstractUser
    {
        $socialiteUser = $this->getProvider()->user();
        if (!$socialiteUser->getEmail()) {
            $socialiteUser->email = $socialiteUser->getId().'@google.com';
        }
        return $socialiteUser;
    }

    protected function getProviderName(): string
    {
        return 'google';
    }
}
