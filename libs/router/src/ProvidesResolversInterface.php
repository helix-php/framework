<?php

declare(strict_types=1);

namespace Helix\Router;

use Helix\Contracts\ParamResolver\ResolverInterface;

interface ProvidesResolversInterface
{
    /**
     * @return iterable<non-empty-string|class-string|ResolverInterface>
     */
    public function getResolvers(): iterable;
}
