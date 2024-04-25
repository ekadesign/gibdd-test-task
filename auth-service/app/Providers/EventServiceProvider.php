<?php

namespace App\Providers;

use App\Events\UserCreatedEvent;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        UserCreatedEvent::class => [],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            \SocialiteProviders\Discord\DiscordExtendSocialite::class,
            \SocialiteProviders\Steam\SteamExtendSocialite::class,
            \SocialiteProviders\VKontakte\VKontakteExtendSocialite::class,
            \SocialiteProviders\Google\GoogleExtendSocialite::class,
            \SocialiteProviders\Telegram\TelegramExtendSocialite::class
        ],
    ];

    protected $observers = [
        User::class => [],
    ];

    protected $subscribe = [];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
