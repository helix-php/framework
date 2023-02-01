<?php

declare(strict_types=1);

namespace Helix\Router\Attribute;

use Helix\Contracts\Router\RouteInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * @see RouteInterface
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class Group
{
    /**
     * @param string $prefix
     * @param string $suffix
     * @param array<non-empty-string, non-empty-string> $where
     * @param array<non-empty-string|class-string|MiddlewareInterface> $middleware
     */
    public function __construct(
        public readonly string $prefix = '',
        public readonly string $suffix = '',
        public readonly array $where = [],
        public readonly array $middleware = [],
    ) {
    }
}
