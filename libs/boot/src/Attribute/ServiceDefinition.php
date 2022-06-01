<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Boot\Attribute;

use Helix\Container\Container;
use Helix\Container\Definition\DefinitionInterface;
use Helix\Contracts\EventDispatcher\DispatcherInterface;

abstract class ServiceDefinition implements MethodMetadataInterface
{
    /**
     * @var array<non-empty-string>
     */
    public readonly array $aliases;

    /**
     * @param class-string|null $id
     * @param array<non-empty-string>|non-empty-string $as
     */
    public function __construct(
        public ?string $id = null,
        array|string $as = [],
    ) {
        $this->aliases = (array)$as;
    }

    /**
     * @param string $id
     * @param DispatcherInterface $events
     * @param \Closure():object $instantiator
     * @return DefinitionInterface
     */
    abstract public function create(
        string $id,
        DispatcherInterface $events,
        \Closure $instantiator,
    ): DefinitionInterface;
}
