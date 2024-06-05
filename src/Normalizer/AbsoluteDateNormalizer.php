<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Normalizer;

use AssoConnect\PHPDate\AbsoluteDate;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

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
    public function normalize($object, string $format = null, array $context = []): string
    {
        if (!$object instanceof AbsoluteDate) {
            throw new InvalidArgumentException(sprintf(
                'The object must be an instance of "%s".',
                AbsoluteDate::class
            ));
        }

        $dateTimeFormat = $context[self::FORMAT_KEY] ?? AbsoluteDate::DEFAULT_DATE_FORMAT;

        return $object->format($dateTimeFormat);
    }

    /**
     * {@inheritdoc}
     * @param mixed[] $context
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof AbsoluteDate;
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed[] $context
     * @throws NotNormalizableValueException
     */
    public function denormalize($data, string $type, string $format = null, array $context = []): ?AbsoluteDate
    {
        $dateTimeFormat = $context[self::FORMAT_KEY] ?? AbsoluteDate::DEFAULT_DATE_FORMAT;

        try {
            return '' === $data || null === $data ? null : new AbsoluteDate($data, $dateTimeFormat);
        } catch (\Exception $e) {
            throw new NotNormalizableValueException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     * @param mixed[] $content
     */
    public function supportsDenormalization(
        mixed $data,
        string $type,
        ?string $format = null,
        array $context = []
    ): bool {
        return AbsoluteDate::class === $type;
    }

    /**
     * @return non-empty-array<class-string<AssoConnect\PHPDateBundle\Normalizer\AbsoluteDateNormalizer>, true>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [get_class($this) => true];
    }
}
