<?php

namespace StevenFox\Larashurl\Support\Url;

use Psr\Http\Message\UriInterface;
use StevenFox\Larashurl\Exceptions\LarashurlException;
use Stringable;

class Url implements Stringable, UriInterface
{
    protected string $scheme = 'https';

    protected string $host = '';

    protected string $path = '';

    protected QueryParameters $query;

    final public function __construct()
    {
        $this->query = new QueryParameters();
    }

    public static function create(): static
    {
        return new static();
    }

    public static function fromString(string $url): static
    {
        return static::make($url);
    }

    protected static function make(string $fromUrl): static
    {
        if (! $parts = parse_url($fromUrl)) {
            throw InvalidArgumentException::invalidUrl($fromUrl);
        }

        return (new static())
            ->withHost($parts['host'] ?? '')
            ->withPath($parts['path'] ?? '/')
            ->withQuery($parts['query'] ?? '');
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getAuthority(): string
    {
        return $this->host;
    }

    public function getUserInfo(): string
    {
        return '';
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): ?int
    {
        return null;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getBasename(): string
    {
        return $this->getSegment(-1);
    }

    public function getDirname(): string
    {
        $segments = $this->getSegments();

        array_pop($segments);

        return '/'.implode('/', $segments);
    }

    public function getQuery(): string
    {
        return (string) $this->query;
    }

    public function getQueryParameter(string $key, mixed $default = null): mixed
    {
        return $this->query->get($key, $default);
    }

    public function hasQueryParameter(string $key): bool
    {
        return $this->query->has($key);
    }

    public function getAllQueryParameters(): array
    {
        return $this->query->all();
    }

    public function withQueryParameter(string $key, string $value): static
    {
        $url = clone $this;
        $url->query->unset($key);

        $url->query->set($key, $value);

        return $url;
    }

    public function withQueryParameters(array $parameters): static
    {
        $parameters = array_merge($this->getAllQueryParameters(), $parameters);
        $url = clone $this;
        $url->query = new QueryParameters($parameters);

        return $url;
    }

    public function withoutQueryParameter(string $key): static
    {
        $url = clone $this;
        $url->query->unset($key);

        return $url;
    }

    public function withoutQueryParameters(): static
    {
        $url = clone $this;
        $url->query->unsetAll();

        return $url;
    }

    public function getFragment(): string
    {
        return '';
    }

    public function getSegments(): array
    {
        return explode('/', trim($this->path, '/'));
    }

    public function getSegment(int $index, mixed $default = null): mixed
    {
        $segments = $this->getSegments();

        if ($index === 0) {
            throw InvalidArgumentException::segmentZeroDoesNotExist();
        }

        if ($index < 0) {
            $segments = array_reverse($segments);
            $index = (int) abs($index);
        }

        return $segments[$index - 1] ?? $default;
    }

    public function getFirstSegment(): mixed
    {
        $segments = $this->getSegments();

        return $segments[0] ?? null;
    }

    public function getLastSegment(): ?string
    {
        $segments = $this->getSegments();

        return end($segments);
    }

    public function withScheme(string $scheme): static
    {
        if (! ($scheme === 'http' || $scheme === 'https')) {
            throw InvalidArgumentException::invalidScheme($scheme);
        }

        $this->scheme = $scheme;

        return $this;
    }

    public function withUserInfo(string $user, ?string $password = null): static
    {
        return $this;
    }

    public function withHost(string $host): static
    {
        $url = clone $this;

        $url->host = $host;

        return $url;
    }

    public function withPort(?int $port): static
    {
        return $this;
    }

    public function withPath(string $path): static
    {
        $url = clone $this;

        if (! str_starts_with($path, '/')) {
            $path = '/'.$path;
        }

        $url->path = $path;

        return $url;
    }

    public function withDirname(string $dirname): static
    {
        $dirname = trim($dirname, '/');

        if (! $this->getBasename()) {
            return $this->withPath($dirname);
        }

        return $this->withPath($dirname.'/'.$this->getBasename());
    }

    public function withBasename(string $basename): static
    {
        $basename = trim($basename, '/');

        if ($this->getDirname() === '/') {
            return $this->withPath('/'.$basename);
        }

        return $this->withPath($this->getDirname().'/'.$basename);
    }

    public function withQuery(?string $query = ''): static
    {
        $url = clone $this;

        $url->query = QueryParameters::fromString($query);

        return $url;
    }

    public function withFragment($fragment): static
    {
        return $this;
    }

    public function matches(self $url): bool
    {
        return (string) $this === (string) $url;
    }

    public function toString(): string
    {
        return $this->__toString();
    }

    public function __toString(): string
    {
        $url = $this->getScheme().'://';

        $url .= $this->getAuthority();

        if ($this->getPath() !== '/') {
            $url .= $this->getPath();
        }

        if ($this->getQuery() !== '') {
            $url .= '?'.$this->getQuery();
        }

        return $url;
    }

    public function __clone()
    {
        $this->query = clone $this->query;
    }
}
