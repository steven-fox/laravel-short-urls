<?php

namespace StevenFox\Larashurl\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use StevenFox\Larashurl\LarashurlServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'StevenFox\\Larashurl\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            LarashurlServiceProvider::class,
        ];
    }

    public function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
    }
}
