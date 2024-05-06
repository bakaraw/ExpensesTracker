<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\SafeSubmit\SafeSubmit;

class SafeSubmitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(SafeSubmit::class, function () {
            return new SafeSubmit();
        });
    }
}
