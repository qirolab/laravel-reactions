<?php

namespace Qirolab\Tests\Laravel\Reactions;

use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as Orchestra;
use Qirolab\Tests\Laravel\Reactions\Stubs\Models\User;

abstract class TestCase extends Orchestra
{
    /**
     * Actions to be performed on PHPUnit start.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->destroyPackageMigrations();

        $this->publishPackageMigrations();

        $this->migratePackageTables();

        $this->migrateUnitTestTables();

        $this->registerPackageFactories();

        // $this->registerTestMorphMaps();

        $this->registerUserModel();
    }

    /**
     * Publish package migrations.
     *
     * @return void
     */
    protected function publishPackageMigrations()
    {
        $this->artisan('vendor:publish', [
            '--force' => '',
            '--tag' => 'migrations',
        ]);
    }

    /**
     * Delete all published package migrations.
     *
     * @return void
     */
    protected function destroyPackageMigrations()
    {
        File::cleanDirectory(__DIR__.'/../vendor/orchestra/testbench-core/laravel/database/migrations');
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Load package service provider.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Qirolab\Laravel\Reactions\ReactionsServiceProvider::class,
        ];
    }

    /**
     * Perform package database migrations.
     *
     * @return void
     */
    protected function migratePackageTables()
    {
        $this->loadMigrationsFrom(database_path('migrations'));
    }

    /**
     * Perform unit test database migrations.
     *
     * @return void
     */
    protected function migrateUnitTestTables()
    {
        $this->loadMigrationsFrom(realpath(__DIR__.'/database/migrations'));
    }

    /**
     * Register package related model factories.
     *
     * @return void
     */
    protected function registerPackageFactories()
    {
        $pathToFactories = realpath(__DIR__.'/database/factories');
        $this->withFactories($pathToFactories);
    }

    /**
     * Register Test User model.
     *
     * @return void
     */
    protected function registerUserModel()
    {
        $this->app['config']->set('auth.providers.users.model', User::class);
    }
}
