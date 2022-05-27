<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Contracts\ParamResolver;

use Helix\Contracts\ParamResolver\Exception\ResolvingExceptionInterface;
use Helix\Contracts\ParamResolver\Exception\SignatureExceptionInterface;

/**
 * @psalm-type ValueResolversList = iterable<ValueResolverInterface>
 */
interface ResolverInterface
{
    /**
     * @param callable-string $name
     * @param ValueResolversList $resolvers
     * @return iterable
     * @throws ResolvingExceptionInterface
     * @throws SignatureExceptionInterface
     */
    public function fromFunction(string $name, iterable $resolvers = []): iterable;

    /**
     * @param class-string|object $class
     * @param non-empty-string $name
     * @param ValueResolversList $resolvers
     * @return iterable
     * @throws ResolvingExceptionInterface
     * @throws SignatureExceptionInterface
     */
    public function fromMethod(string|object $class, string $name, iterable $resolvers = []): iterable;

    /**
     * @param \Closure $closure
     * @param ValueResolversList $resolvers
     * @return iterable
     */
    public function fromClosure(\Closure $closure, iterable $resolvers = []): iterable;

    /**
     * @param callable $callable
     * @param ValueResolversList $resolvers
     * @return iterable
     * @throws ResolvingExceptionInterface
     * @throws SignatureExceptionInterface
     */
    public function fromCallable(callable $callable, iterable $resolvers = []): iterable;
}
