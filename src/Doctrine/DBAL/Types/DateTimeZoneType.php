<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Type;

class DateTimeZoneType extends Type
{
    public const NAME = 'datetimezone';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        $fieldDeclaration['length'] = 30;
        return $platform->getStringTypeDeclarationSQL($fieldDeclaration);
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

        throw $this->createInvalidTypeException($value);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?\DateTimeZone
    {
        if ($value === null || $value instanceof \DateTimeZone) {
            return $value;
        }

        return new \DateTimeZone($value);
    }

    /**
     * DBAL 4 replaced the ConversionException static factories with dedicated exception classes.
     * The runtime conditional below can be inlined once DBAL 3 support is dropped.
     * Excluded from coverage: only one branch can run for a given installed DBAL major.
     *
     * @codeCoverageIgnore
     */
    private function createInvalidTypeException(mixed $value): ConversionException
    {
        if (class_exists(InvalidType::class)) {
            return InvalidType::new($value, self::NAME, ['null', 'DateTimeZone']);
        }

        return ConversionException::conversionFailedInvalidType($value, self::NAME, ['null', 'DateTimeZone']);
    }
}
