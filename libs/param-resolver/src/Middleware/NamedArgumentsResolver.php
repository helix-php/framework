<?php

declare(strict_types=1);

namespace Helix\ParamResolver\Middleware;

use Helix\Contracts\ParamResolver\ResolverInterface;

/**
 * Resolves parameters by their names.
 *
 * ```php
 * $resolver = new StatelessResolver([
 *      new NamedArgumentsResolver(['name' => 'value'])
 * ]);
 *
 * $resolver->resolve(function ($name) {});
 * // Given [0 => string(5) "value"]
 * ```
 *
 * Note that this resolver does not check argument signatures:
 * ```php
 * // Expected "int $name":
 * $resolver->resolve(function (int $name) {});
 *
 * // But given [0 => string(5) "value"]
 * ```
 */
final class NamedArgumentsResolver extends Middleware
{
    /**
     * @param array<non-empty-string, mixed> $arguments
     */
    public function __construct(
        private readonly array $arguments,
    ) {}

    /**
     * {@inheritDoc}
     */
    public function process(\ReflectionParameter $parameter, ResolverInterface $resolver): mixed
    {
        if (isset($this->arguments[$parameter->getName()])) {
            return $this->arguments[$parameter->getName()];
        }

        return $resolver->handle($parameter);
    }
}
