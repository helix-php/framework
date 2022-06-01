<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Container;

use Helix\Contracts\Container\ContainerInterface;
use Helix\Contracts\Container\DispatcherInterface;
use Helix\Contracts\ParamResolver\FactoryInterface;
use Helix\Contracts\ParamResolver\MiddlewareInterface;
use Helix\ParamResolver\Exception\ParamNotResolvableException;
use Helix\ParamResolver\Exception\SignatureException;
use Helix\ParamResolver\Factory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

final class Dispatcher implements DispatcherInterface
{
    /**
     * @var non-empty-string
     */
    private const DEFAULT_ACTION_DELIMITER = '->';

    /**
     * @param ContainerInterface $container
     * @param FactoryInterface $resolver
     * @param non-empty-string $delimiter
     */
    public function __construct(
        private ContainerInterface $container,
        private string $delimiter = self::DEFAULT_ACTION_DELIMITER,
        private readonly FactoryInterface $resolver = new Factory(),
    ) {
    }

    /**
     * @psalm-immutable
     * @param non-empty-string $delimiter
     * @return $this
     */
    public function withDelimiter(string $delimiter): self
    {
        $self = clone $this;
        $self->delimiter = $delimiter;

        return $self;
    }

    /**
     * @psalm-immutable
     * @param ContainerInterface $container
     * @return $this
     */
    public function using(ContainerInterface $container): self
    {
        $self = clone $this;
        $self->container = $container;

        return $self;
    }

    /**
     * {@inheritDoc}
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ParamNotResolvableException
     * @throws SignatureException
     */
    public function call(callable|string|array $fn, iterable $resolvers = []): mixed
    {
        if (\is_string($fn) && \str_contains($fn, $this->delimiter)) {
            return $this->callServiceInstance($fn, $resolvers);
        }

        return $fn(...$this->resolver->fromCallable($fn, $resolvers));
    }

    /**
     * @param callable|non-empty-string $fn
     * @param iterable<MiddlewareInterface|class-string<MiddlewareInterface>> $resolvers
     * @return \Closure():mixed
     */
    public function detach(callable|string $fn, iterable $resolvers = []): \Closure
    {
        return function () use ($fn, $resolvers): mixed {
            return $this->call($fn, $resolvers);
        };
    }

    /**
     * @param non-empty-string $signature
     * @return array{non-empty-string, non-empty-string}
     * @throws SignatureException
     */
    private function parse(string $signature): array
    {
        $parts = \explode($this->delimiter, $signature);

        if (\count($parts) !== 2 || $parts[0] === '' || $parts[1] === '') {
            $message = 'Action signature must be like '
                . '[Container\Service\Name%smethod()], but [%s] given';

            throw new SignatureException(\sprintf($message, $this->delimiter, $signature));
        }

        return $parts;
    }

    /**
     * @param non-empty-string $signature
     * @param iterable<MiddlewareInterface|class-string<MiddlewareInterface>> $resolvers
     * @return mixed
     * @throws ParamNotResolvableException
     * @throws SignatureException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function callServiceInstance(string $signature, iterable $resolvers): mixed
    {
        [$service, $method] = $this->parse($signature);

        $instance = $this->container->get($service, $resolvers);

        return $this->call([$instance, $method], $resolvers);
    }
}
