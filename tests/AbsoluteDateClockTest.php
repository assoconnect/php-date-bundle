<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Tests;

use AssoConnect\PHPDateBundle\AbsoluteDateClock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\Test\ClockSensitiveTrait;

class AbsoluteDateClockTest extends TestCase
{
    use ClockSensitiveTrait;

    protected function setUp(): void
    {
        self::mockTime('2025-03-12 10:11:12');
    }

    public function testNow(): void
    {
        self::assertEquals(
            '2025-03-12',
            AbsoluteDateClock::now(new \DateTimeZone('Europe/Paris'))
        );
    }

    public function testRelative(): void
    {
        self::assertEquals(
            '2025-03-11',
            AbsoluteDateClock::relative('-1day', new \DateTimeZone('Europe/Paris'))
        );
    }
}
