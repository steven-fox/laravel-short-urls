<?php

namespace StevenFox\Larashurl\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use StevenFox\Larashurl\Concerns\UsesLarashurlModels;
use StevenFox\Larashurl\Data\ShortUrlOptions;
use StevenFox\Larashurl\Facades\Larashurl;
use StevenFox\Larashurl\Query\Eloquent\ShortUrlCampaignEloquentBuilder;

/**
 * @property ?int $id
 * @property ?string $name
 * @property ?string $description
 * @property ?CarbonInterface $active_at
 * @property ?CarbonInterface $expires_at
 * @property ShortUrlOptions $options
 */
class ShortUrlCampaign extends Model
{
    use UsesLarashurlModels;

    protected $guarded = [];

    protected $table = 'short_url_campaigns';

    /**
     * @return array{active_at: 'datetime', expires_at: 'datetime', options: 'StevenFox\Larashurl\Data\ShortUrlOptions'}
     */
    protected function casts(): array
    {
        return [
            'active_at' => 'datetime',
            'expires_at' => 'datetime',
            'options' => Larashurl::config()->shortUrlOptionsClass(),
        ];
    }

    public function newEloquentBuilder($query): ShortUrlCampaignEloquentBuilder
    {
        return new ShortUrlCampaignEloquentBuilder($query);
    }

    /**
     * @return HasMany<ShortUrl>
     */
    public function shortUrls(): HasMany
    {
        return $this->hasMany(static::getShortUrlClass());
    }

    /**
     * @return HasManyThrough<ShortUrlVisit>
     */
    public function visits(): HasManyThrough
    {
        return $this->hasManyThrough(static::getShortUrlVisitClass(), static::getShortUrlClass());
    }

    public function permitsVisits(): bool
    {
        return $this->isWithinActiveWindow()
               && $this->hasNotExceededMaxVisits();
    }

    public function isWithinActiveWindow(): bool
    {
        return (! $this->active_at || now()->isAfter($this->active_at))
               && (! $this->expires_at || now()->isBefore($this->expires_at));
    }

    public function hasNotExceededMaxVisits(): bool
    {
        return (! $this->options->max_visits)
               || ($this->visits()->count() < $this->options->max_visits);
    }
}
