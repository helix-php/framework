<?php

declare(strict_types=1);

namespace Helix\Router;

use Helix\Contracts\Http\Method\MethodInterface;
use Helix\Contracts\Router\MatchedRouteInterface;
use Helix\Contracts\Router\RouteInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

class MatchedRoute implements MatchedRouteInterface, ProvidesMiddlewareInterface, ProvidesResolversInterface
{
    /**
     * @param RouteInterface $route
     * @param ServerRequestInterface $request
     * @param array<string, mixed> $arguments
     */
    public function __construct(
        private readonly RouteInterface $route,
        private readonly ServerRequestInterface $request,
        private readonly array $arguments = []
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getPath(): string
    {
        return $this->route->getPath();
    }

    /**
     * {@inheritDoc}
     */
    public function getUri(): UriInterface
    {
        return $this->request->getUri();
    }

    /**
     * {@inheritDoc}
     */
    public function getServerRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * {@inheritDoc}
     */
    public function getHandler(): mixed
    {
        return $this->route->getHandler();
    }

    /**
     * {@inheritDoc}
     */
    public function getMethod(): MethodInterface
    {
        return $this->route->getMethod();
    }

    /**
     * {@inheritDoc}
     */
    public function getParameters(): array
    {
        return $this->route->getParameters();
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): ?string
    {
        return $this->route->getName();
    }

    /**
     * {@inheritDoc}
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * {@inheritDoc}
     */
    public function getDefinition(): RouteInterface
    {
        return $this->route;
    }

    /**
     * {@inheritDoc}
     */
    public function getMiddleware(): iterable
    {
        if ($this->route instanceof ProvidesMiddlewareInterface) {
            return $this->route->getMiddleware();
        }

        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getResolvers(): iterable
    {
        if ($this->route instanceof ProvidesResolversInterface) {
            return $this->route->getResolvers();
        }

        return [];
    }
}
