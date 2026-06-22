# Changelog

All notable changes to `laravel-cloud-logging` will be documented in this file.

## [2.0.2] - 2026-06-22

### Changed
- Updated `google/cloud-logging` to allow `^2.1.0`

## [2.0.1] - 2026-04-09

### Changed
- Updated `google/cloud-error-reporting` to `^0.26.0`

## [2.0.0] - 2025-08-01

### Added
- Laravel 13 support (`illuminate/contracts` `^13.0`)
- PHP 8.5 support

### Changed
- Dropped support for PHP 8.1 and 8.2
- Dropped support for Laravel 9 and 10

## [1.3.2] - 2025-08-01

### Changed
- Updated `google/cloud-error-reporting` to `^0.25.0`
- Updated `google/cloud-logging` to `^1.34.0`
- Removed unused imports in `CloudErrorReporting`

## [1.3.1] - 2025-08-01

### Changed
- Updated `google/cloud-error-reporting` to `^0.23.0`

## [1.3.0] - 2025-07-30

### Added
- Laravel 12 support (`illuminate/contracts` `^12.0`)
- PHP 8.4 support

## [1.2.0] - 2024-10-04

### Added
- Laravel 11 support (`illuminate/contracts` `^11.0`)

## [1.1.1] - 2024-02-26

### Fixed
- Auto-register default `stackdriver` logging channel from config when not already defined

## [1.1.0] - 2024-02-06

### Changed
- Refactored `CloudLogging` from static methods to an instance-based class with constructor injection
- `CloudLogging` is now bound in the service container
- Switched to upstream `Google\Cloud\Logging\PsrLogger` and removed custom `Google\PsrLogger` fork
- Updated error reporting to use the service container to resolve the PSR logger
- Updated README with modern Laravel exception handler `register()` syntax
- Updated `google/cloud-error-reporting` to `^0.22.1`

### Added
- PHP 8.3 support

## [1.0.3] - 2023-02-28

### Added
- Custom `PsrLogger` implementation with batch logging support and metadata provider integration

### Changed
- Updated `google/cloud-error-reporting` to `^0.19.10`
- Updated `google/cloud-logging` to `^1.25.2`

## [1.0.2] - 2023-02-28

### Fixed
- Support JSON string credentials in addition to file paths and arrays

## [1.0.1] - 2023-02-28

### Changed
- Updated package description to "Google Cloud Logging For Laravel"
- Fixed README title

## [1.0.0] - 2023-02-28

### Added
- Initial release
- Google Cloud Logging integration for Laravel
- Google Cloud Error Reporting integration
- Support for Laravel 9 and 10
- Configurable logging channel (`stackdriver`)
