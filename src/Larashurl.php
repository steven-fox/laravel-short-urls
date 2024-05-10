<?php

namespace StevenFox\Larashurl;

use Illuminate\Contracts\Config\Repository;
use StevenFox\Larashurl\Builders\ShortUrlBuilder;
use StevenFox\Larashurl\Config\Helper;

class Larashurl
{
    protected Helper $configHelper;

    public function __construct(
        protected Repository $configRepository,
    ) {
    }

    public function config(): Helper
    {
        return $this->configHelper
               ?? $this->configHelper = new Helper($this->configRepository);
    }

    public function shurlBuilder(): ShortUrlBuilder
    {
        return app(ShortUrlBuilder::class);
    }
}
