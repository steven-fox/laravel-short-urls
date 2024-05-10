<?php

namespace StevenFox\Larashurl\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use StevenFox\Larashurl\Concerns\UsesLarashurlModels;
use StevenFox\Larashurl\Data\ShortUrlOptions;
use StevenFox\Larashurl\Enums\ShortUrlResponseType;
use StevenFox\Larashurl\Facades\Larashurl;
use StevenFox\Larashurl\Query\Eloquent\ShortUrlEloquentBuilder;
use StevenFox\Larashurl\Support\Url\Url;

/**
 * @property ?int $id
 * @property int|string|null $short_url_campaign_id
 * @property ?ShortUrlResponseType $response_type
 * @property ?string $destination_url
 * @property ?string $url_key
 * @property ?CarbonInterface $active_at
 * @property ?CarbonInterface $expires_at
 * @property ShortUrlOptions $options
 */
class ShortUrl extends Model
{
    use UsesLarashurlModels;

    protected $guarded = [];

    protected $table = 'short_urls';

    /**
     * @return array{response_type: 'StevenFox\Larashurl\Enums\ShortUrlResponseType', active_at: 'datetime', expires_at: 'datetime', options: 'StevenFox\Larashurl\Data\ShortUrlOptions'}
     */
    protected function casts(): array
    {
        return [
            'response_type' => ShortUrlResponseType::class,
            'active_at' => 'datetime',
            'expires_at' => 'datetime',
            'options' => Larashurl::config()->shortUrlOptionsClass(),
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'url_key';
    }

    public function newEloquentBuilder($query): ShortUrlEloquentBuilder
    {
        return new ShortUrlEloquentBuilder($query);
    }

    /**
     * @return HasMany<ShortUrlVisit>
     */
    public function visits(): HasMany
    {
        return $this->hasMany(static::getShortUrlVisitClass());
    }

    /**
     * @return HasOne<ShortUrlVisit>
     */
    public function latestVisit(): HasOne
    {
        return $this->hasOne(static::getShortUrlVisitClass())
            ->latestOfMany('visited_at');
    }

    /**
     * @return HasOne<ShortUrlVisit>
     */
    public function firstVisit(): HasOne
    {
        return $this->hasOne(static::getShortUrlVisitClass())
            ->oldestOfMany('visited_at');
    }

    /**
     * @return BelongsTo<ShortUrlCampaign, $this>
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(static::getShortUrlCampaignClass());
    }

    public function route(bool $absolute = true): Url
    {
        $urlString = \Illuminate\Support\Facades\URL::route(
            Larashurl::config()->routeName(),
            $this->url_key,
            $absolute
        );

        return Url::fromString($urlString);
    }

    public function path(): string
    {
        return \Illuminate\Support\Facades\URL::route(
            Larashurl::config()->routeName(),
            $this->url_key,
            false
        );
    }

    public function canBeVisited(mixed $user): bool
    {
        return $this->isWithinActiveWindow()
            && $this->hasNotExceededMaxVisits()
            && $this->campaignPermitsVisits();
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

    public function campaignPermitsVisits(): bool
    {
        return (! $this->campaign)
               || $this->campaign->permitsVisits();
    }
}
