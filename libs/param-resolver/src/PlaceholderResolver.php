<?php

declare(strict_types=1);

namespace Helix\ParamResolver;

use Helix\Contracts\ParamResolver\ResolverInterface;
use Helix\ParamResolver\Parameter\Placeholder;

final class PlaceholderResolver implements ResolverInterface
{
    /**
     * {@inheritDoc}
     */
    public function handle(\ReflectionParameter $parameter): Placeholder
    {
        return new Placeholder($parameter);
    }
}
