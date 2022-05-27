<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Contracts\EventDispatcher;

/**
 * @template TEvent of object
 *
 * @template-extends DispatcherInterface<TEvent>
 */
interface EventSubscriptionInterface extends \Stringable
{
    /**
     * @return non-empty-string|int
     */
    public function getId(): string|int;

    /**
     * @return positive-int|0
     */
    public function getExecutionsNumber(): int;

    /**
     * @return void
     */
    public function cancel(): void;

    /**
     * @template TEvent of object
     *
     * @param TEvent $event
     * @return TEvent
     */
    public function __invoke(object $event): object;
}
