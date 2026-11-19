<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    public function createApplication(): Application
    {
        $app = parent::createApplication();
        $connection = (string) $app['config']->get('database.default');
        $database = (string) $app['config']->get("database.connections.{$connection}.database");

        if (! $app->environment('testing') || ! str_ends_with($database, '_testing')) {
            throw new RuntimeException(
                "Unsafe test database configuration: tests must use a *_testing database, not [{$database}]. "
                .'Run `php artisan config:clear` before the test suite.',
            );
        }

        return $app;
    }
}
