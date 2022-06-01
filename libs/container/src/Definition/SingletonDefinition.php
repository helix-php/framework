<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Container\Definition;

/**
 * @template TService of object
 *
 * @template-extends LazyDefinition<TService>
 */
class SingletonDefinition extends LazyDefinition
{
    /**
     * @var TService|null
     */
    protected ?object $instance = null;

    /**
     * {@inheritDoc}
     */
    public function resolve(): object
    {
        return $this->instance ??= $this->initialize();
    }
}
