<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Boot\Attribute;

use Helix\Container\Definition\WeakSingletonDefinition;
use Helix\Container\Definition\DefinitionInterface;
use Helix\Contracts\EventDispatcher\DispatcherInterface;

#[\Attribute(\Attribute::TARGET_METHOD)]
final class WeakSingleton extends ServiceDefinition
{
    /**
     * {@inheritDoc}
     */
    public function create(
        string $id,
        DispatcherInterface $events,
        \Closure $instantiator,
    ): DefinitionInterface {
        return new WeakSingletonDefinition($instantiator, $events);
    }
}
