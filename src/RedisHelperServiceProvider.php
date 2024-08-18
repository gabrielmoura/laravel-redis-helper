<?php

namespace Gabrielmoura\RedisHelper;

use Illuminate\Support\ServiceProvider;

class RedisHelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('redis-helper', function () {
            return new RedisHelperClass(app('redis')->connection());
        });
    }

    public function provides(): array
    {
        return ['redis-helper'];
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
