<?php

namespace Qirolab\Tests\Laravel\Reactions;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as Orchestra;
use Qirolab\Tests\Laravel\Reactions\Stubs\Models\Article;
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
     * Register Test User model.
     *
     * @return void
     */
    protected function registerUserModel()
    {
        $this->app['config']->set('auth.providers.users.model', User::class);
    }

    protected function faker($locale = null)
    {
        $locale = $locale ?? Factory::DEFAULT_LOCALE;

        if (isset($this->app) && $this->app->bound(Generator::class)) {
            return $this->app->make(Generator::class, ['locale' => $locale]);
        }

        return Factory::create($locale);
    }

    public function factory($class, $attributes = [], $amount = null)
    {
        if (isset($amount) && is_int($amount)) {
            $resource = [];

            for ($i = 0; $i < $amount; $i++) {
                $resource[] = (new $class)->create($attributes);
            }

            return new Collection($resource);
        }

        return (new $class)->create($attributes);
    }

    public function createArticle($attributes = [], $amount = null)
    {
        return $this->factory(
            Article::class,
            array_merge(
                ['title' => $this->faker()->sentence],
                $attributes
            ),
            $amount
        );
    }

    public function createUser($attributes = [], $amount = null)
    {
        return $this->factory(
            User::class,
            array_merge(
                ['name' => $this->faker()->name],
                $attributes
            ),
            $amount
        );
    }
}
