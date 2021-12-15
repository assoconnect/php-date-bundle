<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Translatable;

use AssoConnect\PHPDate\AbsoluteDate;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AbsoluteDateTranslatable implements TranslatableInterface
{
    private AbsoluteDate $absoluteDate;
    private int $dateType;
    private string $pattern;
    private \DateTimeZone $timezone;

    /** Cache */
    private static array $formatters = [];

    public function __construct(
        AbsoluteDate $absoluteDate,
        int $dateType = \IntlDateFormatter::SHORT,
        string $pattern = ''
    ) {
        $this->absoluteDate = $absoluteDate;
        $this->dateType = $dateType;
        $this->pattern = $pattern;
        $this->timezone = new \DateTimeZone('UTC');
    }

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        if (!$locale) {
            $locale = $translator->getLocale();
        }

        $key = implode('.', [$locale, $this->dateType, $this->pattern]);
        if (!isset(self::$formatters[$key])) {
            self::$formatters[$key] = new \IntlDateFormatter(
                $locale,
                $this->dateType,
                \IntlDateFormatter::NONE,
                $this->timezone,
                null,
                $this->pattern
            );
        }

        return self::$formatters[$key]->format($this->absoluteDate->startsAt($this->timezone));
    }
}
