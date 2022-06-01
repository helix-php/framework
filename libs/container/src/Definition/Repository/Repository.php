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
use Helix\Container\Exception\ServiceNotFoundException;

final class Repository implements RepositoryInterface
{
    /**
     * @param array<non-empty-string, DefinitionInterface> $definitions
     */
    public function __construct(
        private readonly array $definitions = [],
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $id): bool
    {
        return isset($this->definitions[$id]);
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-suppress MixedReturnTypeCoercion
     * @throws ServiceNotFoundException
     */
    public function get(string $id): DefinitionInterface
    {
        if (!isset($this->definitions[$id])) {
            throw ServiceNotFoundException::fromName($id);
        }

        return $this->definitions[$id];
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->definitions);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return \count($this->definitions);
    }
}
