<?php

declare(strict_types=1);

namespace Helix\ParamResolver\Tests\Wrapper;

use Helix\Contracts\ParamResolver\Exception\NotResolvableExceptionInterface;
use Helix\Contracts\ParamResolver\MiddlewareInterface;
use Helix\Contracts\ParamResolver\ResolverInterface;
use Helix\ParamResolver\Parameter\NotResolvableInterface;
use Helix\ParamResolver\PlaceholderResolver;
use JetBrains\PhpStorm\Language;
use PHPUnit\Framework\TestCase;

final class TestingValueMiddleware implements MiddlewareInterface
{
    private static int $generatedParameterId = 0;

    /**
     * @var ResolverInterface
     */
    private readonly ResolverInterface $terminal;

    public function __construct(
        private readonly MiddlewareInterface $delegate,
        private readonly TestCase $ctx,
    ) {
        $this->terminal = new PlaceholderResolver();
    }

    /**
     * @param non-empty-string $param
     * @param string $message
     * @return $this
     * @throws NotResolvableExceptionInterface
     */
    public function assertNotSupports(#[Language('PHP')] string $param, string $message = ''): self
    {
        $reflection = $this->createParameter($param);
        $actual = $this->process($reflection, $this->terminal);

        $this->ctx->assertInstanceOf(NotResolvableInterface::class, $actual, $message);
        $this->ctx->assertSame($reflection, $actual->getParameter(), $message);

        return $this;
    }

    private function createParameter(string $parameter): \ReflectionParameter
    {
        $function = $this->randomName();

        if (!\str_contains($parameter, '$')) {
            $parameter .= ' $' . $this->randomName();
        }

        eval("function $function($parameter): void {}");

        return (new \ReflectionFunction($function))
            ->getParameters()[0];
    }

    private function randomName(): string
    {
        return '__' . ++self::$generatedParameterId . \bin2hex(\random_bytes(4));
    }

    public function process(\ReflectionParameter $parameter, ResolverInterface $resolver): mixed
    {
        return $this->delegate->process($parameter, $resolver);
    }

    /**
     * @param non-empty-string $param
     * @param mixed $expected
     * @param string $message
     * @return $this
     */
    public function assertResolvingBy(#[Language('PHP')] string $param, mixed $expected, string $message = ''): self
    {
        $reflection = $this->createParameter($param);

        $this->ctx->assertSame($expected, $this->process($reflection, $this->terminal),
            $message ?: 'Failed asserting that parameter [' . $param
                . '] is resolvable by ['
                    . \get_debug_type($expected)
                    . (\is_scalar($expected) ? ' ' . \var_export($expected, true) : '')
                . '] type',
        );

        return $this;
    }

    /**
     * @param string $param
     * @return mixed
     */
    public function handleThrough(#[Language('PHP')] string $param): mixed
    {
        return $this->process($this->createParameter($param), $this->terminal);
    }

    /**
     * @param non-empty-string $param
     * @param string $message
     * @return $this
     */
    public function assertResolvingNull(#[Language('PHP')] string $param, string $message = ''): self
    {
        $this->assertResolvingBy($param, null, $message);

        return $this;
    }
}
