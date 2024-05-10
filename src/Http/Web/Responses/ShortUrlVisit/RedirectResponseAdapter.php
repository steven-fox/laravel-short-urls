<?php

namespace StevenFox\Larashurl\Http\Web\Responses\ShortUrlVisit;

use Illuminate\Http\RedirectResponse;
use StevenFox\Larashurl\Http\Web\Requests\ShortUrlVisitRequest;
use StevenFox\Larashurl\Models\ShortUrl;

class RedirectResponseAdapter extends Adapter
{
    public function for(ShortUrlVisitRequest $request, ShortUrl $shortUrl): RedirectResponse
    {
        return redirect(
            to: (string) $this->destinationUrlResolver->preparedUrl($request, $shortUrl),
            status: $shortUrl->options->response_status_code ?? 301,
            secure: $shortUrl->options->require_https ?? true,
        );
    }
}
