<?php

namespace StevenFox\Larashurl\Http\Web\Responses\ShortUrlVisit;

use StevenFox\Larashurl\Contracts\AdaptsShortUrlVisitResponses;
use StevenFox\Larashurl\Enums\ShortUrlResponseType;
use StevenFox\Larashurl\Exceptions\LarashurlException;
use StevenFox\Larashurl\Http\Web\Requests\ShortUrlVisitRequest;
use StevenFox\Larashurl\Models\ShortUrl;

class AdapterFactory implements AdaptsShortUrlVisitResponses
{
    /** @var array<string, class-string<AdaptsShortUrlVisitResponses>> */
    protected array $adapterMap = [
        ShortUrlResponseType::redirect->value => RedirectResponseAdapter::class,
        ShortUrlResponseType::view->value => ViewResponseAdapter::class,
    ];

    public function for(ShortUrlVisitRequest $request, ShortUrl $shortUrl): mixed
    {
        $adapterClass = $this->adapterMap[$shortUrl->response_type->value] ?? null;

        if (! ($adapterClass && class_exists($adapterClass))) {
            throw new LarashurlException("Missing response adapter for response type '{$shortUrl->response_type->value}'.");
        }

        if (! is_a($adapterClass, AdaptsShortUrlVisitResponses::class, true)) {
            throw new LarashurlException("Invalid response adapter for response type '{$shortUrl->response_type->value}'.");
        }

        return app($adapterClass)->for($request, $shortUrl);
    }
}
