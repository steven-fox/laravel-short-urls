# Short urls with dashboard for Laravel apps.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/steven-fox/laravel-short-urls.svg?style=flat-square)](https://packagist.org/packages/steven-fox/laravel-short-urls)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/steven-fox/laravel-short-urls/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/steven-fox/laravel-short-urls/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/steven-fox/laravel-short-urls/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/steven-fox/laravel-short-urls/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/steven-fox/laravel-short-urls.svg?style=flat-square)](https://packagist.org/packages/steven-fox/laravel-short-urls)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

Features from other short url generators:
- Pixel tracking
- Public stats
- Short url tagging
- Short url descriptions
- QR codes
- Smart urls (redirect to multiple urls based on device, browser, location, etc)
- Webhooks
- 

KEY FEATURES
Larashurl
- Short urls can either respond with a redirect response or a customizable view. The customizable view would allow one to embed GA/FB/etc tracker javascript into the page before performing a browser-based redirect.
- Short urls have a configurable destination url.
- ** Short urls can have a query/url param that would make it easy to track individual visitors that aren't necessarily auth users. So, say a newsletter link: it would be the same short url but could have some sort of url param that would allow one to record the subscriber that clicked it, which is different than a user_id/auth_id.
- Short urls have a configurable url key.
- Short urls can use a custom seed for the key generator.
- Short urls can use a custom key generator (interface).
- Short urls can use a custom user agent parser (interface).
- Short urls have a configurable redirect status code.
- Short urls can be associated with a model? Would this allow one to dynamically determine the destination url?
- Short urls can be attached to multiple campaigns.
- Short urls can have options:
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
    - Full user agent
  - Forward query params
    - And maybe an option to apply a certain set of query params?
  - Using https for the destination url
  - All options have configurable default (globally) but can be overridden for each short url
- How to handle custom attributes on the ShortUrl model?
  - Mailcoach method where the model classes are dynamic and users can easily override them?
  - Short url package method with a beforeCreate() method that loops through callbacks?
- Use the Conditional trait on the PendingShortUrl class.
- Custom prefix or fully-customized route (with ability to turn off default route)
- Custom middleware for the route
- Custom database connection
- Model factories
- Visit analytics
  - Charts with customizable timespan
- API to CRUD short urls and visits (so someone could deploy this as a solo app and interact with it from a separate service)

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

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-short-urls-views"
```

## Usage

```php
$shortUrl = new StevenFox\Larashurl();
echo $shortUrl->echoPhrase('Hello, StevenFox!');
```

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
