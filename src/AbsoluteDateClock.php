<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle;

use AssoConnect\PHPDate\AbsoluteDate;
use Symfony\Component\Clock\DatePoint;

class AbsoluteDateClock
{
    public static function now(\DateTimeZone $timeZone): AbsoluteDate
    {
        return self::relative('now', $timeZone);
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function relative(string $relative = 'now', \DateTimeZone $timezone = null): AbsoluteDate
    {
        return AbsoluteDate::createInTimezone($timezone ?? new \DateTimeZone('UTC'), new DatePoint($relative));
    }
}
