<?php

namespace StevenFox\Larashurl\Http\Web\Responses\ShortUrlVisit;

use Illuminate\Contracts\View\View;
use StevenFox\Larashurl\Http\Web\Requests\ShortUrlVisitRequest;
use StevenFox\Larashurl\Models\ShortUrl;

class ViewResponseAdapter extends Adapter
{
    public function for(ShortUrlVisitRequest $request, ShortUrl $shortUrl): View
    {
        return view('short-url-visit', [
            'shortUrl' => $shortUrl,
            'destinationUrl' => $this->destinationUrlResolver->preparedUrl($request, $shortUrl),
        ]);
    }
}
