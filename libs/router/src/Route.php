<?php

declare(strict_types=1);

namespace Helix\Router;

use Helix\Contracts\Http\Method\MethodInterface;
use Helix\Contracts\ParamResolver\ResolverInterface;
use Helix\Contracts\Router\RouteInterface;
use Helix\Http\Method\Method;
use Helix\Router\Internal\Normalizer;
use JetBrains\PhpStorm\Language;
use Psr\Http\Server\MiddlewareInterface;

class Route implements RouteInterface, ProvidesMiddlewareInterface, ProvidesResolversInterface
{
    /**
     * @var non-empty-string|null
     */
    private ?string $name = null;

    /**
     * @var array<non-empty-string, non-empty-string>
     */
    private array $parameters = [];

    /**
     * @var non-empty-string
     */
    private string $path;

    private MethodInterface $method;

    private mixed $handler;

    /**
     * @var array<non-empty-string|class-string|MiddlewareInterface>
     */
    private array $middleware = [];

    /**
     * @var array<non-empty-string|class-string|ResolverInterface>
     */
    private array $resolvers = [];

    /**
     * @param non-empty-string $path
     * @param MethodInterface|non-empty-string $method
     */
    public function __construct(
        string $path = '/',
        mixed $handler = null,
        MethodInterface|string $method = Method::GET,
    ) {
        $this->path = Normalizer::path($path) ?: '/';

        /** @psalm-suppress MissingClosureReturnType */
        $this->handler = $handler ?? (static fn() => null);

        $this->method = $method instanceof MethodInterface ? $method : Method::parse($method);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getHandler(): mixed
    {
        return $this->handler;
    }

    public function getMethod(): MethodInterface
    {
        return $this->method;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function getResolvers(): array
    {
        return $this->resolvers;
    }

    /**
     * @param non-empty-string $path
     */
    public function located(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param MethodInterface|non-empty-string $method
     */
    public function matched(MethodInterface|string $method): self
    {
        if (\is_string($method)) {
            $method = Method::parse($method);
        }

        $this->method = $method;

        return $this;
    }

    public function then(mixed $action): self
    {
        $this->handler = $action;

        return $this;
    }

    /**
     * @param non-empty-string $name
     * @param non-empty-string $pattern
     */
    public function where(string $name, #[Language('RegExp')] string $pattern): self
    {
        assert($name !== '');
        assert($pattern !== '');

        $this->parameters[$name] = $pattern;

        return $this;
    }

    /**
     * @param non-empty-string|null $name
     */
    public function as(?string $name): self
    {
        $this->name = $name ?: null;

        return $this;
    }

    /**
     * @param non-empty-string|class-string|MiddlewareInterface $middleware
     * @param non-empty-string|class-string|MiddlewareInterface ...$other
     */
    public function through(string|MiddlewareInterface $middleware, string|MiddlewareInterface ...$middlewares): self
    {
        foreach ([$middleware, ...$middlewares] as $definition) {
            $this->middleware[] = $definition;
        }

        return $this;
    }

    /**
     * @param non-empty-string|class-string|ResolverInterface $resolver
     * @param non-empty-string|class-string|ResolverInterface ...$resolvers
     */
    public function using(string|ResolverInterface $resolver, string|ResolverInterface ...$resolvers): self
    {
        foreach ([$resolver, ...$resolvers] as $definition) {
            $this->resolvers[] = $definition;
        }

        return $this;
    }
}
