<?php

namespace StevenFox\Larashurl\Support\Url;

class QueryParameters implements \Stringable
{
    final public function __construct(
        protected array $parameters = [],
    ) {
        //
    }

    public static function fromString(?string $query = ''): static
    {
        if ($query === '' || $query === null) {
            return new static();
        }

        $parameters = [];
        parse_str($query, $parameters);
        $parameters = array_map(
            static fn ($param) => $param !== '' ? $param : null, $parameters
        );

        return new static($parameters);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->has($key)) {
            return $this->parameters[$key];
        }

        return is_callable($default) ? $default() : $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->parameters);
    }

    public function set(string $key, string|array $value): static
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    public function unset(string $key): static
    {
        unset($this->parameters[$key]);

        return $this;
    }

    public function unsetAll(): static
    {
        $this->parameters = [];

        return $this;
    }

    public function all(): array
    {
        return $this->parameters;
    }

    public function __toString(): string
    {
        return http_build_query($this->parameters, '', '&', PHP_QUERY_RFC3986);
    }
}
