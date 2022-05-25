<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Container\Definition;

use Helix\Container\Event\Resolved;
use Helix\Container\Event\Resolving;
use Helix\Container\Exception\RegistrationException;
use Helix\Container\Registry;
use Helix\Contracts\EventDispatcher\EventSubscriptionInterface;
use Helix\EventDispatcher\Exception\ListenerException;
use Helix\EventDispatcher\Subscription;

final class DefinitionRegistrar implements DefinitionRegistrarInterface
{
    /**
     * @var non-empty-string
     */
    private const ERROR_REGISTER_INTERFACES = 'Can not resolve interfaces list from [%s] service';

    /**
     * @param non-empty-string $id
     * @param Registry $context
     */
    public function __construct(
        private readonly string $id,
        private readonly Registry $context,
    ) {
    }

    /**
     * @return $this
     * @throws RegistrationException
     */
    public function withInterfaces(): self
    {
        $concrete = $this->context->concrete($this->id);

        $interfaces = \class_implements($concrete);

        if ($interfaces === false) {
            $message = \sprintf(self::ERROR_REGISTER_INTERFACES, $concrete);
            throw new RegistrationException($message);
        }

        return $this->as(...$interfaces);
    }

    /**
     * {@inheritDoc}
     */
    public function as(string ...$aliases): self
    {
        foreach ($aliases as $alias) {
            $this->context->alias($this->id, $alias);
        }

        return $this;
    }

    /**
     * @param callable(Resolved):void $handler
     * @return EventSubscriptionInterface
     * @throws ListenerException
     * @throws \Throwable
     */
    public function onResolved(callable $handler): EventSubscriptionInterface
    {
        $definition = $this->context->definition($this->id);

        if ($definition instanceof LazyDefinition) {
            return $definition->onResolved($handler);
        }

        throw new \LogicException('Could not listen non-listenable definition');
    }

    /**
     * @param callable(Resolving):void $handler
     * @return EventSubscriptionInterface
     * @throws ListenerException
     * @throws \Throwable
     */
    public function onResolving(callable $handler): EventSubscriptionInterface
    {
        $definition = $this->context->definition($this->id);

        if ($definition instanceof LazyDefinition) {
            return $definition->onResolving($handler);
        }

        throw new \LogicException('Could not listen non-listenable definition');
    }


}
