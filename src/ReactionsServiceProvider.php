<?php

namespace Qirolab\Laravel\Reactions;

use Illuminate\Support\ServiceProvider;

class ReactionsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrations();

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../config/reactions.php' => config_path('reactions.php'),
            ], 'config');

        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/reactions.php', 'reactions'
        );
    }

    /**
     * Load migrations.
     *
     * @return void
     */
    protected function loadMigrations()
    {
        if ($this->app->runningInConsole()) {
            $migrationsPath = __DIR__ . '/../migrations';

            $this->publishes([
                $migrationsPath => database_path('migrations'),
            ], 'migrations');

            $this->loadMigrationsFrom($migrationsPath);
        }
    }
}
