<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Contracts\Router;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

interface MatchedRouteInterface extends RouteInterface
{
    /**
     * @return array<non-empty-string, string>
     */
    public function getArguments(): array;

    /**
     * @return UriInterface
     */
    public function getUri(): UriInterface;

    /**
     * @return ServerRequestInterface
     */
    public function getServerRequest(): ServerRequestInterface;

    /**
     * @return RouteInterface
     */
    public function getRoute(): RouteInterface;
}
