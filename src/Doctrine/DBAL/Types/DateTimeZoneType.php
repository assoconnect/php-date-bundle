<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class DateTimeZoneType extends Type
{
    public const NAME = 'datetimezone';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        $fieldDeclaration['length'] = 30;
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return $value;
        }

        if ($value instanceof \DateTimeZone) {
            return $value->getName();
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'DateTimeZone']);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?\DateTimeZone
    {
        if ($value === null || $value instanceof \DateTimeZone) {
            return $value;
        }

        return new \DateTimeZone($value);
    }
}
