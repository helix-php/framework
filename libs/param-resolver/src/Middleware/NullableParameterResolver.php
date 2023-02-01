<?php

declare(strict_types=1);

namespace Helix\ParamResolver\Middleware;

use Helix\Contracts\ParamResolver\ResolverInterface;

/**
 * Returns (resolves) {@see null} if the parameter allows passing {@see null}.
 *
 * - [✓] function(?int $some)
 * - [✓] function(int|null $some)
 * - [✓] function(int $some = null)
 * - [×] function(int $some)
 */
final class NullableParameterResolver extends Middleware
{
    /**
     * {@inheritDoc}
     */
    public function process(\ReflectionParameter $parameter, ResolverInterface $resolver): mixed
    {
        if ($parameter->allowsNull()) {
            return null;
        }

        return $resolver->handle($parameter);
    }
}
