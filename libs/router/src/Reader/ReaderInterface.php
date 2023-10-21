<?php

declare(strict_types=1);

namespace Helix\Router\Reader;

use Helix\Contracts\Router\RouteInterface;
use Helix\Router\Exception\BadRouteDefinitionException;

interface ReaderInterface
{
    /**
     * @param class-string $class
     *
     * @return iterable<array-key, RouteInterface>
     * @throws BadRouteDefinitionException
     */
    public function read(string $class): iterable;
}
