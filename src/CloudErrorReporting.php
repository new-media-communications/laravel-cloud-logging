<?php

namespace Nmc\CloudLogging;

use Google\Cloud\ErrorReporting\Bootstrap;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\PsrHandler;
use Monolog\Logger;
use Throwable;

class CloudErrorReporting
{
    public static function isEnabled(): bool
    {
        return (bool) config('cloud-logging.enable_error_reporting', false);
    }

    public static function report(Throwable $e): void
    {
        $handler = app(CloudLogging::class)->psrLogger();

        if (isset($_SERVER['GAE_SERVICE'])) {
            $handler = null;
        }

        Bootstrap::init($handler);
        Bootstrap::exceptionHandler($e);
    }
}
