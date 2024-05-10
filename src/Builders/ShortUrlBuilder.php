<?php

namespace StevenFox\Larashurl\Builders;

use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use StevenFox\Larashurl\Concerns\UsesLarashurlModels;
use StevenFox\Larashurl\Contracts\GeneratesShortUrlKeys;
use StevenFox\Larashurl\Data\ShortUrlOptions;
use StevenFox\Larashurl\Enums\ShortUrlResponseType;
use StevenFox\Larashurl\Exceptions\LarashurlException;
use StevenFox\Larashurl\Facades\Larashurl;
use StevenFox\Larashurl\Models\ShortUrl;
use StevenFox\Larashurl\Models\ShortUrlCampaign;
use StevenFox\Larashurl\Support\Url\Url;

class ShortUrlBuilder
{
    use UsesLarashurlModels;

    protected ShortUrlResponseType $responseType = ShortUrlResponseType::redirect;

    protected Url $destinationUrl;

    protected string $urlKey = '';

    protected ?int $urlKeySeed = null;

    protected GeneratesShortUrlKeys $urlKeyGenerator;

    protected ?CarbonInterface $activeAt = null;

    protected ?CarbonInterface $expiresAt = null;

    protected ShortUrlOptions $options;

    protected array $beforeCreationCallbacks = [];

    protected array $afterCreationCallbacks = [];

    public function __construct(
        ?GeneratesShortUrlKeys $urlKeyGenerator = null
    ) {
        $this->options = new ShortUrlOptions();

        $this->urlKeyGenerator = $urlKeyGenerator ?? app(GeneratesShortUrlKeys::class);
    }

    public function withResponseType(ShortUrlResponseType $responseType): static
    {
        $this->responseType = $responseType;

        return $this;
    }

    public function withDestinationUrl(string|Url $url): static
    {
        if (! Str::startsWith((string) $url, ['http://', 'https://'])) {
            throw new LarashurlException('The destination url must begin with http:// or https://');
        }

        $this->destinationUrl = Url::fromString((string) $url);

        return $this;
    }

    public function withUrlKey(string $key): static
    {
        $this->urlKey = $key;

        return $this;
    }

    public function withUrlKeySeed(int $seed): static
    {
        $this->urlKeySeed = $seed;

        return $this;
    }

    public function withUrlKeyGenerator(GeneratesShortUrlKeys $generator): static
    {
        $this->urlKeyGenerator = $generator;

        return $this;
    }

    public function activateAt(CarbonInterface $dateTime): static
    {
        $this->activeAt = $dateTime;

        return $this;
    }

    public function expireAt(CarbonInterface $dateTime): static
    {
        $this->expiresAt = $dateTime;

        return $this;
    }

    public function withOptions(ShortUrlOptions $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function withDefaultOptions(): static
    {
        $this->options = new ShortUrlOptions;

        return $this;
    }

    public function relateToCampaign(ShortUrlCampaign $campaign): static
    {
        if (! $campaign->exists()) {
            throw new LarashurlException('Cannot attach a short url to an unsaved campaign.');
        }

        $this->afterCreation(
            fn (ShortUrl $shortUrl) => $shortUrl->campaign()->associate($campaign)
        );

        return $this;
    }

    public function beforeCreation(callable $callback): static
    {
        $this->beforeCreationCallbacks[] = $callback;

        return $this;
    }

    public function afterCreation(callable $callback): static
    {
        $this->afterCreationCallbacks[] = $callback;

        return $this;
    }

    public function create(): ShortUrl
    {
        $this->finalizeAttributes()
            ->validateForCreation();

        $shortUrl = $this->prepareShortUrl();

        $this->callBeforeCreationCallbacks($shortUrl);

        if (! $shortUrl->save()) {
            throw new LarashurlException('Failed to create short url.');
        }

        $this->callAfterCreationCallbacks($shortUrl);

        return $shortUrl;
    }

    protected function validateForCreation(): static
    {
        $this->validateAttributes();

        return $this;
    }

    protected function finalizeAttributes(): static
    {
        $this->prepareDestinationUrl()
            ->prepareUrlKey();

        return $this;
    }

    protected function validateAttributes(): static
    {
        $config = Larashurl::config();

        Validator::validate(
            [
                'response_type' => $this->responseType->value,
                'url_key' => $this->urlKey,
                'destination_url' => (string) $this->destinationUrl,
                'active_at' => $this->activeAt,
                'expires_at' => $this->expiresAt,
            ],
            [
                'response_type' => [
                    'required',
                    Rule::in(ShortUrlResponseType::cases()),
                ],
                'url_key' => [
                    'required',
                    'min:'.$config->minimumUrlKeyLength(),
                    Rule::unique(static::getShortUrlTableName(), 'url_key'),
                ],
                'destination_url' => ['required', 'url'],
                'active_at' => ['sometimes', 'nullable', 'date'],
                'expires_at' => ['sometimes', 'nullable', 'date', 'after:active_at'],
            ]
        );

        return $this;
    }

    protected function prepareShortUrl(): ShortUrl
    {
        $shortUrl = new ShortUrl;

        $shortUrl->response_type = $this->responseType;
        $shortUrl->destination_url = $this->destinationUrl;
        $shortUrl->url_key = $this->urlKey;
        $shortUrl->active_at = $this->activeAt;
        $shortUrl->expires_at = $this->expiresAt;
        $shortUrl->options = $this->options;

        return $shortUrl;
    }

    protected function prepareDestinationUrl(): static
    {
        if ($this->options->require_https) {
            $this->destinationUrl = $this->destinationUrl->withScheme('https');
        }

        return $this;
    }

    protected function prepareUrlKey(): static
    {
        if ($this->urlKey) {
            return $this;
        }

        $this->urlKey = $this->urlKeyGenerator->generate($this->urlKeySeed);

        return $this;
    }

    protected function callBeforeCreationCallbacks(ShortUrl $shortUrl): void
    {
        foreach ($this->beforeCreationCallbacks as $callback) {
            $callback($shortUrl);
        }
    }

    protected function callAfterCreationCallbacks(ShortUrl $shortUrl): void
    {
        foreach ($this->afterCreationCallbacks as $callback) {
            $callback($shortUrl);
        }
    }
}
