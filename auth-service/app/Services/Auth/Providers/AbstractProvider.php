<?php

namespace App\Services\Auth\Providers;

use Laravel\Socialite\AbstractUser;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\One\AbstractProvider as AbstractProviderOne;
use Laravel\Socialite\Two\AbstractProvider as AbstractProviderTwo;

abstract class AbstractProvider
{
    protected array $scopes = [];

    abstract public function getRedirectUrl(): string;

    abstract public function user(): AbstractUser;

    public function userByInitData(string $initData): ?AbstractUser
    {
        return null;
    }

    abstract protected function getProviderName(): string;

    protected function getProvider(): Provider|AbstractProviderOne|AbstractProviderTwo
    {
        return Socialite::driver($this->getProviderName())
            ->setScopes($this->scopes)
            ->stateless();
    }
}
