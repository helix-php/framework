<?php

declare(strict_types=1);

namespace Helix\Contracts\Router\Exception;

use Psr\Http\Message\ServerRequestInterface;

interface RouteMatchingExceptionInterface extends RouterExceptionInterface
{
    /**
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface;
}
