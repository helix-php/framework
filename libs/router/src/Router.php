<?php

declare(strict_types=1);

namespace Helix\Router;

use FastRoute\Dispatcher;
use Helix\Contracts\Http\Method\MethodInterface;
use Helix\Contracts\Router\MatchedRouteInterface;
use Helix\Contracts\Router\RegistrarInterface;
use Helix\Contracts\Router\RepositoryInterface;
use Helix\Contracts\Router\RouteInterface;
use Helix\Contracts\Router\RouterInterface;
use Helix\Http\Method\Method;
use Helix\Router\Exception\BadRouteDefinitionException;
use Helix\Router\Exception\RouteMatchingException;
use Helix\Router\Exception\RouteNotAllowedException;
use Helix\Router\Exception\RouteNotFoundException;
use Helix\Router\Exception\RouterException;
use Helix\Router\Internal\Compiler;
use Helix\Router\Internal\Normalizer;
use Helix\Router\Reader\AttributeReader;
use Helix\Router\Reader\ReaderInterface;
use Psr\Http\Message\ServerRequestInterface;

class Router implements RegistrarInterface, RepositoryInterface, RouterInterface
{
    /**
     * @var int
     */
    private const INFO_STATUS = 0x00;

    /**
     * @var int
     */
    private const INFO_HANDLER = 0x01;

    /**
     * @var int
     */
    private const INFO_VARS = 0x02;

    /**
     * @var list<Route>
     */
    private array $routes = [];

    private Compiler $compiler;

    private ?Dispatcher $dispatcher = null;

    /**
     * @var \Closure(mixed=):mixed
     */
    private \Closure $defaultHandler;

    public function __construct(
        private readonly ReaderInterface $reader = new AttributeReader(),
    ) {
        $this->compiler = new Compiler();
        $this->defaultHandler = (static fn(): mixed => null);
    }

    /**
     * {@inheritDoc}
     * @throws RouterException
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress ArgumentTypeCoercion
     * @psalm-suppress MixedArrayOffset
     */
    public function match(ServerRequestInterface $request): MatchedRouteInterface
    {
        $this->compileIfNotCompiled();

        $uri = $request->getUri();
        $path = Normalizer::path($uri->getPath());

        /** @var array{int, mixed, array<string>} $result */
        $result = $this->dispatcher->dispatch($request->getMethod(), $path);

        return match ($result[self::INFO_STATUS]) {
            Dispatcher::FOUND => new MatchedRoute(
                $this->routes[$result[self::INFO_HANDLER]],
                $request,
                $result[self::INFO_VARS]
            ),
            Dispatcher::NOT_FOUND => throw new RouteNotFoundException($request, 'Route Not Found'),
            Dispatcher::METHOD_NOT_ALLOWED => throw new RouteNotAllowedException($request, 'Method Not Allowed'),
            default => throw new RouteMatchingException($request, 'Internal Router Error'),
        };
    }

    /**
     * @param MethodInterface $method
     * @param non-empty-string $path
     * @param mixed $handler
     * @return Route
     */
    public function make(MethodInterface $method, string $path, mixed $handler = null): Route
    {
        $this->add($route = new Route($path, $handler ?? $this->defaultHandler, $method));

        return $route;
    }

    /**
     * {@inheritDoc}
     */
    public function add(RouteInterface $route, RouteInterface ...$routes): self
    {
        $this->clear();

        foreach ([$route, ...$routes] as $current) {
            $this->routes[] = $current;
        }

        return $this;
    }

    /**
     * @param non-empty-string $path
     * @param mixed $handler
     * @return Route
     */
    public function connect(string $path, mixed $handler = null): Route
    {
        return $this->make(Method::CONNECT, $path, $handler);
    }

    /**
     * @param non-empty-string $path
     * @param mixed $handler
     * @return Route
     */
    public function delete(string $path, mixed $handler = null): Route
    {
        return $this->make(Method::DELETE, $path, $handler);
    }

    /**
     * @param non-empty-string $path
     * @param mixed $handler
     * @return Route
     */
    public function get(string $path, mixed $handler = null): Route
    {
        return $this->make(Method::GET, $path, $handler);
    }

    /**
     * @param non-empty-string $path
     * @param mixed $handler
     * @return Route
     */
    public function head(string $path, mixed $handler = null): Route
    {
        return $this->make(Method::HEAD, $path, $handler);
    }

    /**
     * @param non-empty-string $path
     * @param mixed $handler
     * @return Route
     */
    public function options(string $path, mixed $handler = null): Route
    {
        return $this->make(Method::OPTIONS, $path, $handler);
    }

    /**
     * @param non-empty-string $path
     * @param mixed $handler
     * @return Route
     */
    public function post(string $path, mixed $handler = null): Route
    {
        return $this->make(Method::POST, $path, $handler);
    }

    /**
     * @param non-empty-string $path
     * @param mixed $handler
     * @return Route
     */
    public function put(string $path, mixed $handler = null): Route
    {
        return $this->make(Method::PUT, $path, $handler);
    }

    /**
     * @param non-empty-string $path
     * @param mixed $handler
     * @return Route
     */
    public function trace(string $path, mixed $handler = null): Route
    {
        return $this->make(Method::TRACE, $path, $handler);
    }

    /**
     * @param non-empty-string $path
     * @param mixed $handler
     * @return Route
     */
    public function patch(string $path, mixed $handler = null): Route
    {
        return $this->make(Method::PATCH, $path, $handler);
    }

    /**
     * @param non-empty-string $path
     * @param mixed $handler
     * @return Group
     */
    public function any(string $path, mixed $handler = null): Group
    {
        return $this->oneOf(Method::cases(), $path, $handler);
    }

    /**
     * @param iterable<MethodInterface> $methods
     * @param non-empty-string $path
     * @param mixed $handler
     * @return Group
     */
    public function oneOf(iterable $methods, string $path, mixed $handler = null): Group
    {
        return $this->group(static function (self $router) use ($methods, $path, $handler): void {
            foreach ($methods as $method) {
                $router->make($method, $path, $handler);
            }

            if ($router->count() === 0) {
                $router->oneOf(Method::cases(), $path, $handler);
            }
        });
    }

    /**
     * @param null|callable(Router):void $group
     * @return Group
     */
    public function group(callable|null $group = null): Group
    {
        $child = new self($this->reader);

        if ($group !== null) {
            $group($child);
        }

        try {
            return new Group($child->routes);
        } finally {
            foreach ($child->routes as $route) {
                $this->add($route);
            }
        }
    }

    /**
     * @param class-string $class
     * @throws BadRouteDefinitionException
     */
    public function import(string $class): Group
    {
        $routes = [...$this->reader->read($class)];

        foreach ($routes as $route) {
            $this->add($route);
        }

        return new Group($routes);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->routes);
    }

    /**
     * @return int<0, max>
     */
    public function count(): int
    {
        return \count($this->routes);
    }

    public function find(string $name): ?RouteInterface
    {
        foreach ($this->routes as $route) {
            if ($route->getName() === $name) {
                return $route;
            }
        }

        return null;
    }

    private function clear(): void
    {
        $this->dispatcher = null;
    }

    /**
     * @throws BadRouteDefinitionException
     */
    private function compileIfNotCompiled(): void
    {
        if ($this->dispatcher === null) {
            $this->dispatcher = $this->compiler->compile($this->routes);
        }
    }
}
