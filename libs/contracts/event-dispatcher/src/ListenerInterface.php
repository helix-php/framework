<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Contracts\EventDispatcher;

interface ListenerInterface
{
    /**
     * Listener usage:
     * ```
     * $listener->listen(SomeEvent::class, function() {
     *      echo 'SomeEvent handling!';
     * });
     *
     * // Otherwise
     * $listener->listen(function(SomeEvent $event): void {
     *      echo 'SomeEvent handling!';
     * });
     * ```
     *
     * @template TEvent of object
     *
     * @param class-string<TEvent>|callable(TEvent,EventSubscriptionInterface<TEvent>|null):void $handlerOrEventClass
     * @param null|callable(TEvent,EventSubscriptionInterface<TEvent>|null):void $handler
     * @return EventSubscriptionInterface<TEvent>
     */
    public function listen(callable|string $handlerOrEventClass, ?callable $handler = null): EventSubscriptionInterface;

    /**
     * @template TEvent of object
     *
     * @param EventSubscriptionInterface<TEvent>|\Stringable|string $subscription
     * @return void
     */
    public function cancel(EventSubscriptionInterface|\Stringable|string $subscription): void;
}
