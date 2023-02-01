<?php

declare(strict_types=1);

namespace Helix\Router\Generator;

use Helix\Router\Generator\Exception\RouteGeneratorExceptionInterface;
use Psr\Http\Message\UriInterface;

interface GeneratorInterface
{
    /**
     * @param non-empty-string $route
     * @param array<string, string> $args
     * @return UriInterface
     * @throws RouteGeneratorExceptionInterface
     */
    public function generate(string $route, array $args = []): UriInterface;
}
