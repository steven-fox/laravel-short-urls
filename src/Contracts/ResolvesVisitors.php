<?php

namespace StevenFox\Larashurl\Contracts;

use Illuminate\Database\Eloquent\Model;
use StevenFox\Larashurl\Events\ShortUrlVisited;

interface ResolvesVisitors
{
    public function resolveVisitor(ShortUrlVisited $event): ?Model;
}
