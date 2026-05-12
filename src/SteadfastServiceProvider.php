<?php

declare(strict_types=1);

namespace Kejubayer\Steadfast;

use Illuminate\Support\ServiceProvider;
use Kejubayer\Steadfast\Services\SteadfastService;

class SteadfastServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/steadfast.php',
            'steadfast'
        );

        $this->app->singleton('steadfast', function () {
            return new SteadfastService();
        });

        $this->app->alias('steadfast', SteadfastService::class);
    }

    public function boot(): void
    {
        if ((bool) config('steadfast.webhook.enabled', true)) {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/steadfast.php' => config_path('steadfast.php'),
        ], 'steadfast-config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'steadfast-migrations');
    }
}
