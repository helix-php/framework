<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Container\ParamResolver;

/**
 * @template T of mixed
 */
interface ValueResolverInterface
{
    /**
     * @param \ReflectionParameter $parameter
     * @return bool
     */
    public function supports(\ReflectionParameter $parameter): bool;

    /**
     * @param \ReflectionParameter $parameter
     * @return T
     */
    public function resolve(\ReflectionParameter $parameter): mixed;
}