<?php

use StevenFox\Larashurl\Actions\RecordShortUrlVisit;
use StevenFox\Larashurl\Data\ShortUrlOptions;
use StevenFox\Larashurl\Http\Web\Responses\ShortUrlVisit\AdapterFactory;
use StevenFox\Larashurl\Models\ShortUrl;
use StevenFox\Larashurl\Models\ShortUrlCampaign;
use StevenFox\Larashurl\Models\ShortUrlVisit;
use StevenFox\Larashurl\Resolvers\Destination\DestinationUrlResolver;
use StevenFox\Larashurl\Resolvers\UrlKey\SqidUrlKeyGenerator;
use StevenFox\Larashurl\Resolvers\UserAgent\DeviceDetectorParser;
use StevenFox\Larashurl\Resolvers\Visitors\AuthVisitorResolver;
use StevenFox\Larashurl\Resolvers\Visitors\QueryParsingVisitorResolver;

return [
    'models' => [
        'ShortUrl' => ShortUrl::class,
        'ShortUrlVisit' => ShortUrlVisit::class,
        'ShortUrlCampaign' => ShortUrlCampaign::class,
    ],

    /**
     * Adds a polymorphic Visitor relationship to the ShortUrlVisit model/table
     * that records the identification of the visitor when applicable.
     */
    'visitor_identification' => [
        'enabled' => true,

        'resolvers' => [
            'auth' => AuthVisitorResolver::class,
            'visitor_query_params' => QueryParsingVisitorResolver::class,
        ],

        'use_auth_resolver_as_backup' => true,
    ],

    /**
     * The options to store with each short url.
     * You can configure the default options by extending the
     * package's ShortUrlOptions class and altering the default
     * values for each property. Be sure to update this config
     * value to your new options class if you choose to do this.
     */
    'short_url_options_class' => ShortUrlOptions::class,

    'routing' => [
        'disable_default_routing' => false,

        'middleware' => [
            'web',
        ],

        'domain' => null,

        'prefix' => 's',

        'name' => 'web.short-url-visit',

        'short_url_visit_response_adapter_factory' => AdapterFactory::class,
    ],

    'url_key' => [
        'minimum_length' => 3,

        // It's recommended to shuffle your alphabet so that the
        // generated url keys are unique to your app.
        // An easy way to do this is to run
        // `php (new \Random\Randomizer)->shuffleBytes('abcdef...')`
        // and paste the output of that function here.
        'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
    ],

    'resolvers' => [
        'destination_url' => DestinationUrlResolver::class,

        'url_key_generator' => SqidUrlKeyGenerator::class,

        'user_agent_parser' => DeviceDetectorParser::class,
    ],

    'actions' => [
        'record_visit' => RecordShortUrlVisit::class,
    ],
];
