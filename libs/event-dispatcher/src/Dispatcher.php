<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\EventDispatcher;

use Helix\Contracts\EventDispatcher\DispatcherInterface;
use Helix\Contracts\EventDispatcher\EventSubscriptionInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

final class Dispatcher implements DispatcherInterface
{
    /**
     * @param ListenerProviderInterface $provider
     * @param DispatcherInterface|null $parent
     */
    public function __construct(
        private readonly ListenerProviderInterface $provider,
        private readonly ?DispatcherInterface $parent = null
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch(object $event): object
    {
        /** @var EventSubscriptionInterface $subscription */
        foreach ($this->provider->getListenersForEvent($event) as $subscription) {
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                return $event;
            }

            $event = $subscription->dispatch($event);
        }

        if (isset($this->parent)) {
            return $this->parent->dispatch($event);
        }

        return $event;
    }
}
