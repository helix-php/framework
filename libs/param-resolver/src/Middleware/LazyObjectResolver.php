<?php

declare(strict_types=1);

namespace Helix\ParamResolver\Middleware;

use Helix\Contracts\ParamResolver\ResolverInterface;
use Helix\ParamInfo\Type;

/**
 * Returns (resolves) a specific object from callback if the parameter's
 * signature matches the passed class.
 *
 * Given:
 *  - $class: ExampleObject::class
 *  - $resolver: fn() => ExampleObject implements ExampleObjectInterface
 *
 * Options:
 *  - [✓] function(ExampleObject)
 *  - [✓] function(ExampleObjectInterface)
 *  - [✓] function(ExampleObject | AnotherObject)
 *  - [✓] function(ExampleObjectInterface | AnotherObject)
 *  - [✓] function(ExampleObject & ExampleObjectInterface)
 * Except:
 *  - [×] function(object)
 *
 * @template T of object
 */
final class LazyObjectResolver extends Middleware
{
    /**
     * @param class-string<T> $class
     * @param \Closure():T $resolver
     */
    public function __construct(
        private readonly string $class,
        private readonly \Closure $resolver,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function process(\ReflectionParameter $parameter, ResolverInterface $resolver): mixed
    {
        $supports = Type::fromParameter($parameter)
            ->allowsSubclassOf($this->class);

        if ($supports) {
            return ($this->resolver)();
        }

        return $resolver->handle($parameter);
    }
}
