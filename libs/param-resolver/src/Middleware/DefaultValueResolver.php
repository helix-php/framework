<?php

declare(strict_types=1);

namespace Helix\ParamResolver\Middleware;

use Helix\Contracts\ParamResolver\ResolverInterface;

/**
 * Resolves parameter value by default value of this parameter.
 *
 * Options:
 *  - [✓] function(int $a = 23) -> resolves by 23
 *  - [✓] function(?int $b = null) -> resolves by null
 * Except:
 *  - [×] function(int $c)
 */
final class DefaultValueResolver extends Middleware
{
    /**
     * {@inheritDoc}
     */
    public function process(\ReflectionParameter $parameter, ResolverInterface $resolver): mixed
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        return $resolver->handle($parameter);
    }
}
