<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\ParamResolver\ValueResolver;

use Helix\ParamResolver\Introspection\Parameter;

/**
 * @template T of object
 */
class ObjectResolver extends ValueResolver
{
    /**
     * @param T $context
     */
    public function __construct(
        private readonly object $context,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function supports(\ReflectionParameter $parameter): bool
    {
        return Parameter::of($parameter)
            ->type->allowsInstanceOf($this->context);
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(\ReflectionParameter $parameter): object
    {
        return $this->context;
    }
}