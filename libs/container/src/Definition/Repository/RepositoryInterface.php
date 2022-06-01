<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Container\Definition\Repository;

use Helix\Container\Definition\DefinitionInterface;
use Psr\Container\ContainerInterface as PsrContainerInterface;

/**
 * @template-implements \IteratorAggregate<non-empty-string, DefinitionInterface>
 */
interface RepositoryInterface extends PsrContainerInterface, \IteratorAggregate, \Countable
{
    /**
     * Finds a service definition of the container by its identifier
     * and returns it.
     *
     * @template TService of object
     *
     * @param non-empty-string|class-string<TService> $id
     * @return DefinitionInterface<TService>
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function get(string $id): DefinitionInterface;

    /**
     * Returns {@see true} in case that the service was registered
     * as a definition.
     *
     * @param non-empty-string $id
     * @return bool
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function has(string $id): bool;
}
