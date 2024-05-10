<?php

namespace StevenFox\Larashurl\Actions;

use StevenFox\Larashurl\Concerns\UsesLarashurlModels;
use StevenFox\Larashurl\Contracts\ParsesUserAgents;
use StevenFox\Larashurl\Contracts\RecordsShortUrlVisits;
use StevenFox\Larashurl\Contracts\ResolvesVisitors;
use StevenFox\Larashurl\Contracts\UserAgent;
use StevenFox\Larashurl\Events\ShortUrlVisited;
use StevenFox\Larashurl\Exceptions\LarashurlException;
use StevenFox\Larashurl\Facades\Larashurl;
use StevenFox\Larashurl\Models\ShortUrlVisit;
use StevenFox\Larashurl\Resolvers\Visitors\VisitorResolverFactory;
use Throwable;

class RecordShortUrlVisit implements RecordsShortUrlVisits
{
    use UsesLarashurlModels;

    public function __construct(
        protected ParsesUserAgents $userAgentParser,
    ) {
    }

    public function record(ShortUrlVisited $event): ?ShortUrlVisit
    {
        if (! $event->shortUrl->options->track_visits) {
            return null;
        }

        return $this->doRecord($event);
    }

    protected function doRecord(ShortUrlVisited $event): ShortUrlVisit
    {
        $visit = new ShortUrlVisit();

        $visit->shortUrl()->associate($event->shortUrl);

        $visit->visited_at = $event->visitedAt ?? now();

        try {
            $userAgent = $this->parseUserAgent($event);

            $this->handleVisitorIdentification($visit, $event);

            if ($event->shortUrl->options->track_ip_address) {
                $visit->ip_address = $event->request->ip();
            }

            if ($event->shortUrl->options->track_operating_system) {
                $visit->operating_system = $userAgent->operatingSystem();
            }

            if ($event->shortUrl->options->track_operating_system_version) {
                $visit->operating_system_version = $userAgent->operatingSystemVersion();
            }

            if ($event->shortUrl->options->track_browser) {
                $visit->browser = $userAgent->browser();
            }

            if ($event->shortUrl->options->track_browser_version) {
                $visit->browser_version = $userAgent->browserVersion();
            }

            if ($event->shortUrl->options->track_referer_url) {
                $visit->referer_url = $event->request->headers->get('referer');
            }

            if ($event->shortUrl->options->track_device_type) {
                $visit->device_type = $userAgent->deviceType();
            }

            if ($event->shortUrl->options->track_user_agent) {
                $visit->user_agent = $userAgent->userAgentHeaderString();
            }
        } catch (Throwable $exception) {
            report($exception);
        } finally {
            if (! $visit->save()) {
                throw new LarashurlException('Failed to create a new short url visit.');
            }
        }

        return $visit;
    }

    protected function handleVisitorIdentification(ShortUrlVisit $visit, ShortUrlVisited $event): ShortUrlVisit
    {
        if (! (
            $event->shortUrl->options->track_visitor
            && Larashurl::config()->visitorIdentificationIsEnabled()
            && ($visitorResolver = $this->newVisitorResolver($event))
            && ($model = $visitorResolver->resolveVisitor($event))
        )) {
            return $visit;
        }

        $visit->visitor()->associate($model);

        return $visit;
    }

    protected function newVisitorResolver(ShortUrlVisited $event): ?ResolvesVisitors
    {
        return VisitorResolverFactory::for($event);
    }

    protected function parseUserAgent(ShortUrlVisited $event): UserAgent
    {
        return $this->userAgentParser->parse($event->request->headers);
    }
}
