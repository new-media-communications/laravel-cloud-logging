<?php

namespace Nmc\CloudLogging;

use Illuminate\Contracts\Container\Container;
use Illuminate\Log\LogManager;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CloudLoggingServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('cloud-logging')
            ->hasConfigFile();
    }

    public function packageRegistered()
    {
        $this->app->bind(CloudLogging::class, function(Container $app) {
            $config = $app['config']['logging.channels.stackdriver'] ?? $app['config']['cloud-logging.logger'];
            return new CloudLogging($config);
        });
    }

    /**
     * @return void
     */
    public function packageBooted()
    {
        if (!config('logging.channels.stackdriver')) {
            config(['logging.channels.stackdriver' => config('cloud-logging.logger')]);
        }

        /**
         * @var LogManager $log
         */
        $log = $this->app->make('log');

        $log->extend(
            'stackdriver',
            fn (Container $app) => $app->get(CloudLogging::class)->logger()
        );
    }
}
