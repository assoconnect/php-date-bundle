<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Tests\Doctrine\DBAL\Types;

use AssoConnect\PHPDate\AbsoluteDate;
use AssoConnect\PHPDateBundle\Doctrine\DBAL\Types\AbsoluteDateType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function date_default_timezone_set;

class DateTest extends TestCase
{
    /** @var AbstractPlatform|MockObject */
    protected AbstractPlatform $platform;

    protected Type $type;

    protected function setUp(): void
    {
        $this->type = new AbsoluteDateType();
        $this->platform = $this->getMockForAbstractClass(AbstractPlatform::class);
    }

    protected function tearDown(): void
    {
        date_default_timezone_set('UTC');
    }

    /**
     * @param mixed $value
     *
     * @dataProvider invalidPHPValuesProvider
     */
    public function testInvalidTypeConversionToDatabaseValue($value): void
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToDatabaseValue($value, $this->platform);
    }

    /**
     * @return iterable<mixed>
     */
    public static function invalidPHPValuesProvider(): iterable
    {
        yield [0];
        yield [''];
        yield ['foo'];
        yield ['10:11:12'];
        yield ['2015-01-31'];
        yield ['2015-01-31 10:11:12'];
        yield [new \stdClass()];
        yield [27];
        yield [-1];
        yield [1.2];
        yield [[]];
        yield [['an array']];
    }

    public function testNullConversion(): void
    {
        self::assertNull($this->type->convertToPHPValue(null, $this->platform));
    }

    public function testDateConvertsToDatabaseValue(): void
    {
        $date = new AbsoluteDate('1970-01-01');

        self::assertIsString($this->type->convertToDatabaseValue($date, $this->platform));
    }

    public function testConvertDateToPHPValue(): void
    {
        $date = new AbsoluteDate('1970-01-01');

        self::assertSame($date, $this->type->convertToPHPValue($date, $this->platform));
    }

    public function testDateConvertsToPHPValue(): void
    {
        // Birthday of jwaget and also birthday of Doctrine. Send him a present ;)
        self::assertInstanceOf(
            AbsoluteDate::class,
            $this->type->convertToPHPValue('1985-09-01', $this->platform)
        );
    }

    public function testDateResetsNonDatePartsToZeroUnixTimeValues(): void
    {
        $date = $this->type->convertToPHPValue('1985-09-01', $this->platform);

        self::assertEquals('00:00:00', $date->format('H:i:s'));
    }

    public function testDateResetsSummerTimeAffection(): void
    {
        date_default_timezone_set('Europe/Berlin');

        $date = $this->type->convertToPHPValue('2009-08-01', $this->platform);
        self::assertEquals('00:00:00', $date->format('H:i:s'));
        self::assertEquals('2009-08-01', $date->format('Y-m-d'));

        $date = $this->type->convertToPHPValue('2009-11-01', $this->platform);
        self::assertEquals('00:00:00', $date->format('H:i:s'));
        self::assertEquals('2009-11-01', $date->format('Y-m-d'));
    }

    public function testInvalidDateFormatConversion(): void
    {
        $this->expectException(ConversionException::class);
        $this->type->convertToPHPValue('abcdefg', $this->platform);
    }
}
