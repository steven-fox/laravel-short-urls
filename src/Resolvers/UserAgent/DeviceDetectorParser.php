<?php

namespace StevenFox\Larashurl\Resolvers\UserAgent;

use DeviceDetector\ClientHints;
use DeviceDetector\DeviceDetector;
use StevenFox\Larashurl\Contracts\ParsesUserAgents;
use StevenFox\Larashurl\Contracts\UserAgent;
use Symfony\Component\HttpFoundation\HeaderBag;

class DeviceDetectorParser implements ParsesUserAgents
{
    public function parse(HeaderBag $headers): UserAgent
    {
        $detector = new DeviceDetector($headers->get('user-agent', ''), ClientHints::factory($headers->all()));

        $detector->parse();

        return new DeviceDetectorUserAgent($detector);
    }
}
