<?php

namespace StevenFox\Larashurl;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use StevenFox\Larashurl\Commands\ShortUrlCommand;

class LarashurlServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-short-urls')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-short-urls_table')
            ->hasCommand(ShortUrlCommand::class);
    }
}
