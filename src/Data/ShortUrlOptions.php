<?php

namespace StevenFox\Larashurl\Data;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use StevenFox\Larashurl\Casts\ShortUrlOptionsCaster;

class ShortUrlOptions implements Castable
{
    public function __construct(
        public bool $track_visits = true,
        public bool $track_visitor = true,
        public ?string $visitor_resolver = null,
        public bool $track_ip_address = true,
        public bool $track_operating_system = true,
        public bool $track_operating_system_version = true,
        public bool $track_browser = true,
        public bool $track_browser_version = true,
        public bool $track_referer_url = true,
        public bool $track_device_type = true,
        public bool $track_user_agent = true,
        public bool $forward_query_params = true,
        public bool $require_https = true,
        public ?int $max_visits = null,
        public ?int $response_status_code = 301,
    ) {
        // Extend this class and alter the properties to configure
        // the default options for your short urls.
    }

    /**
     * Get the caster class to use when casting from / to this cast target.
     *
     * @param  array<string, mixed>  $arguments
     */
    public static function castUsing(array $arguments): CastsAttributes
    {
        return new ShortUrlOptionsCaster($arguments);
    }
}
