<?php
return [
    'enable_error_reporting' => env('CLOUD_LOGGING_ENABLE_ERROR_REPORTING', isset($_SERVER['GAE_SERVICE']) ?: false),
    'logger' => [
        'driver' => 'stackdriver',
        'level' => env('LOG_LEVEL', 'debug'),
        'name' => env('CLOUD_LOGGING_NAME', 'laravel-cloud-logging'),
        'project' => env('CLOUD_LOGGING_PROJECT_ID'),
        'credentials' => env('CLOUD_LOGGING_CREDENTIALS', config_path('stackdriver.json')),
    ],
];
