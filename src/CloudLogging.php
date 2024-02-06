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
        $credentials = is_string($this->config['credentials']) ? json_decode($this->config['credentials'], true) : null;
        $credentialsKey = is_array($credentials) ? 'keyFile' : (is_string($this->config['credentials']) ? 'keyFilePath' : 'keyFile');
        $credentialsValue = is_array($credentials) ? $credentials : $this->config['credentials'];

        $options = array_merge([
            'clientConfig' => [
                'projectId' => $this->config['project'],
                $credentialsKey => $credentialsValue,
            ],
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
