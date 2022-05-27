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

/**
 * @mixin ListenerInterface
 * @psalm-require-implements ListenerInterface
 */
trait ListenerAwareTrait
{
    /**
     * @var ListenerInterface
     */
    private readonly ListenerInterface $listener;

    /**
     * {@inheritDoc}
     *
     * @see ListenerInterface::listen()
     */
    public function listen(callable|string $handlerOrEventClass, ?callable $handler = null): Subscription
    {
        return $this->listener->listen($handlerOrEventClass, $handler);
    }

    /**
     * {@inheritDoc}
     *
     * @see ListenerInterface::cancel()
     */
    public function cancel(EventSubscriptionInterface|\Stringable|string $subscription): void
    {
        $this->listener->cancel($subscription);
    }
}
