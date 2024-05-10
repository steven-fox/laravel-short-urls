<?php

namespace StevenFox\Larashurl\Resolvers\Visitors;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use StevenFox\Larashurl\Contracts\ResolvesVisitors;
use StevenFox\Larashurl\Events\ShortUrlVisited;

class AuthVisitorResolver implements ResolvesVisitors
{
    public function resolveVisitor(ShortUrlVisited $event): ?Model
    {
        if (($user = Auth::user()) instanceof Model) {
            /** @var Model $user */
            return $user;
        }

        return null;
    }
}
