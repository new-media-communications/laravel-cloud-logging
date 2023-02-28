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
        /**
         * @var \Illuminate\Log\Logger $logger
         */
        $logger = Log::channel('stackdriver');
        $log = $logger->getLogger();

        $handler = null;

        if ($log instanceof Logger) {
            $handler = $log->getHandlers()[0] ?? null;
        }

        if (!is_null($handler) && !$handler instanceof PsrHandler) {
            $handler = null;
        }

        if (isset($_SERVER['GAE_SERVICE'])) {
            $handler = null;
        }

        Bootstrap::init($handler);
        Bootstrap::exceptionHandler($e);
    }
}
