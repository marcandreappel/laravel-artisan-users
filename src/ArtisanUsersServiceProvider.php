<?php

declare(strict_types=1);

namespace MarcAndreAppel\ArtisanUsers;

use Illuminate\Support\ServiceProvider;
use MarcAndreAppel\ArtisanUsers\Console\Commands\UserAdd;
use MarcAndreAppel\ArtisanUsers\Console\Commands\UserEdit;

/**
 * Class ArtisanUsersServiceProvider.
 */
class ArtisanUsersServiceProvider extends ServiceProvider
{

    /**
     * Bootstrapping.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config/artisan_users.php' => config_path('artisan_users.php')], 'config');
        }
    }

    /**
     * Registering.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/artisan_users.php', 'artisan_users');

        $this->app->bind('artisan_users', function () {
            return new ArtisanUsers();
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                UserAdd::class,
                UserEdit::class,
            ]);
        }
    }
}
