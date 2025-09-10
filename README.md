# This is my package subscription-system-admin

[![Latest Version on Packagist](https://img.shields.io/packagist/v/loicgeek/subscription-system-admin.svg?style=flat-square)](https://packagist.org/packages/loicgeek/subscription-system-admin)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/loicgeek/subscription-system-admin/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/loicgeek/subscription-system-admin/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/loicgeek/subscription-system-admin/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/loicgeek/subscription-system-admin/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/loicgeek/subscription-system-admin.svg?style=flat-square)](https://packagist.org/packages/loicgeek/subscription-system-admin)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require loicgeek/subscription-system-admin
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="subscription-system-admin-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="subscription-system-admin-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="subscription-system-admin-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$subscriptionSystemAdmin = new NtechServices\SubscriptionSystemAdmin();
echo $subscriptionSystemAdmin->echoPhrase('Hello, NtechServices!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [loic](https://github.com/loicgeek)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


To use the package, you need to publish the config file and run the migrations:

```bash
php artisan vendor:publish --tag="subscription-system-admin-config"
php artisan migrate
```

