<?php

namespace StevenFox\Larashurl\Resolvers\UrlKey;

use Sqids\Sqids;
use Sqids\SqidsInterface;
use StevenFox\Larashurl\Concerns\UsesLarashurlModels;
use StevenFox\Larashurl\Contracts\GeneratesShortUrlKeys;
use StevenFox\Larashurl\Facades\Larashurl;

class SqidUrlKeyGenerator implements GeneratesShortUrlKeys
{
    use UsesLarashurlModels;

    protected SqidsInterface $sqidder;

    public function __construct(?SqidsInterface $sqidder = null)
    {
        $config = Larashurl::config();

        $this->sqidder = $sqidder ?? new Sqids(
            alphabet: $config->urlKeyAlphabet(),
            minLength: $config->minimumUrlKeyLength(),
        );
    }

    public function generate(?int $seed = null): string
    {
        return $seed
            ? $this->sqidder->encode([$seed])
            : $this->findUniqueKey();
    }

    protected function findUniqueKey(): string
    {
        $shortUrlModel = static::newShortUrlModel();
        $latestId = $shortUrlModel->newQuery()->max('id') ?? 0;

        do {
            $latestId++;
            $key = $this->generate($latestId);
        } while ($shortUrlModel->newQuery()->where('url_key', $key)->exists());

        return $key;
    }
}
