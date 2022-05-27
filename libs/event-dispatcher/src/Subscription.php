<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\EventDispatcher;

use Helix\Contracts\EventDispatcher\EventSubscriptionInterface;
use Helix\Contracts\EventDispatcher\ListenerInterface;
use Ramsey\Uuid\Uuid;

/**
 * @template TEvent of object
 *
 * @template-implements EventSubscriptionInterface<TEvent>
 */
final class Subscription implements EventSubscriptionInterface
{
    /**
     * @var \Closure(TEvent, self): void
     */
    private readonly \Closure $handler;

    /**
     * @var string|null
     */
    private ?string $id = null;

    /**
     * @var positive-int|0
     */
    private int $executions = 0;

    /**
     * @param ListenerInterface $listener
     * @param callable(TEvent,EventSubscriptionInterface<TEvent>|null):void $handler
     */
    public function __construct(
        private readonly ListenerInterface $listener,
        callable $handler
    ) {
        /** @psalm-suppress MixedPropertyTypeCoercion */
        $this->handler = $handler(...);
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): string
    {
        return $this->id ??= (string)Uuid::uuid4();
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch(object $event): object
    {
        ++$this->executions;

        ($this->handler)($event, $this);

        return $event;
    }

    /**
     * {@inheritDoc}
     */
    public function getExecutionsNumber(): int
    {
        return $this->executions;
    }

    /**
     * {@inheritDoc}
     */
    public function cancel(): void
    {
        $this->listener->cancel($this);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return $this->getId();
    }
}
