<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\ParamResolver;

use Helix\Contracts\ParamResolver\ResolverInterface;
use Helix\ParamResolver\Exception\ParamNotResolvableException;

final class ExceptionResolver implements ResolverInterface
{
    /**
     * {@inheritDoc}
     */
    public function handle(\ReflectionParameter $parameter): never
    {
        throw ParamNotResolvableException::fromReflectionParameter($parameter);
    }
}
