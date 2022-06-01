<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Bridge\Doctrine\ValueResolver;

use Doctrine\ORM\EntityManagerInterface;
use Helix\ParamInfo\Type;

/**
 * @template TReference of object
 * @template-extends PoolMiddleware<TReference>
 */
class EntityManagerResolver extends PoolMiddleware
{
    /**
     * @param \ReflectionParameter $parameter
     * @return bool
     */
    public function supports(\ReflectionParameter $parameter): bool
    {
        return Type::fromParameter($parameter)
            ->allowsSubclassOf(EntityManagerInterface::class);
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return EntityManagerInterface
     */
    public function resolve(\ReflectionParameter $parameter): EntityManagerInterface
    {
        return $this->getEntityManagerByParameter($parameter);
    }
}
