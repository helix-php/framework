<?php

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
