<?php

namespace Nmc\CloudLogging;

use Google\Cloud\Logging\LoggingClient;
use Illuminate\Support\Arr;
use Monolog\Handler\PsrHandler;
use Monolog\Logger;
use Nmc\CloudLogging\Google\PsrLogger;
use Psr\Log\LoggerInterface;

class CloudLogging
{
    public static function create(array $config): LoggerInterface
    {
        $name = (string) ($config['name'] ?? 'laravel-cloud-logging');

        $credentials = is_string($config['credentials']) ? json_decode($config['credentials'], true) : null;
        $credentialsKey = is_array($credentials) ? 'keyFile' : (is_string($config['credentials']) ? 'keyFilePath' : 'keyFile');
        $credentialsValue = is_array($credentials) ? $credentials : $config['credentials'];

        $options = array_merge([
            'clientConfig' => [
                'projectId' => $config['project'],
                $credentialsKey => $credentialsValue,
            ],
        ], $config['client_config'] ?? []);

        $options['batchEnabled'] = true;


        $client = new LoggingClient($options['clientConfig']);


        $messageKey = null;

        if (isset($options['messageKey'])) {
            $messageKey = $options['messageKey'];
            unset($options['messageKey']);
        }

        $psrLoggerOptions = Arr::only($options, [
            'metadataProvider',
            'batchEnabled',
            'debugOutput',
            'batchOptions',
            'clientConfig',
            'batchRunner',
            'closureSerializer',
            'debugOutputResource'
        ]);

        $logger = new PsrLogger(
            $client->logger($name, $options),
            $messageKey,
            $psrLoggerOptions + [
                'clientConfig' => $options['clientConfig']
            ]
        );

        return new Logger($name, [new PsrHandler($logger)]);
    }
}
