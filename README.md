# Subscription System Admin

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ntech-services/subscription-system-admin.svg?style=flat-square)](https://packagist.org/packages/ntech-services/subscription-system-admin)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/ntech-services/subscription-system-admin/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ntech-services/subscription-system-admin/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/ntech-services/subscription-system-admin/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/ntech-services/subscription-system-admin/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ntech-services/subscription-system-admin.svg?style=flat-square)](https://packagist.org/packages/ntech-services/subscription-system-admin)

A comprehensive Laravel package that provides a complete subscription system administration interface for Filament admin panels. This package allows you to manage subscriptions, plans, features, usage tracking, and coupons with a beautiful and intuitive admin interface.

Perfect for SaaS applications, membership sites, or any Laravel project that needs subscription management capabilities. The package integrates seamlessly with Filament's admin panel and provides both API and web routes for maximum flexibility.

## Features

- 📊 **Subscription Management** - Complete CRUD operations for subscriptions
- 💳 **Plan Management** - Create and manage subscription plans with different tiers
- ⚡ **Feature Usage Tracking** - Monitor and limit feature usage per subscription
- 🎟️ **Coupon System** - Create and manage discount coupons
- 🔌 **API & Web Routes** - Flexible routing options for different use cases
- 🌍 **Multi-language Support** - Easily translatable interface
- 🎨 **Filament Integration** - Beautiful admin interface with Filament components
- ⚙️ **Configurable** - Highly customizable through configuration files

## Requirements

- PHP 8.1 or higher
- Laravel 10.0 or higher
- Filament 4.x

## Installation

### Step 1: Install Filament (if not already installed)

If you don't have Filament installed yet, follow the documentation here: https://filamentphp.com/docs/4.x/introduction/installation#installing-the-panel-builder

### Step 2: Install the Package

You can install the package via Composer:

```bash
composer require ntech-services/subscription-system-admin
```

### Step 3: Run Migrations

Run this command to install the subscription system tables:

```bash
php artisan migrate:subscription-system
```

### Step 4: Configure the Plugin

In your `AdminPanelProvider`, add the plugin:

```php
use NtechServices\SubscriptionSystemAdmin\SubscriptionSystemAdminPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ... other configuration
        ->plugins([
            SubscriptionSystemAdminPlugin::make()
                ->subscriptionFeatureUsage(false) // Optional: disable feature usage
                ->coupons(true) // Optional: enable coupons
        ]);
}
```

### Step 5: Publish Configuration (Optional)

You can publish and customize the configuration file:

```bash
php artisan vendor:publish --tag="subscription-system-admin-config"
```

### Step 6: Run Additional Migrations

After publishing the config, run the standard migrations:

```bash
php artisan migrate
```

## Configuration

You can publish the config file to customize the package behavior:

```bash
php artisan vendor:publish --tag="subscription-system-admin-config"
```

This is the contents of the published config file:

```php
<?php

return [
    // Enable or disable API routes
    'enable_api_routes' => true,
    
    // Enable or disable web routes
    'enable_web_routes' => false,
    
    // API routes prefix
    'api_prefix' => 'api/ntech-subscription',
    
    // Web routes prefix
    'web_prefix' => 'ntech-subscription',
    
    // Middleware for API routes
    'api_middleware' => ['api'],
    
    // Middleware for web routes
    'web_middleware' => ['web', 'auth'],
];
```

## Publishing Assets

### Publish Views

You can publish and customize the views:

```bash
php artisan vendor:publish --tag="subscription-system-admin-views"
```

### Publish Migrations

You can publish the migration files to customize them:

```bash
php artisan vendor:publish --tag="subscription-system-admin-migrations"
```

### Publish Translations

To add new languages or customize existing translations:

```bash
php artisan vendor:publish --tag="subscription-system-admin-lang"
```

## Usage

### Basic Usage

```php
use NtechServices\SubscriptionSystemAdmin\SubscriptionSystemAdmin;

$subscriptionSystemAdmin = new SubscriptionSystemAdmin();
echo $subscriptionSystemAdmin->echoPhrase('Hello, NtechServices!');
```

### Plugin Configuration Options

The plugin provides several configuration methods:

```php
SubscriptionSystemAdminPlugin::make()
    ->subscriptionFeatureUsage(true)  // Enable/disable feature usage tracking
    ->coupons(true)                   // Enable/disable coupon system
    ->apiRoutes(true)                 // Enable/disable API routes
    ->webRoutes(false)                // Enable/disable web routes
```

### API Routes

When API routes are enabled, you can access the subscription system via REST API:

- `GET /api/ntech-subscription/subscriptions` - List all subscriptions
- `POST /api/ntech-subscription/subscriptions` - Create a new subscription
- `GET /api/ntech-subscription/subscriptions/{id}` - Get a specific subscription
- `PUT /api/ntech-subscription/subscriptions/{id}` - Update a subscription
- `DELETE /api/ntech-subscription/subscriptions/{id}` - Delete a subscription

### Web Routes

When web routes are enabled, you can access the subscription system via web interface at the configured prefix.

## Managing Subscriptions

The package provides a complete admin interface through Filament where you can:

1. **Create and manage subscription plans** with different features and pricing
2. **Monitor active subscriptions** and their usage
3. **Track feature usage** per subscription to enforce limits
4. **Create and manage coupons** for discounts and promotions
5. **Generate reports** on subscription metrics

## Customization

### Custom Models

You can extend the default models by publishing them and modifying as needed. The package is designed to be flexible and allows for extensive customization.

### Custom Views

Publish the views and modify them to match your application's design:

```bash
php artisan vendor:publish --tag="subscription-system-admin-views"
```

### Custom Translations

Add support for additional languages by publishing and modifying the language files:

```bash
php artisan vendor:publish --tag="subscription-system-admin-lang"
```

## Testing

Run the test suite:

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

- [Loic NGOU](https://github.com/loicgeek)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Support

If you encounter any issues or have questions, please:

1. Check the [documentation](https://github.com/ntech-services/subscription-system-admin)
2. Search existing [issues](https://github.com/ntech-services/subscription-system-admin/issues)
3. Create a new issue if your problem isn't already reported

For commercial support or custom development, please contact [Loic NGOU](https://github.com/loicgeek).