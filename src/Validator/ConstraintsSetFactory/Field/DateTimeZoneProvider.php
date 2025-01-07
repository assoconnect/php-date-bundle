<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Validator\ConstraintsSetFactory\Field;

use AssoConnect\PHPDateBundle\Doctrine\DBAL\Types\DateTimeZoneType;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use DateTimeZone;
use Doctrine\ORM\Mapping\FieldMapping;
use Symfony\Component\Validator\Constraints\Type;

class DateTimeZoneProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return DateTimeZoneType::NAME === $type;
    }

    public function getConstraints(FieldMapping $fieldMapping): array
    {
        return [
            new Type(DateTimeZone::class),
        ];
    }
}
