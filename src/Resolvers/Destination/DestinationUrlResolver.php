<?php

namespace StevenFox\Larashurl\Resolvers\Destination;

use StevenFox\Larashurl\Contracts\ResolvesDestinationUrls;
use StevenFox\Larashurl\Http\Web\Requests\ShortUrlVisitRequest;
use StevenFox\Larashurl\Models\ShortUrl;
use StevenFox\Larashurl\Support\Url\Url;

class DestinationUrlResolver implements ResolvesDestinationUrls
{
    public function preparedUrl(ShortUrlVisitRequest $request, ShortUrl $shortUrl): Url
    {
        $url = Url::fromString($shortUrl->destination_url);

        if ($shortUrl->options->require_https) {
            $url = $url->withScheme('https');
        }

        if ($shortUrl->options->forward_query_params) {
            $url = $url->withQuery($request->getQueryString());
        }

        return $url;
    }
}
