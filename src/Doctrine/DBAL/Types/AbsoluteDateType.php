<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Doctrine\DBAL\Types;

use AssoConnect\PHPDate\AbsoluteDate;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateType;

class AbsoluteDateType extends DateType
{
    public const TYPE = 'date_absolute';

    public function getName()
    {
        return self::TYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return $value;
        }

        if ($value instanceof AbsoluteDate) {
            return $value->format($platform->getDateFormatString());
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', AbsoluteDate::class]);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?AbsoluteDate
    {
        if ($value === null || $value instanceof AbsoluteDate) {
            return $value;
        }

        try {
            return new AbsoluteDate($value, $platform->getDateFormatString());
        } catch (\Exception $exception) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateFormatString(),
                $exception
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
