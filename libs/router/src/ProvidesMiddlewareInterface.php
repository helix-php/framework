<?php

declare(strict_types=1);

namespace Helix\Router;

use Psr\Http\Server\MiddlewareInterface;

interface ProvidesMiddlewareInterface
{
    /**
     * @return iterable<non-empty-string|class-string|MiddlewareInterface>
     */
    public function getMiddleware(): iterable;
}
