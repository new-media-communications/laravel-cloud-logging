<?php

namespace Nmc\CloudLogging;

use Google\Cloud\Logging\LoggingClient;
use Google\Cloud\Logging\PsrLogger;
use Illuminate\Support\Arr;
use Monolog\Handler\PsrHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class CloudLogging
{
    public string $name;

    public function __construct(public array $config)
    {
        $this->name = (string) ($config['name'] ?? 'laravel-cloud-logging');
    }

    public function logger(): LoggerInterface
    {
        return new Logger($this->name, [new PsrHandler($this->psrLogger())]);
    }

    public function psrLogger()
    {
        $raw_credentials = $this->config['credentials'] ?? null;
        $credentials = is_string($raw_credentials) ? json_decode($raw_credentials, true) : null;
        $credentialsKey = is_array($credentials) ? 'keyFile' : (is_string($raw_credentials) ? 'keyFilePath' : 'keyFile');
        $credentialsValue = is_array($credentials) ? $credentials : $raw_credentials;

        $options = array_merge([
            'clientConfig' => array_filter([
                'projectId' => $this->config['project'],
                $credentialsKey => $credentialsValue,
            ]),
        ], $this->config['client_config'] ?? []);

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
            $client->logger($this->name, $options),
            $messageKey,
            $psrLoggerOptions + [
                'clientConfig' => $options['clientConfig']
            ]
        );

        return $logger;
    }
}
