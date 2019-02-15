# Laravel Heartbeat

[![Latest Stable Version](https://poser.pugx.org/eXolnet/laravel-heartbeat/v/stable?format=flat-square)](https://packagist.org/packages/eXolnet/laravel-heartbeat)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/eXolnet/laravel-heartbeat/master.svg?style=flat-square)](https://travis-ci.org/eXolnet/laravel-heartbeat)
[![Total Downloads](https://img.shields.io/packagist/dt/eXolnet/laravel-heartbeat.svg?style=flat-square)](https://packagist.org/packages/eXolnet/laravel-heartbeat)

Periodically schedule a job to send a heartbeat to a monitoring system.

## Installation

Require this package with composer:

```
composer require exolnet/laravel-heartbeat
```

If you don't use package auto-discovery, add the service provider to the ``providers`` array in `config/app.php`:

```
Exolnet\Heartbeat\HeartbeatServiceProvider::class
```

And the facade to the ``facades`` array in `config/app.php`: 

```
'Heartbeat' => Exolnet\Heartbeat\HeartbeatFacade::class
```

## Config

### Config Files

In order to edit the default configuration (where for e.g. you can find `schedule` and `presets`) for this package you may execute:

```
php artisan vendor:publish --provider="Exolnet\Heartbeat\HeartbeatServiceProvider"
```

After that, `config/heartbeat.php` will be created. Inside this file you will find all the fields that can be edited in this package.

The default configuration file can be found here : [config/heartbeat.php](config/heartbeat.php)

## Usage

Laravel Heartbeat should be working right after the service provider is loaded.

### Default
By default Laravel Heartbeat is configure to use queue and the scheduler to create heartbeats. It will store it's files on Laravel's public disk. If you want a dedicated disk you should add a disk to app/config/filesystems.php and change the `disk` option in the `Queue preset` 

```
...
'presets' => [
    ...
    'queue' => [
        ...
        'disk' => 'local',
        ...
    ],
    ...
```

### Available Channels
#### Disk
Channel used to store heartbeats in a Laravel Filesystem disk.
#### File
Channel used to store heartbeats in a file
#### Http
Channel used to make a heartbeat by calling a url

## Testing

To run the phpUnit tests, please use:

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE OF CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email security@exolnet.com instead of using the issue tracker.

## Credits

- [Alexandre D'Eschambeault](https://github.com/xel1045)
- [Simon Gaudreau](https://github.com/Gandhi11)
- [All Contributors](../../contributors)

## License

This code is licensed under the [MIT license](http://choosealicense.com/licenses/mit/). 
Please see the [license file](LICENSE) for more information.
