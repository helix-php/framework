<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Container;

use Helix\Container\Definition\DefinitionRegistrarInterface;
use Helix\Container\Definition\FactoryDefinition;
use Helix\Container\Definition\InstanceDefinition;
use Helix\Container\Definition\SingletonDefinition;
use Helix\Container\Definition\WeakSingletonDefinition;
use Helix\Container\Event\Resolved;
use Helix\Container\Event\Resolving;
use Helix\Container\Exception\ServiceNotFoundException;
use Helix\Contracts\Container\DefinitionInterface;
use Helix\Contracts\Container\DispatcherInterface;
use Helix\Contracts\Container\InstantiatorInterface;
use Helix\Contracts\Container\RegistrarInterface;
use Helix\Contracts\Container\RepositoryInterface;
use Helix\Contracts\EventDispatcher\DispatcherInterface as EventDispatcherInterface;
use Helix\Contracts\EventDispatcher\EventSubscriptionInterface;
use Helix\Contracts\EventDispatcher\ListenerInterface;
use Helix\Contracts\EventDispatcher\ListenerInterface as EventListenerInterface;
use Helix\Contracts\ParamResolver\ParamResolverInterface;
use Helix\Contracts\ParamResolver\RegistrarInterface as ParamResolverRegistrarInterface;
use Helix\Contracts\ParamResolver\RepositoryInterface as ParamResolverRepositoryInterface;
use Helix\Contracts\ParamResolver\ValueResolverInterface;
use Helix\EventDispatcher\Dispatcher as EventDispatcher;
use Helix\EventDispatcher\Listener as EventListener;
use Helix\ParamResolver\Resolver;
use Helix\ParamResolver\ValueResolver\ContainerServiceResolver;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface as PsrContainerInterface;
use Helix\Contracts\Container\ContainerInterface;

final class Container implements
    ContainerInterface,
    DispatcherInterface,
    InstantiatorInterface,
    RegistrarInterface
{
    /**
     * @var Registry
     */
    private readonly Registry $definitions;

    /**
     * @var Resolver
     */
    private readonly Resolver $resolver;

    /**
     * @var InstantiatorInterface
     */
    private readonly InstantiatorInterface $instantiator;

    /**
     * @var DispatcherInterface
     */
    private DispatcherInterface $dispatcher;

    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $events;

    /**
     * @var EventListenerInterface
     */
    private EventListenerInterface $listener;

    /**
     * @param PsrContainerInterface|null $parent
     */
    public function __construct(
        private readonly ?PsrContainerInterface $parent = null,
    ) {
        $this->listener = new EventListener();
        $this->events = new EventDispatcher($this->listener);

        $this->resolver = new Resolver([
            new ContainerServiceResolver($this),
        ]);

        $this->definitions = new Registry();
        $this->instantiator = new Instantiator($this->definitions, $this->resolver);
        $this->dispatcher = new Dispatcher($this, $this->resolver);

        $this->registerSelf();
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->events;
    }

    /**
     * @param callable(Resolving):void $handler
     * @return EventSubscriptionInterface
     * @throws \Throwable
     */
    public function onResolving(callable $handler): EventSubscriptionInterface
    {
        return $this->listener->on(Resolving::class, $handler);
    }

    /**
     * @param callable(Resolving):void $handler
     * @return EventSubscriptionInterface
     * @throws \Throwable
     */
    public function onResolved(callable $handler): EventSubscriptionInterface
    {
        return $this->listener->on(Resolved::class, $handler);
    }

    /**
     * @template TIdentifier as object
     *
     * @param non-empty-string|class-string<TIdentifier> $id
     * @param null|\Closure():TIdentifier $registrar
     * @return DefinitionRegistrarInterface
     */
    public function singleton(string $id, \Closure $registrar = null): DefinitionRegistrarInterface
    {
        return $this->define($id, new SingletonDefinition(
            $this->detach($registrar ?? fn (): object => $this->make($id)),
            $this->events
        ));
    }

    /**
     * @template TIdentifier as object
     *
     * @param non-empty-string|class-string<TIdentifier> $id
     * @param null|\Closure():TIdentifier $registrar
     * @return DefinitionRegistrarInterface
     */
    public function weak(string $id, \Closure $registrar = null): DefinitionRegistrarInterface
    {
        return $this->define($id, new WeakSingletonDefinition(
            $this->detach($registrar ?? fn (): object => $this->make($id)),
            $this->events
        ));
    }

    /**
     * @template TIdentifier as object
     *
     * @param non-empty-string|class-string<TIdentifier> $id
     * @param null|\Closure():TIdentifier $registrar
     * @return DefinitionRegistrarInterface
     */
    public function factory(string $id, \Closure $registrar = null): DefinitionRegistrarInterface
    {
        return $this->define($id, new FactoryDefinition(
            $this->detach($registrar ?? fn (): object => $this->make($id)),
            $this->events
        ));
    }

    /**
     * @param object $id
     * @return DefinitionRegistrarInterface
     */
    public function instance(object $id): DefinitionRegistrarInterface
    {
        return $this->define($id::class, new InstanceDefinition($id));
    }

    /**
     * {@inheritDoc}
     */
    public function define(string $id, DefinitionInterface $service): DefinitionRegistrarInterface
    {
        return $this->definitions->define($id, $service);
    }

    /**
     * @template T of object
     * @param non-empty-string|class-string<T> $id
     * @return DefinitionInterface<T>
     * @throws ServiceNotFoundException
     */
    public function definition(string $id): DefinitionInterface
    {
        return $this->definitions->get($id);
    }

    /**
     * {@inheritDoc}
     */
    public function alias(string $id, string $alias): void
    {
        $this->definitions->alias($id, $alias);
    }

    /**
     * @template T of object
     *
     * @param non-empty-string|class-string<T> $id
     * @param iterable<ValueResolverInterface> $resolvers
     * @return T
     * @throws ServiceNotFoundException
     * @throws ContainerExceptionInterface
     */
    public function get(string $id, iterable $resolvers = []): object
    {
        if ($this->parent?->has($id)) {
            return $this->parent->get($id);
        }

        if ($this->definitions->has($id)) {
            $definition = $this->definitions->get($id);

            return $definition->resolve();
        }

        return $this->make($id, $resolvers);
    }

    /**
     * @param non-empty-string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return $this->parent?->has($id) || $this->definitions->has($id);
    }

    /**
     * {@inheritDoc}
     */
    public function call(callable|string $fn, iterable $resolvers = []): mixed
    {
        return $this->dispatcher->call($fn, $resolvers);
    }

    /**
     * @param callable|string $fn
     * @param iterable<ValueResolverInterface> $resolvers
     * @return \Closure():mixed
     */
    public function detach(callable|string $fn, iterable $resolvers = []): \Closure
    {
        return function () use ($fn, $resolvers): mixed {
            return $this->call($fn, $resolvers);
        };
    }

    /**
     * {@inheritDoc}
     */
    public function make(string $id, iterable $resolvers = []): object
    {
        return $this->instantiator->make($id, $resolvers);
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator(): \Traversable
    {
        return $this->definitions->getIterator();
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return $this->definitions->count();
    }

    /**
     * @return void
     */
    private function registerSelf(): void
    {
        $this->instance($this->resolver)
            ->as(ParamResolverInterface::class)
            ->as(ParamResolverRegistrarInterface::class)
            ->as(ParamResolverRepositoryInterface::class);

        $this->instance($this)
            ->as(PsrContainerInterface::class)
            ->as(RepositoryInterface::class)
            ->as(RegistrarInterface::class)
            ->as(ContainerInterface::class)
            ->as(DispatcherInterface::class)
            ->as(InstantiatorInterface::class);
    }

    /**
     * @return void
     */
    public function __clone(): void
    {
        $this->dispatcher = clone $this->dispatcher;
    }
}
