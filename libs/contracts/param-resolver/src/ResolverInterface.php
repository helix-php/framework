<?php

declare(strict_types=1);

namespace Helix\Contracts\ParamResolver;

use Helix\Contracts\ParamResolver\Exception\NotResolvableExceptionInterface;

interface ResolverInterface
{
    /**
     * @param \ReflectionParameter $parameter
     * @throws NotResolvableExceptionInterface
     */
    public function handle(\ReflectionParameter $parameter): mixed;
}
