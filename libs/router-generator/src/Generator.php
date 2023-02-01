<?php

declare(strict_types=1);

namespace Helix\Router\Generator;

use Helix\Contracts\Router\RepositoryInterface;
use Helix\Router\Generator\Exception\InvalidRouteNameException;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class Generator implements GeneratorInterface
{
    /**
     * @param UriFactoryInterface $uris
     * @param RepositoryInterface $routes
     */
    public function __construct(
        private readonly UriFactoryInterface $uris,
        private readonly RepositoryInterface $routes,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function generate(string $route, iterable $args = []): UriInterface
    {
        $declaration = $this->routes->find($route) ?? throw InvalidRouteNameException::notFound($route);

        /**
         * @var string $result
         * @psalm-suppress MixedArgumentTypeCoercion
         */
        $result = \preg_replace_callback('/{(.+?)(:.+?)?}/isum', $this->replace($args), $declaration->getPath());

        return $this->uris->createUri($result);
    }

    /**
     * @param array<string, string> $arguments
     * @return \Closure(array{1:string}): string
     */
    private function replace(array $arguments): \Closure
    {
        return static function (array $matches) use ($arguments): string {
            return $arguments[$matches[1]] ?? '';
        };
    }
}
