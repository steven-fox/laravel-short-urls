<?php

namespace StevenFox\Larashurl\Database\Factories;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\Factory;
use StevenFox\Larashurl\Data\ShortUrlOptions;
use StevenFox\Larashurl\Models\ShortUrlCampaign;

class ShortUrlCampaignFactory extends Factory
{
    protected $model = ShortUrlCampaign::class;

    public function definition(): array
    {
        return [
            'name' => 'Short Url Campaign '.$this->faker->numberBetween(1, 100),
            'description' => fn (array $attrs): string => 'Description for '.$attrs['name'].'.',
            'active_at' => null,
            'expires_at' => null,
            'options' => new ShortUrlOptions(),
        ];
    }

    public function activateAt(DateTimeInterface $dateTime): static
    {
        return $this->state(fn (array $attributes) => [
            'active_at' => $dateTime,
        ]);
    }

    public function expireAt(DateTimeInterface $dateTime): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => $dateTime,
        ]);
    }

    public function withOptions(ShortUrlOptions $options): static
    {
        return $this->state(fn (array $attributes) => [
            'options' => $options,
        ]);
    }
}
