<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Tests\Validator\ConstraintsSetFactory\Field;

use AssoConnect\PHPDateBundle\Doctrine\DBAL\Types\DateTimeZoneType;
use AssoConnect\PHPDateBundle\Validator\ConstraintsSetFactory\Field\DateTimeZoneProvider;
use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use DateTimeZone;
use Symfony\Component\Validator\Constraints\Type;

class DateTimeZoneProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new DateTimeZoneProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [
            ['type' => DateTimeZoneType::NAME],
            [new Type(DateTimeZone::class)],
        ];
    }
}
