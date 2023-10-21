<?php

declare(strict_types=1);

namespace Helix\Router\Reader;

use Helix\Contracts\Router\RouteInterface;
use Helix\Router\Attribute\Group;
use Helix\Router\Attribute\Route as RouteAttribute;
use Helix\Router\Exception\BadRouteDefinitionException;
use Helix\Router\Internal\Normalizer;
use Helix\Router\Route;

class AttributeReader implements ReaderInterface
{
    public function read(string $class): iterable
    {
        try {
            $reflection = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            throw new BadRouteDefinitionException($e->getMessage(), (int)$e->getCode(), $e);
        }

        yield from $this->methods($reflection, $this->group($reflection));
    }

    /**
     * @return iterable<RouteInterface>
     * @throws BadRouteDefinitionException
     */
    private function methods(\ReflectionClass $class, Group $group): iterable
    {
        foreach ($class->getMethods() as $method) {
            yield from $this->method($class, $method, $group);
        }
    }

    /**
     * @return iterable<RouteInterface>
     * @throws BadRouteDefinitionException
     */
    private function method(\ReflectionClass $class, \ReflectionMethod $method, Group $group): iterable
    {
        $attributes = $method->getAttributes(RouteAttribute::class, \ReflectionAttribute::IS_INSTANCEOF);

        if ($attributes === []) {
            return [];
        }

        if (!$method->isPublic()) {
            $message = \vsprintf('Route http-method %s::%s must be public', [
                $class->getName(),
                $method->getName(),
            ]);

            throw new BadRouteDefinitionException($message);
        }

        foreach ($attributes as $attribute) {
            yield $this->make($this->handler($class, $method), $group, $attribute->newInstance());
        }
    }

    /**
     * @param non-empty-string|callable $handler
     */
    private function make(string|callable $handler, Group $group, RouteAttribute $route): RouteInterface
    {
        $result = new Route($this->path($group, $route), $handler, $route->method);

        foreach ($group->where as $name => $pcre) {
            $result->where($name, $pcre);
        }

        foreach ($route->where as $name => $pcre) {
            $result->where($name, $pcre);
        }

        if ($group->resolvers !== []) {
            $result->using(...$group->resolvers);
        }

        if ($route->resolvers !== []) {
            $result->using(...$route->resolvers);
        }

        if ($group->middleware !== []) {
            $result->through(...$group->middleware);
        }

        if ($route->middleware !== []) {
            $result->through(...$route->middleware);
        }

        return $result->as($route->as);
    }

    /**
     * @return non-empty-string
     */
    private function path(Group $group, RouteAttribute $route): string
    {
        $prefix = $group->prefix ? Normalizer::path($group->prefix) : '';
        $suffix = $group->suffix ? Normalizer::path($group->suffix, false) : '';

        return $prefix . Normalizer::path($route->path) . $suffix;
    }

    /**
     * @return non-empty-string|callable
     */
    private function handler(\ReflectionClass $class, \ReflectionMethod $method): string|callable
    {
        if ($method->isStatic()) {
            return \Closure::fromCallable([$class->getName(), $method->getName()]);
        }

        return $class->getName() . '@' . $method->getName();
    }

    private function group(\ReflectionClass $reflection): Group
    {
        $attributes = $reflection->getAttributes(Group::class, \ReflectionAttribute::IS_INSTANCEOF);

        foreach ($attributes as $attribute) {
            return $attribute->newInstance();
        }

        return new Group();
    }
}
