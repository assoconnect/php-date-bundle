<?php

declare(strict_types=1);

namespace App\Tests\Twig;

use App\Twig\AbsoluteDateExtension;
use AssoConnect\PHPDate\AbsoluteDate;
use AssoConnect\PHPDateBundle\Translatable\AbsoluteDateTranslatable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class AbsoluteDateExtensionTest extends KernelTestCase
{

    /** @group functional */
    public function testFilterUserDefaultLocal()
    {
        self::bootKernel();
        $translator = self::getContainer()->get(TranslatorInterface::class);
        $translator->setLocale('fr_FR');

        $absoluteDate = new AbsoluteDate('2023-03-24');
        $translatable = new AbsoluteDateTranslatable($absoluteDate);
        $extension = new AbsoluteDateExtension($translator);
        $result = $extension->formatAbsoluteDate($absoluteDate);

        self::assertSame($translatable->trans($translator, 'fr_FR'), $result);
    }

}
