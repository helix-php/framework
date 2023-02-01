<?php

declare(strict_types=1);

namespace Helix\ParamResolver\Tests\ValueResolver;

use Helix\ParamResolver\Middleware\NamedArgumentsResolver;
use Helix\ParamResolver\Tests\Wrapper\TestingValueMiddleware;

/**
 * @group param-resolver
 */
class NamedArgumentsResolverTestCase extends TestCase
{
    protected function resolver(array $arguments): TestingValueMiddleware
    {
        return $this->testingValueResolver(
            new NamedArgumentsResolver($arguments),
        );
    }

    public function testSupportsLiteral(): void
    {
        $expected = \random_int(\PHP_INT_MIN, \PHP_INT_MAX);

        $this->resolver(['arg' => $expected])
            ->assertResolvingBy('int $arg', $expected);
    }

    public function testCaseSensitive(): void
    {
        $this->resolver(['arg' => 42])
            ->assertNotSupports('int $Arg');
    }

    public function testIgnoresTypeHints(): void
    {
        $this->resolver(['arg' => 'string'])
            ->assertResolvingBy('int $arg', 'string');
    }
}
