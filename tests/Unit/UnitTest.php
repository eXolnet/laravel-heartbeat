<?php

namespace Exolnet\Heartbeat\Tests\Unit;

use Illuminate\Support\Carbon;
use Mockery;
use PHPUnit\Framework\TestCase;

abstract class UnitTest extends TestCase
{
    public function tearDown()
    {
        if (class_exists('Mockery')) {
            if ($container = Mockery::getContainer()) {
                $this->addToAssertionCount($container->mockery_getExpectationCount());
            }

            Mockery::close();
        }

        if (class_exists(Carbon::class)) {
            Carbon::setTestNow();
        }
    }
}
