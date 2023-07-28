<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Translatable;

use AssoConnect\PHPDate\AbsoluteDate;
use DateTimeZone;
use IntlDateFormatter;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AbsoluteDateTranslatable implements TranslatableInterface
{
    private AbsoluteDate $absoluteDate;
    private int $dateType;
    private string $pattern;
    private DateTimeZone $timezone;

    /**
     * Cache
     * @var IntlDateFormatter[]
     */
    private static array $formatters = [];

    public function __construct(
        AbsoluteDate $absoluteDate,
        int $dateType = IntlDateFormatter::SHORT,
        string $pattern = ''
    ) {
        $this->absoluteDate = $absoluteDate;
        $this->dateType = $dateType;
        $this->pattern = $pattern;
        $this->timezone = new DateTimeZone('UTC');
    }

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        if (null === $locale) {
            $locale = $translator->getLocale();
        }

        $key = implode('.', [$locale, $this->dateType, $this->pattern]);
        if (!isset(self::$formatters[$key])) {
            self::$formatters[$key] = new IntlDateFormatter(
                $locale,
                $this->dateType,
                IntlDateFormatter::NONE,
                $this->timezone,
                null,
                $this->pattern
            );

            if (false !== strpos($locale, '_US')) {
                //A more used format
                $pattern = self::$formatters[$key]->getPattern();
                $pattern = str_replace(['yy', 'M', 'd'], ['y', 'MM', 'dd'], $pattern);
                self::$formatters[$key]->setPattern($pattern);
            }
        }

        $formatted = self::$formatters[$key]->format($this->absoluteDate->startsAt($this->timezone));

        if (false === $formatted) {
            throw new \RuntimeException(sprintf('Cannot format %s', $this->absoluteDate->__toString()));
        }

        return $formatted;
    }
}
