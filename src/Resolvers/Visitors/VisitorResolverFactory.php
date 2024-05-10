<?php

namespace StevenFox\Larashurl\Resolvers\Visitors;

use StevenFox\Larashurl\Contracts\ResolvesVisitors;
use StevenFox\Larashurl\Events\ShortUrlVisited;
use StevenFox\Larashurl\Exceptions\LarashurlException;
use StevenFox\Larashurl\Facades\Larashurl;

class VisitorResolverFactory
{
    public static function for(ShortUrlVisited $event): ?ResolvesVisitors
    {
        // In the event the full class name is stored on the ShortUrl,
        // we will immediately return that class from the container.
        if (class_exists($event->shortUrl->options->visitor_resolver ?? '')) {
            return app($event->shortUrl->options->visitor_resolver);
        }

        // Otherwise, it's assumed the stored resolver is either null or an alias
        // from the config file.
        $config = Larashurl::config();

        $resolverAlias = $event->shortUrl->options->visitor_resolver;

        // If a resolver alias wasn't stored with the ShortUrl, we either
        // return null or use the auth resolver as a backup.
        if (! $resolverAlias) {
            if ($config->visitorIdentificationUseAuthResolverAsBackup()) {
                $resolverAlias = 'auth';
            } else {
                return null;
            }
        }

        $resolverClass = $config->visitorResolvers()[$resolverAlias] ?? null;

        if (! $resolverClass) {
            throw new LarashurlException("Unknown visitor resolver: {$event->shortUrl->options->visitor_resolver}");
        }

        return app($resolverClass);
    }
}
