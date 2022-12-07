<?php

declare(strict_types=1);

namespace App\Twig;

use AssoConnect\PHPDate\AbsoluteDate;
use AssoConnect\PHPDateBundle\Translatable\AbsoluteDateTranslatable;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AbsoluteDateExtension extends AbstractExtension
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('date', [$this, 'formatAbsoluteDate']),
        ];
    }

    public function formatAbsoluteDate(AbsoluteDate $date, string $locale = null): string
    {
        return (new AbsoluteDateTranslatable($date))->trans($this->translator, $locale);
    }
}
