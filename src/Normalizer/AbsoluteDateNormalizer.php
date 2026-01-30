<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Normalizer;

use AssoConnect\PHPDate\AbsoluteDate;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Throwable;

/**
 * Normalizes an instance of {@see AbsoluteDate} to a date string.
 * Denormalizes a date string to an instance of {@see AbsoluteDate}.
 */
class AbsoluteDateNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public const FORMAT_KEY = 'datetime_format';

    /**
     * {@inheritdoc}
     *
     * @param mixed[] $context
     * @throws InvalidArgumentException
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): string
    {
        if (!$data instanceof AbsoluteDate) {
            throw new InvalidArgumentException(sprintf(
                'The object must be an instance of "%s".',
                AbsoluteDate::class
            ));
        }

        $dateTimeFormat = $context[self::FORMAT_KEY] ?? AbsoluteDate::DEFAULT_DATE_FORMAT;

        return $data->format($dateTimeFormat);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof AbsoluteDate;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, string $type, ?string $format = null, array $context = []): AbsoluteDate
    {
        if ('' === $data || null === $data) {
            throw new UnexpectedValueException();
        }

        $dateTimeFormat = $context[self::FORMAT_KEY] ?? AbsoluteDate::DEFAULT_DATE_FORMAT;

        try {
            return new AbsoluteDate($data, $dateTimeFormat);
        } catch (Throwable $e) {
            throw new UnexpectedValueException(previous: $e);
        }
    }

    /**
     * {@inheritdoc}
     * @param mixed[] $context
     */
    public function supportsDenormalization(
        mixed $data,
        string $type,
        ?string $format = null,
        array $context = []
    ): bool {
        return AbsoluteDate::class === $type;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [AbsoluteDate::class => true];
    }
}
