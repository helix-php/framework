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
use Helix\Container\Definition\Registrar\DefinitionRegistrarInterface;

interface RegistrarInterface
{
    /**
     * Creates an alias for an existing or added service in the future.
     *
     * @param non-empty-string $id
     * @param non-empty-string $alias
     * @return void
     */
    public function alias(string $id, string $alias): void;

    /**
     * Registers a definition by it's ID.
     *
     * @template TService as object
     * @template TDefinition as DefinitionInterface<TService>
     *
     * @param non-empty-string|class-string<TService> $id
     * @param TDefinition $service
     * @return (
     *  TDefinition is SubscribableDefinitionInterface
     *      ? SubscribableDefinitionRegistrarInterface
     *      : DefinitionRegistrarInterface
     * )
     */
    public function define(string $id, DefinitionInterface $service): DefinitionRegistrarInterface;
}
