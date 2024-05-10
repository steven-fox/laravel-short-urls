<?php

namespace StevenFox\Larashurl\Http\Web\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use StevenFox\Larashurl\Concerns\UsesLarashurlModels;
use StevenFox\Larashurl\Contracts\AdaptsShortUrlVisitResponses;
use StevenFox\Larashurl\Events\ShortUrlVisited;
use StevenFox\Larashurl\Http\Web\Requests\ShortUrlVisitRequest;
use StevenFox\Larashurl\Models\ShortUrl;

class ShortUrlVisitController
{
    use AuthorizesRequests;
    use UsesLarashurlModels;

    public function __construct(
        protected AdaptsShortUrlVisitResponses $responseAdapterFactory
    ) {
    }

    public function __invoke(ShortUrlVisitRequest $request, string $urlKey): mixed
    {
        [$shortUrl, $missingResponse] = $this->resolveShortUrl($request, $urlKey);

        if ($missingResponse) {
            return $missingResponse;
        }

        $this->authorizeVisit($request, $shortUrl);

        $response = $this->responseAdapterFactory->for($request, $shortUrl);

        event(new ShortUrlVisited($shortUrl, $request, now()));

        return $response;
    }

    /**
     * @return array{?ShortUrl, mixed}
     */
    protected function resolveShortUrl(ShortUrlVisitRequest $request, string $urlKey): array
    {
        try {
            $shortUrlModel = static::newShortUrlModel();

            return [$shortUrlModel->resolveRouteBindingQuery($shortUrlModel, $urlKey)->firstOrFail(), null]; // @phpstan-ignore-line
        } catch (ModelNotFoundException $exception) {
            if ($request->route()->getMissing()) {
                return [null, $request->route()->getMissing()($request, $exception)];
            }

            throw $exception;
        }
    }

    protected function authorizeVisit(ShortUrlVisitRequest $request, ShortUrl $shortUrl): void
    {
        $this->authorize('visitShortUrl', $shortUrl);
    }
}
