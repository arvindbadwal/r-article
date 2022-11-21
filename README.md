# Article Service

This package is Article Service. Currently, History and Feedback service is support by this packages.

## Prerequisites

This package can be used in Laravel 6 or higher.

User model is required.

This package expects the primary key of your User model to be an auto-incrementing int.

## Installation

You can install the package via composer:

```bash
composer require cactus/article
```

Optional: The service provider will automatically get registered. Or you may manually add the service provider in your config/app.php file:
```php
'providers' => [
    // ...
    Cactus\Article\ArticleServiceProvider::class,
];
```

You should publish the migration and the config/permission.php config file with:

```bash
php artisan vendor:publish --provider="Cactus\Article\ArticleServiceProvider"
```

Clear your config cache.
```bash
php artisan optimize:clear
```

Run the migrations: After the config and migration have been published and configured:
```bash
php artisan migrate
```

## Usage

### Custom Route Prefix

### Custom User Model Namespace
If User Model has different namespace other than default laravel, change the User Model namespace in following

In `config/article.php` set/change `models.user`

### Unsilo Service
Set unsilo service setting
In `config/article.php` set/change `unsilo`

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email arvind.badwal@cactusglobal.com instead of using the issue tracker.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
