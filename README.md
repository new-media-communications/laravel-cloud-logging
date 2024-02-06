# Google Cloud Logging For Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nmc/laravel-cloud-logging.svg?style=flat-square)](https://packagist.org/packages/nmc/laravel-cloud-logging)
[![Total Downloads](https://img.shields.io/packagist/dt/nmc/laravel-cloud-logging.svg?style=flat-square)](https://packagist.org/packages/nmc/laravel-cloud-logging)

## Installation

You can install the package via composer:

```bash
composer require nmc/laravel-cloud-logging
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Nmc\CloudLogging\CloudLoggingServiceProvider" --tag="cloud-logging-config"
```

This is the contents of the published config file:

```php
return [
    'enable_error_reporting' => env('CLOUD_LOGGING_ENABLE_ERROR_REPORTING', isset($_SERVER['GAE_SERVICE']) ?? false),
    'logger' => [
        'driver' => 'stackdriver',
        'level' => env('LOG_LEVEL', 'debug'),
        'name' => env('CLOUD_LOGGING_NAME', 'laravel-cloud-logging'),
        'project' => env('CLOUD_LOGGING_PROJECT_ID'),
        'credentials' => env('CLOUD_LOGGING_CREDENTIALS', config_path('stackdriver.json')),
    ],
];
```

Edit `app/Exceptions/Handler.php`
```php
    use Nmc\CloudLogging\CloudErrorReporting;

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if (CloudErrorReporting::isEnabled()) {
                CloudErrorReporting::report($e);
            }
        });
    }
```

## Usage

```php
use Illuminate\Support\Facades\Log;
Log::channel('stackdriver')->info('Test');
```
## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
