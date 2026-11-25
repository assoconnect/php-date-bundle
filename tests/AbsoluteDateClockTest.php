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

    public function testNowWithoutDateTimeZone(): void
    {
        self::assertEquals(
            '2025-03-12',
            AbsoluteDateClock::now()
        );
    }

    public function testRelative(): void
    {
        self::assertEquals(
            '2025-03-11',
            AbsoluteDateClock::relative('-1day', new \DateTimeZone('Europe/Paris'))
        );
    }

    /**
     * @return iterable<string, array{string, string, ?string, string}>
     */
    public static function provideRelativeIsResolvedOnTheCalendarOfTheTimezone(): iterable
    {
        yield 'today, Paris on the next day' => ['2026-11-24 23:30:00', 'today', 'Europe/Paris', '2026-11-25'];
        yield 'yesterday, Paris on the next day' => ['2026-11-24 23:30:00', 'yesterday', 'Europe/Paris', '2026-11-24'];
        yield 'midnight, Paris on the next day' => ['2026-11-24 23:30:00', 'midnight', 'Europe/Paris', '2026-11-25'];
        yield 'now, Paris on the next day' => ['2026-11-24 23:30:00', 'now', 'Europe/Paris', '2026-11-25'];
        yield 'today, UTC+14' => ['2026-11-24 12:00:00', 'today', 'Pacific/Kiritimati', '2026-11-25'];
        yield 'absolute date, UTC-12' => ['2026-11-24 10:00:00', '2026-01-15', 'Etc/GMT+12', '2026-01-15'];
        yield 'days across a DST change, Paris' => ['2026-11-24 22:22:00', '-31 days', 'Europe/Paris', '2026-10-24'];
        yield 'today, no timezone means UTC' => ['2026-11-24 23:30:00', 'today', null, '2026-11-24'];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideRelativeIsResolvedOnTheCalendarOfTheTimezone')]
    public function testRelativeIsResolvedOnTheCalendarOfTheTimezone(
        string $utcInstant,
        string $relative,
        ?string $timezone,
        string $expected,
    ): void {
        self::mockTime(new \DateTimeImmutable($utcInstant, new \DateTimeZone('UTC')));

        self::assertSame(
            $expected,
            (string) AbsoluteDateClock::relative($relative, null === $timezone ? null : new \DateTimeZone($timezone))
        );
    }
}
