<?php

namespace Exolnet\Heartbeat\Tests\Integration;

use Exolnet\Heartbeat\Jobs\HeartbeatJob;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Queue;

class JobScheduleTest extends TestCase
{
    /**
     * @var mixed
     */
    protected $jobSchedule;

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        if ($this->jobSchedule !== null) {
            $app['config']->set('heartbeat.job_schedule', $this->jobSchedule);
        }
    }

    /**
     * @param mixed $jobSchedule
     * @return void
     */
    protected function bootWithJobSchedule($jobSchedule): void
    {
        $this->jobSchedule = $jobSchedule;

        $this->refreshApplication();
    }

    /**
     * @return \Illuminate\Console\Scheduling\Event[]
     */
    protected function scheduledEvents(): array
    {
        return $this->app->make(Schedule::class)->events();
    }

    /**
     * @param \Illuminate\Console\Scheduling\Event $event
     * @return void
     */
    protected function runEvent(Event $event): void
    {
        Queue::fake();

        $event->run($this->app);
    }

    /**
     * @return void
     */
    public function testDefaultConfigurationSchedulesOneJobOnDefaultQueue()
    {
        $events = $this->scheduledEvents();

        $this->assertCount(1, $events);
        $this->assertSame('*/15 * * * *', $events[0]->expression);

        $this->runEvent($events[0]);

        Queue::assertPushed(HeartbeatJob::class, function (HeartbeatJob $job) {
            return $job->channel === 'preset'
                && $job->options === ['disk']
                && $job->connection === null
                && $job->queue === null;
        });
    }

    /**
     * @return void
     */
    public function testLegacyFormatWithoutConnectionAndQueue()
    {
        $this->bootWithJobSchedule([
            'preset' => 'file',
            'cron' => '0 * * * *',
        ]);

        $events = $this->scheduledEvents();

        $this->assertCount(1, $events);
        $this->assertSame('0 * * * *', $events[0]->expression);

        $this->runEvent($events[0]);

        Queue::assertPushed(HeartbeatJob::class, function (HeartbeatJob $job) {
            return $job->options === ['file'] && $job->connection === null && $job->queue === null;
        });
    }

    /**
     * @return void
     */
    public function testLegacyFormatWithConnectionAndQueue()
    {
        $this->bootWithJobSchedule([
            'preset' => 'disk',
            'cron' => '*/15 * * * *',
            'connection' => 'redis',
            'queue' => 'high',
        ]);

        $events = $this->scheduledEvents();

        $this->assertCount(1, $events);

        $this->runEvent($events[0]);

        Queue::assertPushed(HeartbeatJob::class, function (HeartbeatJob $job) {
            return $job->connection === 'redis' && $job->queue === 'high';
        });
    }

    /**
     * @return void
     */
    public function testListFormatSchedulesMultipleJobs()
    {
        $this->bootWithJobSchedule([
            ['preset' => 'disk', 'cron' => '*/15 * * * *'],
            ['preset' => 'http', 'cron' => '*/5 * * * *', 'connection' => 'sqs', 'queue' => 'low'],
        ]);

        $events = $this->scheduledEvents();

        $this->assertCount(2, $events);
        $this->assertSame('*/15 * * * *', $events[0]->expression);
        $this->assertSame('*/5 * * * *', $events[1]->expression);

        $this->runEvent($events[0]);

        Queue::assertPushed(HeartbeatJob::class, function (HeartbeatJob $job) {
            return $job->options === ['disk'] && $job->connection === null && $job->queue === null;
        });

        $this->runEvent($events[1]);

        Queue::assertPushed(HeartbeatJob::class, function (HeartbeatJob $job) {
            return $job->options === ['http'] && $job->connection === 'sqs' && $job->queue === 'low';
        });
    }

    /**
     * @return void
     */
    public function testIncompleteEntriesAreIgnored()
    {
        $this->bootWithJobSchedule([
            ['preset' => null, 'cron' => '*/15 * * * *'],
            ['preset' => 'disk', 'cron' => null],
            ['preset' => 'file', 'cron' => '0 * * * *'],
        ]);

        $events = $this->scheduledEvents();

        $this->assertCount(1, $events);
        $this->assertSame('0 * * * *', $events[0]->expression);
    }

    /**
     * @return void
     */
    public function testLegacyFormatWithoutPresetSchedulesNothing()
    {
        $this->bootWithJobSchedule([
            'preset' => null,
            'cron' => '*/15 * * * *',
        ]);

        $this->assertCount(0, $this->scheduledEvents());
    }

    /**
     * @return void
     */
    public function testEmptyConfigurationSchedulesNothing()
    {
        $this->bootWithJobSchedule([]);

        $this->assertCount(0, $this->scheduledEvents());
    }
}
