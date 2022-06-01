<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Container\Definition\Registrar;

use Helix\Container\Definition\Repository\RegistrarInterface;

/**
 * @internal Helix\Container is an internal library class,
 *           please do not use it in your code.
 * @psalm-internal Helix\Container
 */
class DefinitionRegistrar implements DefinitionRegistrarInterface
{
    /**
     * @param non-empty-string $id
     * @param RegistrarInterface $registrar
     */
    public function __construct(
        private readonly string $id,
        private readonly RegistrarInterface $registrar,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function as(string ...$aliases): self
    {
        foreach ($aliases as $alias) {
            $this->registrar->alias($this->id, $alias);
        }

        return $this;
    }
}
