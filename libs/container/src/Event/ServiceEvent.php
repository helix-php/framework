<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Container\Event;

use Helix\Container\Definition\DefinitionInterface;

/**
 * @template T of object
 */
abstract class ServiceEvent
{
    /**
     * @param DefinitionInterface<T> $definition
     */
    public function __construct(
        public readonly DefinitionInterface $definition,
    ) {
    }
}
