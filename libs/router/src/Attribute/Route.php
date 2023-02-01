<?php

declare(strict_types=1);

namespace Helix\Router\Attribute;

use Helix\Contracts\Http\Method\MethodInterface;
use Helix\Contracts\ParamResolver\ResolverInterface;
use Helix\Http\Method\Method;
use Psr\Http\Server\MiddlewareInterface;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_METHOD)]
final class Route
{
    /**
     * @var MethodInterface
     */
    public readonly MethodInterface $method;

    /**
     * @param non-empty-string $path
     * @param MethodInterface|non-empty-string $method
     * @param non-empty-string|null $as
     * @param array<non-empty-string, non-empty-string> $where
     * @param array<non-empty-string|class-string|MiddlewareInterface> $middleware
     * @param array<non-empty-string|class-string|ResolverInterface> $resolvers
     */
    public function __construct(
        public readonly string $path,
        MethodInterface|string $method = Method::GET,
        public readonly ?string $as = null,
        public readonly array $where = [],
        public readonly array $middleware = [],
        public readonly array $resolvers = [],
    ) {
        if (\is_string($method)) {
            $method = Method::parse($method);
        }

        $this->method = $method;
    }
}
