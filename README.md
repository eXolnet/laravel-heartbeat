# Laravel Heartbeat

[![Latest Stable Version](https://poser.pugx.org/eXolnet/laravel-heartbeat/v/stable?format=flat-square)](https://packagist.org/packages/eXolnet/laravel-heartbeat)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/github/workflow/status/eXolnet/laravel-heartbeat/tests?label=tests&style=flat-square)](https://github.com/eXolnet/laravel-heartbeat/actions?query=workflow%3Atests)
[![Total Downloads](https://img.shields.io/packagist/dt/eXolnet/laravel-heartbeat.svg?style=flat-square)](https://packagist.org/packages/eXolnet/laravel-heartbeat)

Periodically schedule a job to send a heartbeat to a monitoring system.

## Installation

Require this package with composer:

```bash
composer require exolnet/laravel-heartbeat
```

If you don't use package auto-discovery, add the service provider to the ``providers`` array in `config/app.php`:

```php
Exolnet\Heartbeat\HeartbeatServiceProvider::class
```

And the facade to the ``facades`` array in `config/app.php`: 

```php
'Heartbeat' => Exolnet\Heartbeat\HeartbeatFacade::class
```

### Configuration

In order to edit this package's default configuration (where for example you can define presets and configure queue
monitoring), you may execute:

```bash
php artisan vendor:publish --provider="Exolnet\Heartbeat\HeartbeatServiceProvider"
```

After that, the configuration file `config/heartbeat.php` will be created. This file contains all the options that can
be configured for this package. The default configuration file [can be found here](config/heartbeat.php).

## Usage

### Sending Signals

#### Using The Heartbeat Facade

You may send heartbeat signals via the `Heartbeat` facade. To do so, specify the channel you want to use and call the
`signal` method with the arguments required by this channel. For example, with the `Http` channel, it may look like
this:

```php
Heartbeat::channel('http')->signal('https://beats.envoyer.io/heartbeat/example');
```

Alternatively, you may send the same signal using method helpers defined in the `Heartbeat` facade by calling the
driver method directly:

```php
Heartbeat::http('https://beats.envoyer.io/heartbeat/example');
```

#### Using Artisan

Heartbeat can also be used with the `heartbeat` Artisan command. To do so, specify the channel and specify the
channel's arguments in the same order as their `signal` method. Here two examples of how to use it:

```bash
php artisan heartbeat preset preset-name
php artisan heartbeat http https://beats.envoyer.io/heartbeat/example
```

### Specifying Presets

The handy `preset` channel allows you to define all your heartbeat configuration in the configuration file. First, let's
look at an example of a `preset` configuration:

```php
'presets' => [
    'envoyer' => [
        'channel' => 'http',
        'url' => 'https://beats.envoyer.io/heartbeat/example',
    ],
]
```

This configuration can now be used by the `preset` channel to invoke the signal method on the `http` channel:

```php
Heartbeat::preset('envoyer');
```

Heartbeat's default configuration list for each channel all the parameters required.

### Queue Monitoring

Heartbeat can also be used to monitor your Laravel queue system. This feature is enabled by default when you have
Laravel's scheduler enabled. The preset `queue` is used and a file named `queue.heartbeat` will be created in your
`storage/app` folder every fifteen minutes.

To configure this, just publish the package configuration and update the `queue` preset.

### Available Channels

#### Disk

Channel used to store heartbeats in a Laravel Filesystem disk.

#### File

Channel used to store heartbeats in a file

#### Http

Channel used to make a heartbeat by calling a url

### Custom Channels

Heartbeat ships with a handful of channels, but you may want to write your own drivers to deliver signals via other
channels. In order to do so, define a class that contains a `signal` method. This method should receive as many
parameters that you need to send the signal:

```php
<?php

namespace App\Heartbeats;

class CustomChannel
{
    /**
     * Send a heartbeat signal.
     *
     * @param string $someOption
     * @param array $moreOptions
     * @return void
     */
    public function signal($someOption, array $moreOptions = [])
    {
        // Send the signal according to the specified options.
    }
}
```

Once your channel class has been defined, you may extend `Heartbeat`'s driver to add yours. This could be added to
a service provider in the `boot` method:

```php
<?php

namespace App\Providers;

use App\Heartbeats\CustomChannel;
use Heartbeat;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        Heartbeat::extend('custom', function($app) {
            return $app->make(CustomChannel::class);
        });
    }
}
```

Finally, you can now use your driver like native one:

```php
Heartbeat::custom('someOption', ['more' => 'options']);
```

## Testing

To run the phpUnit tests, please use:

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE OF CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email security@exolnet.com instead of using the issue tracker.

## Credits

- [Alexandre D'Eschambeault](https://github.com/xel1045)
- [Simon Gaudreau](https://github.com/Gandhi11)
- [Pat Gagnon-Renaud](https://github.com/pgrenaud)
- [All Contributors](../../contributors)

## License

This code is licensed under the [MIT license](http://choosealicense.com/licenses/mit/). 
Please see the [license file](LICENSE) for more information.
