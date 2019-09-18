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
    protected function setUp(): void
    {
        parent::setUp();

        $this->destroyPackageMigrations();

        $this->publishPackageMigrations();

        $this->setUpDatabase();

        $this->registerPackageFactories();

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
    public function tearDown(): void
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
     * Set up the database.
     *
     * @return void
     */
    protected function setUpDatabase()
    {
        include_once __DIR__.'/../migrations/2018_07_10_000000_create_reactions_table.php';
        include_once __DIR__.'/database/migrations/2018_07_10_000000_create_users_table.php';
        include_once __DIR__.'/database/migrations/2018_07_11_000000_create_articles_table.php';

        (new \CreateReactionsTable())->up();
        (new \CreateUsersTable())->up();
        (new \CreateArticlesTable())->up();
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
