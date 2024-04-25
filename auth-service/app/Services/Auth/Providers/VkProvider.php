<?php

namespace App\Services\Auth\Providers;

use Laravel\Socialite\AbstractUser;

class VkProvider extends AbstractProvider
{

    protected array $scopes = ['email'];

    public function getRedirectUrl(): string
    {
        return $this->getProvider()->redirect()->getTargetUrl();
    }

    public function user(): AbstractUser
    {
        $socialiteUser = $this->getProvider()->user();
        if (!$socialiteUser->getEmail()) {
            $socialiteUser->email = $socialiteUser->getId().'@vk.com';
        }
        return $socialiteUser;
    }

    protected function getProviderName(): string
    {
        return 'vkontakte';
    }
}
