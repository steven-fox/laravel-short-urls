<?php

namespace StevenFox\Larashurl\Events;

use Carbon\CarbonInterface;
use StevenFox\Larashurl\Http\Web\Requests\ShortUrlVisitRequest;
use StevenFox\Larashurl\Models\ShortUrl;

class ShortUrlVisited
{
    public function __construct(
        public ShortUrl $shortUrl,
        public ShortUrlVisitRequest $request,
        public ?CarbonInterface $visitedAt = null
    ) {
        $this->visitedAt = $visitedAt ?? now();
    }
}
