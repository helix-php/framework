<?php

declare(strict_types=1);

namespace Helix\Router\Attribute;

use Helix\Http\Method\Method;
use Psr\Http\Server\MiddlewareInterface;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_METHOD)]
final class Route
{
    /**
     * @param non-empty-string $path
     * @param Method $method
     * @param non-empty-string|null $as
     * @param array<non-empty-string, non-empty-string> $where
     * @param array<non-empty-string|class-string|MiddlewareInterface> $middleware
     */
    public function __construct(
        public readonly string $path,
        public readonly Method $method = Method::GET,
        public readonly ?string $as = null,
        public readonly array $where = [],
        public readonly array $middleware = [],
    ) {
    }
}
