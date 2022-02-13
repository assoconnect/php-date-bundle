<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Tests\Doctrine\DBAL\Types;

use AssoConnect\PHPDateBundle\Doctrine\DBAL\Types\DateTimeZoneType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\TestCase;

class DateTimeZoneTypeTest extends TestCase
{
    public function testConversionWorks(): void
    {
        $type = new DateTimeZoneType();
        $platform = $this->getMockForAbstractClass(AbstractPlatform::class);

        // With null
        self::assertNull($type->convertToDatabaseValue(null, $platform));
        self::assertNull($type->convertToPHPValue(null, $platform));

        // With a timezone
        $name = 'Europe/Paris';
        $timezone = new \DateTimeZone($name);
        self::assertSame($name, $type->convertToDatabaseValue($timezone, $platform));
        $phpValue = $type->convertToPHPValue($name, $platform);
        self::assertInstanceOf(\DateTimeZone::class, $phpValue);
        self::assertSame($name, $phpValue->getName());
    }

    public function testConversionToDatabaseThrows(): void
    {
        $type = new DateTimeZoneType();
        $platform = $this->getMockForAbstractClass(AbstractPlatform::class);

        $this->expectException(ConversionException::class);
        $type->convertToDatabaseValue('hello', $platform);
    }
}
