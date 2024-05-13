# Larashurl
Short urls for Laravel apps, dashboard included.

# Work In Progress
This package is not yet ready for production applications. There are a number of v1 features missing along with comprehensive tests. A v1.0.0 release will signal a production ready state.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/steven-fox/laravel-short-urls.svg?style=flat-square)](https://packagist.org/packages/steven-fox/laravel-short-urls)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/steven-fox/laravel-short-urls/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/steven-fox/laravel-short-urls/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/steven-fox/laravel-short-urls/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/steven-fox/laravel-short-urls/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/steven-fox/laravel-short-urls.svg?style=flat-square)](https://packagist.org/packages/steven-fox/laravel-short-urls)


# Features

- Short urls can either respond with a redirect response or a customizable view. The customizable view would allow one to perform browser-based actions before performing a client side redirect. Ex: embed GA/FB/etc tracker javascript into the page or show a custom message..
- Short urls have a configurable destination url.
- Short urls have a configurable url key.
- Short urls can use a custom seed for the key generator.
- Short urls can use a custom key generator.
- Short urls can use a custom user agent parser.
- Short urls have an optional feature to attempt visitor identification (relating a short url visit back to a particular user of your system) based on multiple resolution options - authentication or custom query params.
- Short urls have a configurable redirect status code.
- Short urls can be attached to a campaign to group similar links together and control bulk settings.
- Short urls can have various options on an individual link basis:
  - Max number of uses (including single use).
  - Activation and deactivation datetime.
  - Tracking visits.
    - Recording user (when authenticated)
    - Visitor type (human, bot, etc)
    - Ip address
    - OS
    - OS version
    - Browser
    - Browser version
    - Referer url
    - Device type
    - Full user agent string
  - Forwarding query params
    - (Future feature) Ability to apply a certain set of query params to all redirections (like setting utm_campaign/utm_source/etc automatically).
  - Forcing https for the destination url
  - All options have a configurable default setting but can be overridden for each short url.
- Short url routes can have a custom prefix or a fully-customized route (with ability to turn off default route).
- Custom middleware for the route.
- Custom database connections for each model.
- Model factories
- Visit analytics
  - Charts with customizable timespan
- An admin UI to easily manage short urls, campaigns, and review visit analytics.
- API to CRUD short urls and visits (so someone could deploy this as a solo app and interact with it from a separate service)

### Future Features (v2):
- Pixel tracking
- Public stats
- Short url tagging
- Short url descriptions
- QR codes
- Smart urls (redirect to multiple urls based on device, browser, location, etc)
- Webhooks

## Installation

You can install the package via composer:

```bash
composer require steven-fox/laravel-short-urls
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-short-urls-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-short-urls-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-short-urls-views"
```

## Usage



## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Steven Fox](https://github.com/steven-fox)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
