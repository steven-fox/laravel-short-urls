<?php

namespace StevenFox\Larashurl\Listeners;

use StevenFox\Larashurl\Events\ShortUrlVisited;
use StevenFox\Larashurl\Jobs\RecordShortUrlVisitSyncJob;

class RecordShortUrlVisit
{
    public function handle(ShortUrlVisited $event): bool
    {
        if (! $event->shortUrl->options->track_visits) {
            return true;
        }

        dispatch(new RecordShortUrlVisitSyncJob($event))->afterResponse();

        return true;
    }
}
