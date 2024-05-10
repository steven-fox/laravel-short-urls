<?php

namespace StevenFox\Larashurl\Concerns;

use StevenFox\Larashurl\Facades\Larashurl;
use StevenFox\Larashurl\Models\ShortUrl;
use StevenFox\Larashurl\Models\ShortUrlCampaign;
use StevenFox\Larashurl\Models\ShortUrlVisit;

trait UsesLarashurlModels
{
    /**
     * @return class-string<ShortUrl>
     */
    public static function getShortUrlClass(): string
    {
        return Larashurl::config()->shortUrlClass();
    }

    public static function newShortUrlModel(): ShortUrl
    {
        $class = self::getShortUrlClass();

        return new $class;
    }

    public static function getShortUrlTableName(): string
    {
        return self::newShortUrlModel()->getTable();
    }

    public static function getShortUrlVisitClass(): string
    {
        return Larashurl::config()->shortUrlVisitClass();
    }

    public static function newShortUrlVisitModel(): ShortUrlVisit
    {
        $class = self::getShortUrlVisitClass();

        return new $class;
    }

    public static function getShortUrlVisitTableName(): string
    {
        return self::newShortUrlVisitModel()->getTable();
    }

    public static function getShortUrlCampaignClass(): string
    {
        return Larashurl::config()->shortUrlCampaignClass();
    }

    public static function newShortUrlCampaignModel(): ShortUrlCampaign
    {
        $class = self::getShortUrlCampaignClass();

        return new $class;
    }

    public static function getShortUrlCampaignTableName(): string
    {
        return self::newShortUrlCampaignModel()->getTable();
    }
}
