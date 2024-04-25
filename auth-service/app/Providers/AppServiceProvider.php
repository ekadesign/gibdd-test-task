<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Services\Verify\CodeVerificationGenerator;
use App\Services\Verify\CodeVerificationGeneratorInterface;
use App\Services\Verify\Senders\SenderVerifyPhoneInterface;
use App\Services\Verify\Senders\CodeSenderMock;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        $this->app->singleton(SenderVerifyPhoneInterface::class, CodeSenderMock::class);
        $this->app->singleton(CodeVerificationGeneratorInterface::class, CodeVerificationGenerator::class);
    }

}
