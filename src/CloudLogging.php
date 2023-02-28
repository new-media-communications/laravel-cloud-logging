<?php

namespace Nmc\CloudLogging;

use Google\Cloud\Logging\LoggingClient;
use Monolog\Handler\PsrHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class CloudLogging
{
    public static function create(array $config): LoggerInterface
    {
        $name = (string) ($config['name'] ?? 'laravel-cloud-logging');
        $credentialsKey = is_string($config['credentials']) ? 'keyFilePath' : 'keyFile';
        $logger = LoggingClient::psrBatchLogger($name, array_merge([
            'clientConfig' => [
                'projectId' => $config['project'],
                $credentialsKey => $config['credentials'],
            ],
        ], $config['client_config'] ?? []));

        return new Logger($name, [new PsrHandler($logger)]);
    }
}
