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
        $jobSchedules = $this->getJobSchedules();

        if (! $jobSchedules) {
            return;
        }

        $this->app->booted(function () use ($jobSchedules) {
            /** @var \Illuminate\Console\Scheduling\Schedule $schedule */
            $schedule = $this->app->make(Schedule::class);

            foreach ($jobSchedules as $jobSchedule) {
                $schedule->job(
                    new HeartbeatJob('preset', $jobSchedule['preset']),
                    $jobSchedule['queue'] ?? null,
                    $jobSchedule['connection'] ?? null
                )->cron($jobSchedule['cron']);
            }
        });
    }

    /**
     * Get the valid configured job schedules. Both a single job schedule
     * (legacy format) and a list of job schedules are supported.
     *
     * @return list<array{preset: string, cron: string, connection?: string|null, queue?: string|null}>
     */
    protected function getJobSchedules(): array
    {
        $config = config('heartbeat.job_schedule');

        if (! is_array($config)) {
            return [];
        }

        if (array_is_list($config)) {
            return array_values(array_filter($config, [$this, 'isValidJobSchedule']));
        }

        return $this->isValidJobSchedule($config) ? [$config] : [];
    }

    /**
     * @param mixed $jobSchedule
     * @return bool
     */
    protected function isValidJobSchedule($jobSchedule): bool
    {
        return is_array($jobSchedule) && ! empty($jobSchedule['preset']) && ! empty($jobSchedule['cron']);
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
