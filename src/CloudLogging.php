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

        $credentials = is_string($config['credentials']) ? json_decode($config['credentials'], true) : null;
        $credentialsKey = is_array($credentials) ? 'keyFile' : (is_string($config['credentials']) ? 'keyFilePath' : 'keyFile');
        $credentialsValue = is_array($credentials) ? $credentials : $config['credentials'];

        $logger = LoggingClient::psrBatchLogger($name, array_merge([
            'clientConfig' => [
                'projectId' => $config['project'],
                $credentialsKey => $credentialsValue,
            ],
        ], $config['client_config'] ?? []));

        return new Logger($name, [new PsrHandler($logger)]);
    }
}
