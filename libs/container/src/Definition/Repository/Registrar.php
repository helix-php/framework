<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Container\Definition\Repository;

use Helix\Container\Definition\DefinitionInterface;
use Helix\Container\Definition\FactoryDefinition;
use Helix\Container\Definition\InstanceDefinition;
use Helix\Container\Definition\Registrar\DefinitionRegistrar;
use Helix\Container\Definition\Registrar\DefinitionRegistrarInterface;
use Helix\Container\Definition\Registrar\SubscribableDefinitionRegistrar;
use Helix\Container\Definition\Registrar\SubscribableDefinitionRegistrarInterface;
use Helix\Container\Definition\SingletonDefinition;
use Helix\Container\Definition\SubscribableDefinitionInterface;
use Helix\Container\Definition\WeakSingletonDefinition;
use Helix\Contracts\Container\InstantiatorInterface;
use Helix\Contracts\EventDispatcher\DispatcherInterface;

final class Registrar implements RegistrarInterface, RepositoryProviderInterface
{
    /**
     * @var array<non-empty-string, DefinitionInterface>
     */
    private array $definitions = [];

    /**
     * @var array<non-empty-string, non-empty-string>
     */
    private array $aliases = [];

    /**
     * @param InstantiatorInterface $instantiator
     * @param DispatcherInterface|null $events
     */
    public function __construct(
        private readonly InstantiatorInterface $instantiator,
        private readonly ?DispatcherInterface $events = null,
    ) {
    }

    /**
     * @template T of object
     *
     * @param T $service
     * @return DefinitionRegistrarInterface
     */
    public function instance(object $service): DefinitionRegistrarInterface
    {
        return $this->define($service::class, new InstanceDefinition($service));
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $service
     * @param null|\Closure():T $registrar
     * @return SubscribableDefinitionRegistrarInterface
     */
    public function singleton(
        string $service,
        \Closure $registrar = null,
    ): SubscribableDefinitionRegistrarInterface {
        return $this->define($service, new SingletonDefinition(
            $registrar ?? fn () => $this->instantiator->make($service),
            $this->events,
        ));
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $service
     * @param null|\Closure():T $registrar
     * @return SubscribableDefinitionRegistrarInterface
     */
    public function factory(
        string $service,
        \Closure $registrar = null,
    ): SubscribableDefinitionRegistrarInterface {
        return $this->define($service, new FactoryDefinition(
            $registrar ?? fn () => $this->instantiator->make($service),
            $this->events,
        ));
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $service
     * @param null|\Closure():T $registrar
     * @return SubscribableDefinitionRegistrarInterface
     */
    public function weak(
        string $service,
        \Closure $registrar = null,
    ): SubscribableDefinitionRegistrarInterface {
        return $this->define($service, new WeakSingletonDefinition(
            $registrar ?? fn () => $this->instantiator->make($service),
            $this->events,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function define(string $id, DefinitionInterface $service): DefinitionRegistrarInterface
    {
        $this->definitions[$id] = $service;

        /** @psalm-suppress DocblockTypeContradiction */
        if ($service instanceof SubscribableDefinitionInterface) {
            return new SubscribableDefinitionRegistrar($id, $this, $service);
        }

        return new DefinitionRegistrar($id, $this);
    }

    /**
     * {@inheritDoc}
     */
    public function alias(string $id, string $alias): void
    {
        $this->aliases[$alias] = $id;
    }

    /**
     * {@inheritDoc}
     */
    public function getRepository(): RepositoryInterface
    {
        $definitions = $this->definitions;

        foreach ($this->aliases as $alias => $id) {
            // Skip non-resolvable and extra service aliases
            if (!isset($this->definitions[$id]) || isset($this->definitions[$alias])) {
                continue;
            }

            $definitions[$alias] = $this->definitions[$id];
        }

        /** @psalm-suppress MixedArgumentTypeCoercion */
        return new Repository($definitions);
    }
}
