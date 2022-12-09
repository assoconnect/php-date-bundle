<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Tests\Twig\Extension;

use AssoConnect\PHPDateBundle\Twig\Extension\AbsoluteDateExtension;
use AssoConnect\PHPDate\AbsoluteDate;
use AssoConnect\PHPDateBundle\Translatable\AbsoluteDateTranslatable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\Translator;

class AbsoluteDateExtensionTest extends TestCase
{
    public function testFilterUserDefaultLocal(): void
    {
        $translator = $this->createMock(Translator::class);
        $translator->expects(self::once())
            ->method('getLocale')
            ->willReturn('fr_FR');

        $absoluteDate = new AbsoluteDate('2023-03-24');
        $translatable = new AbsoluteDateTranslatable($absoluteDate);
        $extension = new AbsoluteDateExtension($translator);
        $result = $extension->formatAbsoluteDate($absoluteDate);

        self::assertSame($translatable->trans($translator, 'fr_FR'), $result);
    }

    public function testFilterName(): void
    {
        $extension = new AbsoluteDateExtension($this->createMock(Translator::class));
        $filters = $extension->getFilters();

        self::assertSame('date', $filters[0]->getName());
    }
}
