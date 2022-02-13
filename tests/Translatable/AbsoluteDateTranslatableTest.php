<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Tests\Translatable;

use AssoConnect\PHPDate\AbsoluteDate;
use AssoConnect\PHPDateBundle\Translatable\AbsoluteDateTranslatable;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class AbsoluteDateTranslatableTest extends TestCase
{
    public function testTransWorks(): void
    {
        $translator = $this->createMock(TranslatorInterface::class);

        $date = new AbsoluteDate('1987-09-07');

        $translatable = new AbsoluteDateTranslatable($date, \IntlDateFormatter::SHORT);
        self::assertSame('07/09/1987', $translatable->trans($translator, 'fr_FR'));
        self::assertSame('09/07/1987', $translatable->trans($translator, 'en_US'));
    }
}
