<?php

namespace StevenFox\Larashurl\Http\Web\Responses\ShortUrlVisit;

use StevenFox\Larashurl\Contracts\AdaptsShortUrlVisitResponses;
use StevenFox\Larashurl\Resolvers\Destination\DestinationUrlResolver;

abstract class Adapter implements AdaptsShortUrlVisitResponses
{
    public function __construct(
        protected DestinationUrlResolver $destinationUrlResolver,
    ) {
    }
}
