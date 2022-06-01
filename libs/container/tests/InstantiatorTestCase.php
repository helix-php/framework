<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Container\Tests;

use Helix\Container\Definition\DefinitionInterface;
use Helix\Container\Definition\InstanceDefinition;
use Helix\Container\Exception\ServiceNotInstantiatableException;
use Helix\Container\Instantiator;
use Helix\Container\Tests\Stub\ClassWithConstructorDefaults;
use Helix\Container\Tests\Stub\ClassWithConstructorException;
use Helix\Container\Tests\Stub\ClassWithoutConstructor;
use Helix\Contracts\Container\InstantiatorInterface;
use Helix\ParamResolver\Factory;
use Helix\ParamResolver\Middleware\ContainerServiceResolver;
use Helix\ParamResolver\Middleware\NamedArgumentsResolver;
use Helix\ParamResolver\Middleware\ObjectResolver;
use Helix\ParamResolver\Pipeline;
use Helix\ParamResolver\StatelessResolver;

/**
 * @group container
 */
class InstantiatorTestCase extends TestCase
{
    /**
     * @param array<non-empty-string, DefinitionInterface> $definitions
     * @return InstantiatorInterface
     */
    protected function instantiator(array $definitions = []): InstantiatorInterface
    {
        $resolver = new ContainerServiceResolver(
            $this->container($definitions),
        );

        $pipeline = Pipeline::createWithDefaultResolvers();
        $pipeline->prepend($resolver);

        return new Instantiator(new Factory(
            pipeline: $pipeline,
        ));
    }

    public function testCreationSimpleObject(): void
    {
        $expected = ClassWithoutConstructor::class;

        $actual = $this->instantiator()
            ->make($expected);

        $this->assertInstanceOf($expected, $actual);
    }

    public function testCreationWithConstructorDefaults(): void
    {
        $expected = ClassWithConstructorDefaults::class;

        $actual = $this->instantiator()
            ->make($expected);

        $this->assertInstanceOf($expected, $actual);
    }

    public function testCreationUsingValueResolvers(): void
    {
        $expectedScalar = 23;
        $expectedObject = (object)['some' => 42];

        $actual = $this->instantiator()
            ->make(ClassWithConstructorDefaults::class, [
                new NamedArgumentsResolver(['scalar' => $expectedScalar]),
                new ObjectResolver($expectedObject),
            ]);

        $this->assertSame($expectedScalar, $actual->scalar);
        $this->assertSame($expectedObject, $actual->class);
    }

    public function testCreationUsingContainer(): void
    {
        $expectedObject = (object)['some' => 42];

        $actual = $this->instantiator([
            \StdClass::class => new InstanceDefinition($expectedObject),
        ])
            ->make(ClassWithConstructorDefaults::class);

        $this->assertSame($expectedObject, $actual->class);
    }

    public function testNegativeCreationUsingString(): void
    {
        $this->expectException(ServiceNotInstantiatableException::class);
        $this->expectExceptionMessage('[some.any]');

        $this->instantiator()->make('some.any');
    }

    public function testCreationWithRuntimeException(): void
    {
        $this->expectException(ServiceNotInstantiatableException::class);
        $this->expectExceptionMessage(ClassWithConstructorException::class);

        $this->instantiator()->make(ClassWithConstructorException::class);
    }
}
