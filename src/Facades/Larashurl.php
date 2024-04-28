<?php

namespace StevenFox\Larashurl\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \StevenFox\Larashurl\Larashurl
 */
class Larashurl extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \StevenFox\Larashurl\Larashurl::class;
    }
}
