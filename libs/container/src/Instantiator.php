<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Container;

use Helix\Container\Exception\ServiceNotInstantiatableException;
use Helix\Contracts\Container\InstantiatorInterface;
use Helix\Contracts\ParamResolver\Exception\NotResolvableExceptionInterface;
use Helix\Contracts\ParamResolver\Exception\SignatureExceptionInterface;
use Helix\Contracts\ParamResolver\FactoryInterface;
use Helix\Contracts\ParamResolver\MiddlewareInterface;
use Helix\ParamResolver\Exception\ParamNotResolvableException;
use Helix\ParamResolver\Exception\SignatureException;
use Helix\ParamResolver\Factory;

final class Instantiator implements InstantiatorInterface
{
    /**
     * @param FactoryInterface $resolver
     */
    public function __construct(
        private readonly FactoryInterface $resolver = new Factory(),
    ) {
    }

    /**
     * {@inheritDoc}
     * @throws ParamNotResolvableException
     * @throws ServiceNotInstantiatableException
     * @throws SignatureException
     */
    public function make(string $id, iterable $resolvers = []): object
    {
        if (!\class_exists($id)) {
            throw ServiceNotInstantiatableException::fromInvalidClass($id);
        }

        $arguments = $this->getConstructorArguments($id, $resolvers);

        try {
            /**
             * @psalm-suppress InvalidReturnStatement
             * @psalm-suppress MixedMethodCall
             */
            return new $id(...$arguments);
        } catch (\Throwable $e) {
            throw ServiceNotInstantiatableException::fromException($e, $id);
        }
    }

    /**
     * @param class-string $class
     * @param iterable<MiddlewareInterface> $resolvers
     * @return iterable
     * @throws NotResolvableExceptionInterface
     * @throws SignatureExceptionInterface
     */
    private function getConstructorArguments(string $class, iterable $resolvers): iterable
    {
        if (\method_exists($class, '__construct')) {
            return $this->resolver->fromMethod($class, '__construct', $resolvers);
        }

        return [];
    }
}
