<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Contracts\ParamResolver;

use Helix\Contracts\ParamResolver\Exception\NotResolvableExceptionInterface;
use Helix\Contracts\ParamResolver\Exception\SignatureExceptionInterface;

interface FactoryInterface
{
    /**
     * Returns a list of resolved values for the passed callable argument.
     *
     * @param callable $callable
     * @param iterable<MiddlewareInterface> $resolvers
     * @return iterable
     * @throws NotResolvableExceptionInterface
     * @throws SignatureExceptionInterface
     */
    public function fromCallable(callable $callable, iterable $resolvers = []): iterable;

    /**
     * Returns a list of resolved values for the passed method argument.
     *
     * @param class-string|object $class
     * @param non-empty-string $method
     * @param iterable<MiddlewareInterface> $resolvers
     * @return iterable
     * @throws NotResolvableExceptionInterface
     * @throws SignatureExceptionInterface
     */
    public function fromMethod(string|object $class, string $method, iterable $resolvers = []): iterable;

    /**
     * Returns a list of resolved values for the passed function argument.
     *
     * @param callable-string $function
     * @param iterable<MiddlewareInterface> $resolvers
     * @return iterable
     * @throws NotResolvableExceptionInterface
     * @throws SignatureExceptionInterface
     */
    public function fromFunction(string $function, iterable $resolvers = []): iterable;
}
