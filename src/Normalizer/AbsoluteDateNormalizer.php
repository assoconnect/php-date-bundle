<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Normalizer;

use AssoConnect\PHPDate\AbsoluteDate;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Normalizes an instance of {@see AbsoluteDate} to a date string.
 * Denormalizes a date string to an instance of {@see AbsoluteDate}.
 */
class AbsoluteDateNormalizer implements NormalizerInterface, DenormalizerInterface, CacheableSupportsMethodInterface
{
    public const FORMAT_KEY = 'datetime_format';

    private $defaultContext = [
        self::FORMAT_KEY => AbsoluteDate::DEFAULT_DATE_FORMAT,
    ];

    private static $supportedTypes = [
        AbsoluteDate::class => true,
    ];

    public function __construct(array $defaultContext = [])
    {
        $this->defaultContext = array_merge($this->defaultContext, $defaultContext);
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        if (!$object instanceof AbsoluteDate) {
            throw new InvalidArgumentException(sprintf(
                'The object must be an instance of "%s".',
                AbsoluteDate::class
            ));
        }

        $dateTimeFormat = $context[self::FORMAT_KEY] ?? $this->defaultContext[self::FORMAT_KEY];

        return $object->format($dateTimeFormat);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof AbsoluteDate;
    }

    /**
     * {@inheritdoc}
     *
     * @throws NotNormalizableValueException
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $format = $context[self::FORMAT_KEY] ?? null;

        if ('' === $data || null === $data) {
            return;
        }

        try {
            return new AbsoluteDate($data, $format);
        } catch (\Exception $e) {
            throw new NotNormalizableValueException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return isset(self::$supportedTypes[$type]);
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return __CLASS__ === \get_class($this);
    }
}
