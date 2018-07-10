<?php

namespace Hkp22\Tests\Laravel\Reactions;

use Orchestra\Testbench\TestCase as Orchestra;
use Hkp22\Tests\Laravel\Reactions\Stubs\Models\User;

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

        $this->migratePackageTables();

        $this->migrateUnitTestTables();

        $this->registerPackageFactories();

        $this->registerUserModel();
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
     * Perform package database migrations.
     *
     * @return void
     */
    protected function migratePackageTables()
    {
        $this->loadMigrationsFrom([
            '--realpath' => database_path('migrations'),
        ]);
    }

    /**
     * Perform unit test database migrations.
     *
     * @return void
     */
    protected function migrateUnitTestTables()
    {
        $this->loadMigrationsFrom([
            '--realpath' => realpath(__DIR__ . '/database/migrations'),
        ]);
    }

    /**
     * Register package related model factories.
     *
     * @return void
     */
    protected function registerPackageFactories()
    {
        $pathToFactories = realpath(__DIR__ . '/database/factories');
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
