<?php

use StevenFox\Larashurl\Facades\Larashurl;
use StevenFox\Larashurl\Jobs\RecordShortUrlVisitSyncJob;

it('can handle a short url visit', function () {
    $this->withoutExceptionHandling();

    \Illuminate\Support\Facades\Bus::fake();

    $shurl = Larashurl::shurlBuilder()
        ->withDestinationUrl($destination = 'https://example.com')
        ->create();

    $response = $this->get($shurl->route());

    $response->assertRedirect($destination);

    \Illuminate\Support\Facades\Bus::assertDispatchedAfterResponse(
        RecordShortUrlVisitSyncJob::class,
        function (RecordShortUrlVisitSyncJob $job) use ($shurl): bool {
            return $job->event->shortUrl->is($shurl);
        });
});
