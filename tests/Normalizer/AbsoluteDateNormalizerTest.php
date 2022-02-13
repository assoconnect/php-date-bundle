<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Tests\Normalizer;

use AssoConnect\PHPDate\AbsoluteDate;
use AssoConnect\PHPDateBundle\Normalizer\AbsoluteDateNormalizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

class AbsoluteDateNormalizerTest extends TestCase
{
    private AbsoluteDateNormalizer $normalizer;

    protected function setUp(): void
    {
        $this->normalizer = new AbsoluteDateNormalizer();
    }

    public function testSupportsNormalization(): void
    {
        self::assertTrue($this->normalizer->supportsNormalization(new AbsoluteDate('1970-01-01')));
    }

    public function testNormalize(): void
    {
        self::assertEquals('2016-01-01', $this->normalizer->normalize(new AbsoluteDate('2016-01-01')));
    }

    public function testNormalizeUsingFormatPassedInContext(): void
    {
        $normalizedValue = $this->normalizer->normalize(
            new AbsoluteDate('2016-01-01'),
            null,
            [AbsoluteDateNormalizer::FORMAT_KEY => 'Y']
        );
        self::assertEquals('2016', $normalizedValue);
    }

    public function testNormalizeInvalidObjectThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('The object must be an instance of "%s".', AbsoluteDate::class));
        $this->normalizer->normalize(new \stdClass());
    }

    public function testSupportsDenormalization(): void
    {
        self::assertTrue($this->normalizer->supportsDenormalization('2016-01-01', AbsoluteDate::class));
        self::assertFalse($this->normalizer->supportsDenormalization('foo', 'Bar'));
    }

    public function testDenormalize(): void
    {
        self::assertEquals(
            new AbsoluteDate('2016-01-01'),
            $this->normalizer->denormalize('2016-01-01', AbsoluteDate::class)
        );
    }

    public function testDenormalizeUsingFormatPassedInContext(): void
    {
        self::assertEquals(
            new AbsoluteDate('2016-01-01'),
            $this->normalizer->denormalize('2016.01.01', AbsoluteDate::class, null, [
                AbsoluteDateNormalizer::FORMAT_KEY => 'Y.m.d|'
            ])
        );
    }

    public function testDenormalizeInvalidDataThrowsException(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->normalizer->denormalize('invalid date', AbsoluteDate::class);
    }

    public function testDenormalizeFormatMismatchThrowsException(): void
    {
        $this->expectException(NotNormalizableValueException::class);
        $this->normalizer->denormalize('2016/01/01', AbsoluteDate::class, null, [
            AbsoluteDateNormalizer::FORMAT_KEY => 'Y-m-d|'
        ]);
    }
}
