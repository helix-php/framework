<?php

declare(strict_types=1);

namespace Helix\Contracts\Router;

use Helix\Contracts\Router\Exception\NotAllowedExceptionInterface;
use Helix\Contracts\Router\Exception\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;

interface RouterInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return MatchedRouteInterface
     *
     * @throws NotAllowedExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function match(ServerRequestInterface $request): MatchedRouteInterface;
}
