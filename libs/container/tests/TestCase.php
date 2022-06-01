<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Container\Tests;

use Helix\Container\Definition\DefinitionInterface;
use Helix\Container\Exception\ServiceNotFoundException;
use Helix\Contracts\Container\ContainerInterface;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * @group container
 */
abstract class TestCase extends BaseTestCase
{
    protected function container(array $definitions = []): ContainerInterface
    {
        return new class ($definitions) implements ContainerInterface {
            /**
             * @param array<non-empty-string, DefinitionInterface> $definitions
             */
            public function __construct(
                private readonly array $definitions,
            ) {
            }

            public function get(string $id, iterable $resolvers = []): object
            {
                return $this->definitions[$id]?->resolve()
                    ?? throw ServiceNotFoundException::fromName($id);
            }

            public function has(string $id): bool
            {
                return isset($this->definitions[$id]);
            }
        };
    }
}
