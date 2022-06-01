<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Contracts\ParamResolver;

use Helix\Contracts\ParamResolver\Exception\NotResolvableExceptionInterface;

interface ResolverInterface
{
    /**
     * @param \ReflectionParameter $parameter
     * @return mixed
     * @throws NotResolvableExceptionInterface
     */
    public function handle(\ReflectionParameter $parameter): mixed;
}
