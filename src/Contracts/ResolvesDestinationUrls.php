<?php

namespace StevenFox\Larashurl\Contracts;

use StevenFox\Larashurl\Http\Web\Requests\ShortUrlVisitRequest;
use StevenFox\Larashurl\Models\ShortUrl;
use StevenFox\Larashurl\Support\Url\Url;

interface ResolvesDestinationUrls
{
    public function preparedUrl(ShortUrlVisitRequest $request, ShortUrl $shortUrl): Url;
}
