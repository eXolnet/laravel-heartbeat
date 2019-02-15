<?php

namespace Exolnet\Heartbeat;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class HeartbeatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            $this->getConfigFile() => config_path('heartbeat.php'),
        ], 'config');

        $this->scheduleQueueCheck();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom($this->getConfigFile(), 'heartbeat');

        $this->registerHeartbeat();
        $this->registerCommands();
    }

    /**
     * @return void
     */
    protected function scheduleQueueCheck()
    {
        $preset = config('heartbeat.job_schedule.preset');
        $cron   = config('heartbeat.job_schedule.cron');

        if (! $preset || ! $cron) {
            return;
        }

        $this->app->booted(function () use ($preset, $cron) {
            /** @var \Illuminate\Console\Scheduling\Schedule $schedule */
            $schedule = $this->app->make(Schedule::class);

            $schedule->command('heartbeat', ['--queue', $preset])->cron($cron);
        });
    }

    /**
     * @return void
     */
    protected function registerHeartbeat()
    {
        $this->app->singleton('heartbeat', function () {
            return new HeartbeatManager($this->app);
        });
    }

    /**
     * @return void
     */
    protected function registerCommands()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }
        $this->commands([
            Console\HeartbeatCommand::class,
        ]);
    }

    /**
     * @return string
     */
    protected function getConfigFile()
    {
        return __DIR__ .
            DIRECTORY_SEPARATOR . '..' .
            DIRECTORY_SEPARATOR . 'config' .
            DIRECTORY_SEPARATOR . 'heartbeat.php';
    }
}
