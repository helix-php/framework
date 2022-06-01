<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\ParamResolver\Tests\ValueResolver;

use Helix\ParamResolver\Middleware\DefaultValueResolver;
use Helix\ParamResolver\Tests\Wrapper\TestingValueMiddleware;

/**
 * @group param-resolver
 */
class DefaultValueResolverTestCase extends TestCase
{
    protected function resolver(): TestingValueMiddleware
    {
        return $this->testingValueResolver(
            new DefaultValueResolver(),
        );
    }

    public function testSupportsLiteral(): void
    {
        $expected = \random_int(\PHP_INT_MIN, \PHP_INT_MAX);

        $this->resolver()
            ->assertResolvingBy('int $arg = ' . $expected, $expected);
    }

    public function testSupportsNullableLiteral(): void
    {
        $this->resolver()
            ->assertResolvingNull('?int $arg = null');
    }

    public function testNotSupportsWithoutDefault(): void
    {
        $this->resolver()
            ->assertNotSupports('int $arg');
    }

    public function testNotSupportsNullable(): void
    {
        $this->resolver()
            ->assertNotSupports('?int $arg');
    }
}
