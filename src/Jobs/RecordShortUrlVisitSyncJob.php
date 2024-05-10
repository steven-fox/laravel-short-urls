<?php

namespace StevenFox\Larashurl\Jobs;

use StevenFox\Larashurl\Contracts\RecordsShortUrlVisits;
use StevenFox\Larashurl\Events\ShortUrlVisited;

class RecordShortUrlVisitSyncJob
{
    public function __construct(
        public ShortUrlVisited $event,
    ) {
    }

    public function handle(RecordsShortUrlVisits $action): void
    {
        $action->record($this->event);
    }
}
