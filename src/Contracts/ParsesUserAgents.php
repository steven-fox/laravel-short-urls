<?php

namespace StevenFox\Larashurl\Contracts;

use Symfony\Component\HttpFoundation\HeaderBag;

interface ParsesUserAgents
{
    public function parse(HeaderBag $headers): UserAgent;
}
