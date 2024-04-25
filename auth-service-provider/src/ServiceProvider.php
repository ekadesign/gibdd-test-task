<?php

namespace Testtask\AuthServiceProvider;

use Testtask\AuthServiceProvider\Services\AuthService;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/auth_service.php', 'auth_service');
    }

    public function boot(): void
    {
        $this->registerPublishes();

        $this->app->bind(Contracts\UserContract::class, function ($app) {
            return new Models\User;
        });

        $this->app->singleton(Contracts\AuthServiceContract::class, function ($app) {
            return new AuthService($app['config']->get('auth_service'));
        });

        $this->app->bind(UserProvider::class, function ($app) {
            return new UserProvider(
                $app->make(Contracts\UserContract::class)
            );
        });

        Auth::provider('testtask', function (Application $app) {
            return $app->make(UserProvider::class);
        });
    }

    protected function registerPublishes(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/auth_service.php' => config_path('auth_service.php'),
        ], 'config');
    }
}
