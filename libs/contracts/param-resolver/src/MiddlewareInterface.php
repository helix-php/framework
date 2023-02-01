<?php

declare(strict_types=1);

namespace Helix\Contracts\ParamResolver;

interface MiddlewareInterface
{
    /**
     * @param \ReflectionParameter $parameter
     * @param ResolverInterface $resolver
     * @return mixed
     */
    public function process(\ReflectionParameter $parameter, ResolverInterface $resolver): mixed;
}
