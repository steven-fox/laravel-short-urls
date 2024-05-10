<?php

namespace StevenFox\Larashurl\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use StevenFox\Larashurl\Concerns\UsesLarashurlModels;

/**
 * @property ?int $id
 * @property ?int $short_url_id
 * @property ?int $visitor_id
 * @property ?string $visitor_type
 * @property ?string $ip_address
 * @property ?string $operating_system
 * @property ?string $operating_system_version
 * @property ?string $browser
 * @property ?string $browser_version
 * @property ?string $referer_url
 * @property ?string $device_type
 * @property ?string $user_agent
 * @property ?string $query_params
 * @property Carbon|CarbonInterface|null $visited_at
 * @property ?ShortUrl $shortUrl
 * @property ?Model $visitor
 */
class ShortUrlVisit extends Model
{
    use UsesLarashurlModels;

    protected $guarded = [];

    protected $table = 'short_url_visits';

    /**
     * @return array{visited_at: 'datetime'}
     */
    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<ShortUrl, $this>
     */
    public function shortUrl(): BelongsTo
    {
        return $this->belongsTo(static::getShortUrlClass());
    }

    public function visitor(): MorphTo
    {
        return $this->morphTo('visitor');
    }
}
