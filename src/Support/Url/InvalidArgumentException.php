<?php

namespace StevenFox\Larashurl\Support\Url;

use InvalidArgumentException as BaseInvalidArgumentException;

final class InvalidArgumentException extends BaseInvalidArgumentException
{
    public static function invalidUrl(string $url): self
    {
        return new self("The string '`{$url}`' is not a valid url.");
    }

    public static function invalidScheme(string $scheme): self
    {
        return new self("The string '`{$scheme}`' is not a valid scheme for short urls.");
    }

    public static function segmentZeroDoesNotExist(): self
    {
        return new self("Segment 0 doesn't exist. Segments can be retrieved by using 1-based index or a negative index.");
    }
}
