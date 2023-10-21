<?php

declare(strict_types=1);

namespace Helix\Router;

use Helix\Router\Internal\Normalizer;

/**
 * @template-implements \IteratorAggregate<array-key, Route>
 */
final class Group implements GroupInterface, \IteratorAggregate
{
    /**
     * @var list<Route>
     */
    private array $routes;

    /**
     * @param iterable<array-key, Route> $routes
     */
    public function __construct(iterable $routes)
    {
        $this->routes = [...$routes];
    }

    /**
     * @param callable(Route):void $each
     */
    public function each(callable $each): self
    {
        foreach ($this->routes as $route) {
            $each($route);
        }

        return $this;
    }

    public function where(string $name, string $pattern): self
    {
        return $this->each(static fn(Route $route): Route => $route->where($name, $pattern));
    }

    public function through(mixed ...$middleware): self
    {
        return $this->each(static fn(Route $route): Route => $route->through(...$middleware));
    }

    public function then(mixed $action): self
    {
        return $this->each(static fn(Route $route): Route => $route->then($action));
    }

    public function prefix(string $prefix, bool $concat = false): self
    {
        return $this->each(static fn(Route $route) => $route->located(
            Normalizer::chunks([$prefix, $route->getPath()], $concat)
        ));
    }

    public function suffix(string $suffix, bool $concat = true): self
    {
        return $this->each(static fn(Route $route) => $route->located(
            Normalizer::chunks([$route->getPath(), $suffix], $concat)
        ));
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
}
