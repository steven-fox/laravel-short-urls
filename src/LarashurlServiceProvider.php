<?php

namespace StevenFox\Larashurl;

use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use StevenFox\Larashurl\Contracts\AdaptsShortUrlVisitResponses;
use StevenFox\Larashurl\Contracts\GeneratesShortUrlKeys;
use StevenFox\Larashurl\Contracts\ParsesUserAgents;
use StevenFox\Larashurl\Contracts\RecordsShortUrlVisits;
use StevenFox\Larashurl\Contracts\ResolvesDestinationUrls;
use StevenFox\Larashurl\Events\ShortUrlVisited;
use StevenFox\Larashurl\Facades\Larashurl;
use StevenFox\Larashurl\Listeners\RecordShortUrlVisit;
use StevenFox\Larashurl\Models\ShortUrl;

class LarashurlServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-short-urls')
            ->hasConfigFile('larashurl')
            ->hasViews()
            ->hasMigration('create_short_urls_tables')
            ->runsMigrations()
            ->hasRoute('web');
    }

    public function packageRegistered(): void
    {
        $config = Larashurl::config();

        $this->app->bind(
            ParsesUserAgents::class,
            $config->userAgentParserClass()
        );

        $this->app->bind(
            ResolvesDestinationUrls::class,
            $config->destinationUrlResolverClass()
        );

        $this->app->bind(
            GeneratesShortUrlKeys::class,
            $config->urlKeyGeneratorClass()
        );

        $this->app->bind(
            RecordsShortUrlVisits::class,
            $config->recordVisitActionClass()
        );

        $this->app->bind(
            AdaptsShortUrlVisitResponses::class,
            $config->shortUrlVisitResponseAdapterFactory()
        );
    }

    public function packageBooted(): void
    {
        Route::middlewareGroup('larashurl', Larashurl::config()->middleware());

        Gate::define('visitShortUrl', function (mixed $user, ShortUrl $shortUrl) {
            return $shortUrl->canBeVisited($user)
                ? Response::allow()
                : Response::denyAsNotFound();
        });

        Event::listen(ShortUrlVisited::class, RecordShortUrlVisit::class);
    }
}
