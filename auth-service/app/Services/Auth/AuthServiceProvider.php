<?php

namespace App\Services\Auth;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->app->bind(AuthService::class, function () {
            return new AuthService([
                'discord' => Providers\DiscordProvider::class,
                'steam' => Providers\SteamProvider::class,
                'vkontakte' => Providers\VkProvider::class,
                'google' => Providers\GoogleProvider::class,
                'telegram' => Providers\TelegramProvider::class,
            ]);
        });
    }

}
