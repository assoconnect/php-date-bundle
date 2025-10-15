<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\Tests\ThirdParty\Symfony\HttpKernel\Controller\ArgumentResolver;

use AssoConnect\PHPDate\AbsoluteDate;
use AssoConnect\PHPDateBundle\ThirdParty\Symfony\HttpKernel\Controller\ArgumentResolver\AbsoluteDateValueResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AbsoluteDateValueResolverTest extends TestCase
{
    private AbsoluteDateValueResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = new AbsoluteDateValueResolver();
    }

    public function testResolve(): void
    {
        $request = new Request([], [], ['someArgument' => $date = '2024-06-02']);
        $argument = new ArgumentMetadata('someArgument', AbsoluteDate::class, false, false, null);
        $resolvedArgument = $this->resolver->resolve($request, $argument);

        self::assertCount(1, $resolvedArgument);

        foreach ($resolvedArgument as $arg) {
            self::assertSame($date, $arg->format());
        }
    }

    public function testResolveWithInvalidType(): void
    {
        $request = new Request([], [], ['someArgument' => '2024-06-02']);
        $argument = new ArgumentMetadata('someArgument', \DateTime::class, false, false, null);
        $resolvedArgument = $this->resolver->resolve($request, $argument);

        self::assertSame([], $resolvedArgument);
    }

    public function testResolveWithNonStringValue(): void
    {
        $request = new Request([], [], ['someArgument' => 1]);
        $argument = new ArgumentMetadata('someArgument', AbsoluteDate::class, false, false, null);
        $resolvedArgument = $this->resolver->resolve($request, $argument);

        self::assertSame([], $resolvedArgument);
    }

    public function testResolveWithInvalidValue(): void
    {
        $request = new Request([], [], ['someArgument' => 'somevalue']);
        $argument = new ArgumentMetadata('someArgument', AbsoluteDate::class, false, false, null);

        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Invalid date given for parameter "somevalue"');
        $this->resolver->resolve($request, $argument);
    }
}
