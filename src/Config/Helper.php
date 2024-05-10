<?php

namespace StevenFox\Larashurl\Config;

use Illuminate\Contracts\Config\Repository;
use StevenFox\Larashurl\Actions\RecordShortUrlVisit;
use StevenFox\Larashurl\Contracts\AdaptsShortUrlVisitResponses;
use StevenFox\Larashurl\Contracts\GeneratesShortUrlKeys;
use StevenFox\Larashurl\Contracts\ParsesUserAgents;
use StevenFox\Larashurl\Contracts\RecordsShortUrlVisits;
use StevenFox\Larashurl\Contracts\ResolvesDestinationUrls;
use StevenFox\Larashurl\Contracts\ResolvesVisitors;
use StevenFox\Larashurl\Data\ShortUrlOptions;
use StevenFox\Larashurl\Http\Web\Responses\ShortUrlVisit\AdapterFactory;
use StevenFox\Larashurl\Models\ShortUrl;
use StevenFox\Larashurl\Models\ShortUrlCampaign;
use StevenFox\Larashurl\Models\ShortUrlVisit;
use StevenFox\Larashurl\Resolvers\Destination\DestinationUrlResolver;
use StevenFox\Larashurl\Resolvers\UrlKey\SqidUrlKeyGenerator;
use StevenFox\Larashurl\Resolvers\UserAgent\DeviceDetectorParser;

class Helper
{
    public function __construct(protected Repository $config)
    {
    }

    /**
     * @return class-string<ShortUrl>
     */
    public function shortUrlClass(): string
    {
        return $this->config->get('larashurl.models.ShortUrl', ShortUrl::class);
    }

    /**
     * @return class-string<ShortUrlVisit>
     */
    public function shortUrlVisitClass(): string
    {
        return $this->config->get('larashurl.models.ShortUrlVisit', ShortUrlVisit::class);
    }

    /**
     * @return class-string<ShortUrlCampaign>
     */
    public function shortUrlCampaignClass(): string
    {
        return $this->config->get('larashurl.models.ShortUrlCampaign', ShortUrlCampaign::class);
    }

    public function visitorIdentificationIsEnabled(): bool
    {
        return $this->config->get('larashurl.features.visitor_identification', false);
    }

    public function visitorIdentificationUseAuthResolverAsBackup(): bool
    {
        return $this->config->get('larashurl.visitor_identification.use_auth_resolver_as_backup', true);
    }

    /**
     * @return class-string<ShortUrlOptions>
     */
    public function shortUrlOptionsClass(): string
    {
        return $this->config->get('larashurl.short_url_options_class', ShortUrlOptions::class);
    }

    public function disableDefaultRouting(): bool
    {
        return $this->config->get('larashurl.routing.disable_default_routing', false);
    }

    public function middleware(): array
    {
        return $this->config->get('larashurl.middleware', []);
    }

    public function routeDomain(): ?string
    {
        return $this->config->get('larashurl.routing.domain');
    }

    public function routePrefix(): ?string
    {
        return $this->config->get('larashurl.routing.prefix');
    }

    public function routeName(): ?string
    {
        return $this->config->get('larashurl.routing.name', 'web.short-url-visit');
    }

    /**
     * @return class-string<AdaptsShortUrlVisitResponses>
     */
    public function shortUrlVisitResponseAdapterFactory(): string
    {
        return $this->config->get('larashurl.routing.short_url_visit_response_adapter_factory', AdapterFactory::class);
    }

    public function minimumUrlKeyLength(): int
    {
        return $this->config->get('larashurl.url_key.minimum_length', 3);
    }

    public function urlKeyAlphabet(): string
    {
        return $this->config->get(
            'larashurl.url_key.alphabet',
            'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
        );
    }

    /**
     * @return array<string, class-string<ResolvesVisitors>>
     */
    public function visitorResolvers(): array
    {
        return $this->config->get('larashurl.visitor_identification.resolvers', []);
    }

    /**
     * @return class-string<ResolvesDestinationUrls>
     */
    public function destinationUrlResolverClass(): string
    {
        return $this->config->get('larashurl.resolvers.destination_url', DestinationUrlResolver::class);
    }

    /**
     * @return class-string<GeneratesShortUrlKeys>
     */
    public function urlKeyGeneratorClass(): string
    {
        return $this->config->get('larashurl.resolvers.url_key_generator', SqidUrlKeyGenerator::class);
    }

    /**
     * @return class-string<ParsesUserAgents>
     */
    public function userAgentParserClass(): string
    {
        return $this->config->get('larashurl.resolvers.user_agent_parser', DeviceDetectorParser::class);
    }

    /**
     * @return class-string<RecordsShortUrlVisits>
     */
    public function recordVisitActionClass(): string
    {
        return $this->config->get('larashurl.actions.record_visit', RecordShortUrlVisit::class);
    }
}
