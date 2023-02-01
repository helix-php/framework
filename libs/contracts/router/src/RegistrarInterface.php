<?php

declare(strict_types=1);

namespace Helix\Contracts\Router;

interface RegistrarInterface
{
    /**
     * @param RouteInterface $route
     * @param RouteInterface ...$routes
     * @return $this
     */
    public function add(RouteInterface $route, RouteInterface ...$routes): self;
}
