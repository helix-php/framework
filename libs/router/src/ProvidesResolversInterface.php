<?php

declare(strict_types=1);

namespace Helix\Router;

use Helix\Contracts\ParamResolver\ValueResolverInterface;

interface ProvidesResolversInterface
{
    /**
     * @return iterable<non-empty-string|class-string|ValueResolverInterface>
     */
    public function getResolvers(): iterable;
}
