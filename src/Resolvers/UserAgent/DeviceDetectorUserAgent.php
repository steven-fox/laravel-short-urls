<?php

namespace StevenFox\Larashurl\Resolvers\UserAgent;

use DeviceDetector\DeviceDetector;
use StevenFox\Larashurl\Contracts\UserAgent as UserAgentInterface;

class DeviceDetectorUserAgent implements UserAgentInterface
{
    public function __construct(
        protected DeviceDetector $deviceDetector,
    ) {
        if (! $this->deviceDetector->isParsed()) {
            $this->deviceDetector->parse();
        }
    }

    public function operatingSystem(): string
    {
        return $this->deviceDetector->getOs('name');
    }

    public function operatingSystemVersion(): string
    {
        return $this->deviceDetector->getOs('version');
    }

    public function browser(): string
    {
        return $this->deviceDetector->getClient('name');
    }

    public function browserVersion(): string
    {
        return $this->deviceDetector->getClient('version');
    }

    public function deviceType(): string
    {
        return $this->deviceDetector->getDeviceName();
    }

    public function userAgentHeaderString(): string
    {
        return $this->deviceDetector->getUserAgent();
    }
}
