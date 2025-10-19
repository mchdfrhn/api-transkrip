<?php

namespace App\Services;

use Illuminate\Support\ServiceProvider;

class ResponseFileServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            ResponseFileServiceInterface::class,
            ResponseFileService::class
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
