<?php

declare(strict_types=1);

namespace Helix\ParamResolver\Middleware;

use Helix\Contracts\ParamResolver\ResolverInterface;
use Helix\ParamInfo\Type;

/**
 * Returns (resolves) a specific object if the parameter's signature
 * matches the passed object.
 *
 * Given:
 *  - $context: ExampleObject implements ExampleObjectInterface
 *
 * Options:
 *  - [✓] function(ExampleObject)
 *  - [✓] function(ExampleObjectInterface)
 *  - [✓] function(ExampleObject | AnotherObject)
 *  - [✓] function(ExampleObjectInterface | AnotherObject)
 *  - [✓] function(ExampleObject & ExampleObjectInterface)
 * Except:
 *  - [×] function(object)
 */
final class ObjectResolver extends Middleware
{
    /**
     * @param object $context
     */
    public function __construct(
        private readonly object $context,
    ) {}

    /**
     * {@inheritDoc}
     */
    public function process(\ReflectionParameter $parameter, ResolverInterface $resolver): mixed
    {
        $supports = Type::fromParameter($parameter)
            ->allowsInstanceOf($this->context);

        if ($supports) {
            return $this->context;
        }

        return $resolver->handle($parameter);
    }
}
