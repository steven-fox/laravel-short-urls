<?php

namespace StevenFox\Larashurl\Contracts;

use StevenFox\Larashurl\Http\Web\Requests\ShortUrlVisitRequest;
use StevenFox\Larashurl\Models\ShortUrl;

interface AdaptsShortUrlVisitResponses
{
    public function for(ShortUrlVisitRequest $request, ShortUrl $shortUrl): mixed;
}
