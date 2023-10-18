<?php

declare(strict_types=1);

namespace Helix\ParamResolver\Middleware;

use Helix\Contracts\ParamResolver\ResolverInterface;
use Psr\Container\ContainerInterface;

final class ContainerServiceResolver extends Middleware
{
    /**
     * @param ContainerInterface $container
     */
    public function __construct(
        private readonly ContainerInterface $container,
    ) {}

    /**
     * {@inheritDoc}
     */
    public function process(\ReflectionParameter $parameter, ResolverInterface $resolver): mixed
    {
        $type = $parameter->getType();

        if (!$type instanceof \ReflectionNamedType || $type->isBuiltin()) {
            return $resolver->handle($parameter);
        }

        if ($this->container->has($type->getName())) {
            return $this->container->get($type->getName());
        }

        return $resolver->handle($parameter);
    }
}
