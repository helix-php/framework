<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\ParamResolver\Tests\ValueResolver;

use Helix\ParamResolver\Middleware\ObjectResolver;
use Helix\ParamResolver\Tests\ValueResolver\Stub\ValueObjectStub;
use Helix\ParamResolver\Tests\ValueResolver\Stub\ValueObjectStubInterface;
use Helix\ParamResolver\Tests\Wrapper\TestingValueMiddleware;

/**
 * Tests that {@see ObjectResolver} correctly resolves variant signatures.
 *
 * @group param-resolver
 */
class ObjectResolverTestCase extends TestCase
{
    protected function resolver(object $target): TestingValueMiddleware
    {
        return $this->testingValueResolver(
            new ObjectResolver($target),
        );
    }

    /**
     * @testdox Match function(ValueObjectStub) by object(ValueObjectStub)
     */
    public function testSupportsBySignature(): void
    {
        $this->resolver($expected = new ValueObjectStub())
            ->assertResolvingBy(ValueObjectStub::class, $expected);
    }

    /**
     * @testdox Match function(object) by object(ValueObjectStub)
     */
    public function testNotSupportsByInvalidSignature(): void
    {
        $this->resolver(new ValueObjectStub())
            ->assertNotSupports('object');
    }

    /**
     * @testdox Match function(string | ValueObjectStub) by object(ValueObjectStub)
     */
    public function testSupportsByUnionSignature(): void
    {
        $this->resolver($expected = new ValueObjectStub())
            ->assertResolvingBy('string|' . ValueObjectStub::class, $expected);
    }

    /**
     * @testdox Match function(string | int) by object(ValueObjectStub)
     */
    public function testNotSupportsByInvalidUnionSignature(): void
    {
        $this->resolver(new ValueObjectStub())
            ->assertNotSupports('string|int');
    }

    /**
     * @testdox Match function(ValueObjectStubInterface & ValueObjectStub) by object(ValueObjectStub)
     */
    public function testSupportsByIntersectionSignature(): void
    {
        $this->resolver($expected = new ValueObjectStub())
            ->assertResolvingBy(
                ValueObjectStubInterface::class . '&' . ValueObjectStub::class,
                $expected,
            );
    }

    /**
     * @testdox Match function(StdClass & ValueObjectStub) by object(ValueObjectStub)
     */
    public function testNotSupportsByInvalidIntersectionSignature(): void
    {
        $this->resolver(new ValueObjectStub())
            ->assertNotSupports('\StdClass&' . ValueObjectStub::class);
    }

    /**
     * @testdox Match function(ValueObjectStubInterface) by object(ValueObjectStub)
     */
    public function testSupportsByInterface(): void
    {
        $this->resolver($expected = new ValueObjectStub())
            ->assertResolvingBy(ValueObjectStubInterface::class, $expected);
    }

    /**
     * @testdox Match function(string & ValueObjectStubInterface) by object(ValueObjectStub)
     */
    public function testSupportsByUnionSignatureByInterface(): void
    {
        $this->resolver($expected = new ValueObjectStub())
            ->assertResolvingBy('string|' . ValueObjectStubInterface::class, $expected);
    }
}
