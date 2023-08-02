<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Validator\ConstraintsSetFactory\Field;

use AssoConnect\PHPDate\AbsoluteDate;
use AssoConnect\PHPDateBundle\Doctrine\DBAL\Types\AbsoluteDateType;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use Symfony\Component\Validator\Constraints\Type;

class AbsoluteDateProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return AbsoluteDateType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new Type(AbsoluteDate::class),
        ];
    }
}
