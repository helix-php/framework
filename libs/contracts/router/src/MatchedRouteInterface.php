<?php

declare(strict_types=1);

namespace Helix\Contracts\Router;

use Psr\Http\Message\ServerRequestInterface;

interface MatchedRouteInterface extends RouteInterface
{
    /**
     * @return array<non-empty-string, string>
     */
    public function getArguments(): array;

    /**
     * @return ServerRequestInterface
     */
    public function getServerRequest(): ServerRequestInterface;

    /**
     * @return RouteInterface
     */
    public function getDefinition(): RouteInterface;
}
