<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Tests\Validator\ConstraintsSetFactory\Field;

use AssoConnect\PHPDate\AbsoluteDate;
use AssoConnect\PHPDateBundle\Doctrine\DBAL\Types\AbsoluteDateType;
use AssoConnect\PHPDateBundle\Validator\ConstraintsSetFactory\Field\AbsoluteDateProvider;
use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use Symfony\Component\Validator\Constraints\Type;

class AbsoluteDateProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new AbsoluteDateProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [
            ['type' => AbsoluteDateType::NAME],
            [new Type(AbsoluteDate::class)],
        ];
    }
}
