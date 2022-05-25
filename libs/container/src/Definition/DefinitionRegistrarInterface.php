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
use Helix\Contracts\EventDispatcher\EventSubscriptionInterface;

interface DefinitionRegistrarInterface
{
    /**
     * @param non-empty-string ...$aliases
     * @return $this
     */
    public function as(string ...$aliases): self;

    /**
     * @return $this
     */
    public function withInterfaces(): self;

    /**
     * @param callable(Resolving):void $handler
     * @return EventSubscriptionInterface
     */
    public function onResolving(callable $handler): EventSubscriptionInterface;

    /**
     * @param callable(Resolved):void $handler
     * @return EventSubscriptionInterface
     */
    public function onResolved(callable $handler): EventSubscriptionInterface;
}
