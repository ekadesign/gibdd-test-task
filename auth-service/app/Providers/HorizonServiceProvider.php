<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        if (request()->host() == 'localhost') {
            config()->set('octane.https', false);
        }
    }

    protected function authorization()
    {
        Horizon::auth(function ($request) {
            return true;
        });
    }
}
