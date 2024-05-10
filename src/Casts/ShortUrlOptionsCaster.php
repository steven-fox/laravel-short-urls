<?php

namespace StevenFox\Larashurl\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use StevenFox\Larashurl\Data\ShortUrlOptions;

class ShortUrlOptionsCaster implements CastsAttributes
{
    public function __construct(
        protected array $arguments = [],
    ) {
    }

    public function get(Model $model, string $key, mixed $value, array $attributes): ShortUrlOptions
    {
        $options = Json::decode($value, true);

        return new ShortUrlOptions(
            track_visits: $options['track_visits'] ?? null,
            track_visitor: $options['track_visitor'] ?? null,
            visitor_resolver: $options['visitor_resolver'] ?? null,
            track_ip_address: $options['track_ip_address'] ?? null,
            track_operating_system: $options['track_operating_system'] ?? null,
            track_operating_system_version: $options['track_operating_system_version'] ?? null,
            track_browser: $options['track_browser'] ?? null,
            track_browser_version: $options['track_browser_version'] ?? null,
            track_referer_url: $options['track_referer_url'] ?? null,
            track_device_type: $options['track_device_type'] ?? null,
            track_user_agent: $options['track_user_agent'] ?? null,
            forward_query_params: $options['forward_query_params'] ?? null,
            require_https: $options['require_https'] ?? null,
        );
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        /** @var ShortUrlOptions $value */
        return [$key => Json::encode($value)];
        //return [
        //    $key => [
        //        'track_visits' => $value->track_visits,
        //        'track_visitor' => $value->track_visitor,
        //        'visitor_resolver' => $value->visitor_resolver,
        //        'track_ip_address' => $value->track_ip_address,
        //        'track_operating_system' => $value->track_operating_system,
        //        'track_operating_system_version' => $value->track_operating_system_version,
        //        'track_browser' => $value->track_browser,
        //        'track_browser_version' => $value->track_browser_version,
        //        'track_referer_url' => $value->track_referer_url,
        //        'track_device_type' => $value->track_device_type,
        //        'track_user_agent' => $value->track_user_agent,
        //        'forward_query_params' => $value->forward_query_params,
        //        'require_https' => $value->require_https,
        //    ],
        //];
    }
}
