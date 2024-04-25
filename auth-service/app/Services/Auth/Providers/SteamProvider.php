<?php

namespace App\Services\Auth\Providers;

use Laravel\Socialite\AbstractUser;

class SteamProvider extends AbstractProvider
{

    protected array $scopes = ['openid', 'profile'];

    public function getRedirectUrl(): string
    {
        return $this->getProvider()->redirect()->getTargetUrl();
    }

    public function user(): AbstractUser
    {
        $socialiteUser = $this->getProvider()->user();
        $socialiteUser->email = $socialiteUser->getId().'@steampowered.com';
        return $socialiteUser;
    }

    protected function getProviderName(): string
    {
        return 'steam';
    }
}
