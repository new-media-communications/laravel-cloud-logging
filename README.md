# Google Cloud Logging For Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nmc/laravel-cloud-logging.svg?style=flat-square)](https://packagist.org/packages/nmc/laravel-cloud-logging)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/nmc/laravel-cloud-logging/run-tests?label=tests)](https://github.com/nmc/laravel-cloud-logging/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/nmc/laravel-cloud-logging/Check%20&%20fix%20styling?label=code%20style)](https://github.com/nmc/laravel-cloud-logging/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
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

    public function report(Throwable $e)
    {
        if (CloudErrorReporting::isEnabled() && $this->shouldReport($e)) {
            CloudErrorReporting::report($e);
        } else {
            parent::report($e);
        }
    }
```

## Usage

```php
use Illuminate\Support\Facades\Log;
Log::channel('stackdriver')->info('Test');
```
## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
