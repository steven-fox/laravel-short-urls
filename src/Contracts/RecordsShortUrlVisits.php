<?php

namespace StevenFox\Larashurl\Contracts;

use StevenFox\Larashurl\Events\ShortUrlVisited;
use StevenFox\Larashurl\Models\ShortUrlVisit;

interface RecordsShortUrlVisits
{
    public function record(ShortUrlVisited $event): ?ShortUrlVisit;
}
