<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Doctrine\DBAL\Types;

use AssoConnect\PHPDate\AbsoluteDate;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Exception\InvalidFormat;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Type;

class AbsoluteDateType extends Type
{
    public const NAME = 'absolute_date';

    private const FORMAT = 'Y-m-d';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getDateTypeDeclarationSQL($column);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return $value;
        }

        if ($value instanceof AbsoluteDate) {
            return $value->format($this->getDateFormatString($platform));
        }

        throw $this->createInvalidTypeException($value);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?AbsoluteDate
    {
        if ($value === null || $value instanceof AbsoluteDate) {
            return $value;
        }

        $format = $this->getDateFormatString($platform);

        try {
            return new AbsoluteDate($value, $format);
        } catch (\Exception $exception) {
            throw $this->createInvalidFormatException($value, $format, $exception);
        }
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    private function getDateFormatString(AbstractPlatform $platform): string
    {
        // getDateFormatString() was removed in DBAL 4, where the date format is always Y-m-d
        return method_exists($platform, 'getDateFormatString') ? $platform->getDateFormatString() : self::FORMAT;
    }

    /**
     * DBAL 4 replaced the ConversionException static factories with dedicated exception classes.
     * The runtime conditionals below can be inlined once DBAL 3 support is dropped.
     * Excluded from coverage: only one branch can run for a given installed DBAL major.
     *
     * @codeCoverageIgnore
     */
    private function createInvalidTypeException(mixed $value): ConversionException
    {
        if (class_exists(InvalidType::class)) {
            return InvalidType::new($value, self::NAME, ['null', AbsoluteDate::class]);
        }

        return ConversionException::conversionFailedInvalidType($value, self::NAME, ['null', AbsoluteDate::class]);
    }

    /**
     * @codeCoverageIgnore
     */
    private function createInvalidFormatException(
        mixed $value,
        string $format,
        \Throwable $previous
    ): ConversionException {
        if (class_exists(InvalidFormat::class)) {
            return InvalidFormat::new($value, self::NAME, $format, $previous);
        }

        return ConversionException::conversionFailedFormat($value, self::NAME, $format, $previous);
    }
}
