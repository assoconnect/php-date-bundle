<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\ThirdParty\Symfony\HttpKernel\Controller\ArgumentResolver;

use AssoConnect\PHPDate\AbsoluteDate;
use AssoConnect\PHPDate\Exception\ParsingException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AbsoluteDateValueResolver implements ValueResolverInterface
{
    /**
     * @return array<AbsoluteDate>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();
        if (AbsoluteDate::class !== $argumentType) {
            return [];
        }

        // get the value from the request, based on the argument name
        $value = $request->attributes->get($argument->getName());
        if (!is_string($value)) {
            return [];
        }

        try {
            $date = new AbsoluteDate($value);
        } catch (ParsingException $e) {
            throw new BadRequestHttpException(
                sprintf(
                    'Invalid date given for parameter "%s" (expected format: %s).',
                    $value,
                    AbsoluteDate::DEFAULT_DATE_FORMAT
                ),
                $e
            );
        }

        return [$date];
    }
}
