<?php

declare(strict_types=1);

namespace Helix\Router\Tests;

use Helix\Contracts\Http\Method\MethodInterface;
use Helix\Contracts\Router\RouteInterface;
use Helix\Contracts\Router\RouterInterface;
use Helix\Http\Method\Method;
use Helix\Router\Route;
use Helix\Router\Router;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * @group router
 */
abstract class TestCase extends BaseTestCase
{
    protected function route(
        string $path = '/',
        mixed $handler = 'print',
        MethodInterface|string $method = Method::GET
    ): Route {
        return new Route($path, $handler, $method);
    }

    /**
     * @param array<RouteInterface> $routes
     * @return Router
     */
    protected function router(array $routes = []): Router
    {
        $router = new Router();

        foreach ($routes as $route) {
            $router->add($route);
        }

        return $router;
    }
}
