<?php

namespace App\Services\Auth\Providers;

use Laravel\Socialite\AbstractUser;

class DiscordProvider extends AbstractProvider
{

    protected array $scopes = ['identify', 'email'];

    public function getRedirectUrl(): string
    {
        return $this->getProvider()->redirect()->getTargetUrl();
    }

    public function user(): AbstractUser
    {
        $socialiteUser = $this->getProvider()->user();
        if (isset($socialiteUser->user['verified']) && $socialiteUser->user['verified'] === false) {
            $socialiteUser->email = $socialiteUser->getId().'@discord.com';
        }
        return $socialiteUser;
    }

    protected function getProviderName(): string
    {
        return 'discord';
    }
}
