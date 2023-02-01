<?php

declare(strict_types=1);

namespace Helix\Http;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

class ServerRequestFactory implements ServerRequestFactoryInterface
{
    public function __construct(
        protected ServerRequestFactoryInterface $factory,
    ) {
    }

    /**
     * @param non-empty-string $method
     * @param string|UriInterface $uri
     * @param array $serverParams
     *
     * @return ServerRequest
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequest
    {
        $request = $this->factory->createServerRequest($method, $uri, $serverParams);

        return new ServerRequest($request);
    }
}
