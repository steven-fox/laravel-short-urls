<?php

namespace StevenFox\Larashurl\Contracts;

interface GeneratesShortUrlKeys
{
    public function generate(int $seed): string;
}
