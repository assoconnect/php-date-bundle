<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Exception;

use AssoConnect\PHPDate\AbsoluteDate;
use LogicException;

class DateFormattingException extends LogicException
{
    public const CANNOT_GET_PATTERN_MESSAGE = 'Cannot get pattern for date %s';
    public const CANNOT_FORMAT_DATE_MESSAGE = 'Cannot format date %s';

    public static function cannotGetPattern(AbsoluteDate $absoluteDate): self
    {
        return new self(
            sprintf(self::CANNOT_GET_PATTERN_MESSAGE, $absoluteDate->__toString())
        );
    }

    public static function cannotFormatDate(AbsoluteDate $absoluteDate): self
    {
        return new self(
            sprintf(self::CANNOT_FORMAT_DATE_MESSAGE, $absoluteDate->__toString())
        );
    }
}
