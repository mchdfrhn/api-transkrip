<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UserFileServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserFileServiceInterface::class,
            UserFileService::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
