<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\ParamResolver\Resolver;

use Helix\ParamResolver\Metadata\ParameterInterface;
use Helix\ParamResolver\Metadata\Type\TerminalTypeInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class ContainerResolver extends UnionResolver
{
    /**
     * @param ContainerInterface $container
     */
    public function __construct(
        private readonly ContainerInterface $container,
    ) {
    }

    /**
     * @param ParameterInterface $param
     * @param TerminalTypeInterface $type
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function try(ParameterInterface $param, TerminalTypeInterface $type): mixed
    {
        if ($this->container->has($type->getName())) {
            return $this->container->get($type->getName());
        }

        return null;
    }
}
