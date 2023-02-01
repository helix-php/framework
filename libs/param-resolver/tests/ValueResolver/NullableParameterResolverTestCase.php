<?php

declare(strict_types=1);

namespace Helix\ParamResolver\Tests\ValueResolver;

use Helix\ParamResolver\Middleware\NullableParameterResolver;
use Helix\ParamResolver\Tests\Wrapper\TestingValueMiddleware;

/**
 * @group param-resolver
 */
class NullableParameterResolverTestCase extends TestCase
{
    protected function resolver(): TestingValueMiddleware
    {
        return $this->testingValueResolver(
            new NullableParameterResolver(),
        );
    }

    public function testSupportsWithDefaultValue(): void
    {
        $this->resolver()
            ->assertResolvingNull('int $arg = null');
    }

    public function testSupportsNullableHint(): void
    {
        $this->resolver()
            ->assertResolvingNull('?int $arg');
    }

    public function testSupportsNullableUnionHint(): void
    {
        $this->resolver()
            ->assertResolvingNull('int|string|null $arg');
    }

    public function testNotSupportsWithoutDefaultNull(): void
    {
        $this->resolver()
            ->assertNotSupports('int $arg = 23');
    }

    public function testNotSupportsWithoutNullInUnion(): void
    {
        $this->resolver()
            ->assertNotSupports('int|string|bool $arg');
    }
}
