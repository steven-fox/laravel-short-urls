<?php

namespace StevenFox\Larashurl\Contracts;

interface UserAgent
{
    public function operatingSystem(): string;

    public function operatingSystemVersion(): string;

    public function browser(): string;

    public function browserVersion(): string;

    public function deviceType(): string;

    public function userAgentHeaderString(): string;
}
