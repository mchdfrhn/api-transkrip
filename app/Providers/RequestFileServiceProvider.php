<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RequestFileServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            RequestFileServiceInterface::class,
            RequestFileService::class
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
