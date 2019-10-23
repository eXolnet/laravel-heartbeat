<?php

namespace Exolnet\Heartbeat;

use Exolnet\Heartbeat\Jobs\HeartbeatJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class HeartbeatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->publishes([
            $this->getConfigFile() => config_path('heartbeat.php'),
        ], 'config');

        $this->scheduleQueueCheck();
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->getConfigFile(), 'heartbeat');

        $this->registerHeartbeat();
        $this->registerCommands();
    }

    /**
     * @return void
     */
    protected function scheduleQueueCheck(): void
    {
        $preset = config('heartbeat.job_schedule.preset');
        $cron   = config('heartbeat.job_schedule.cron');

        if (! $preset || ! $cron) {
            return;
        }

        $this->app->booted(function () use ($preset, $cron) {
            /** @var \Illuminate\Console\Scheduling\Schedule $schedule */
            $schedule = $this->app->make(Schedule::class);

            $schedule->job(new HeartbeatJob('preset', $preset))->cron($cron);
        });
    }

    /**
     * @return void
     */
    protected function registerHeartbeat(): void
    {
        $this->app->singleton('heartbeat', function () {
            return new HeartbeatManager($this->app);
        });
    }

    /**
     * @return void
     */
    protected function registerCommands(): void
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
    protected function getConfigFile(): string
    {
        return __DIR__ .
            DIRECTORY_SEPARATOR . '..' .
            DIRECTORY_SEPARATOR . 'config' .
            DIRECTORY_SEPARATOR . 'heartbeat.php';
    }
}
