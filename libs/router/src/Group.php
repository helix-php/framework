<?php

declare(strict_types=1);

namespace Helix\Router;

use Helix\Router\Internal\Normalizer;

final class Group implements GroupInterface, \IteratorAggregate
{
    /**
     * @var array<array-key, Route>
     */
    private array $routes;

    /**
     * @param list<Route> $routes
     */
    public function __construct(iterable $routes)
    {
        $this->routes = [...$routes];
    }

    /**
     * @param callable(Route):void $each
     * @return $this
     */
    public function each(callable $each): self
    {
        foreach ($this->routes as $route) {
            $each($route);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function where(string $name, string $pattern): self
    {
        return $this->each(static fn (Route $route): Route => $route->where($name, $pattern));
    }

    /**
     * {@inheritDoc}
     */
    public function through(mixed ...$middleware): self
    {
        return $this->each(static fn (Route $route): Route => $route->through(...$middleware));
    }

    /**
     * {@inheritDoc}
     */
    public function then(mixed $action): self
    {
        return $this->each(static fn (Route $route): Route => $route->then($action));
    }

    /**
     * {@inheritDoc}
     */
    public function prefix(string $prefix, bool $concat = false): self
    {
        return $this->each(static fn (Route $route) => $route->located(
            Normalizer::chunks([$prefix, $route->getPath()], $concat)
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function suffix(string $suffix, bool $concat = true): self
    {
        return $this->each(static fn (Route $route) => $route->located(
            Normalizer::chunks([$route->getPath(), $suffix], $concat)
        ));
    }

    /**
     * @return \Traversable<Route>
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->routes);
    }

    /**
     * @return positive-int|0
     */
    public function count(): int
    {
        return \count($this->routes);
    }
}
