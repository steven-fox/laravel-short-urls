<?php

namespace StevenFox\Larashurl\Enums;

enum ShortUrlResponseType: string
{
    case redirect = 'redirect';
    case view = 'view';
}
