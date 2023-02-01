<?php

declare(strict_types=1);

namespace Helix\Http;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriInterface;

class RequestFactory implements RequestFactoryInterface
{
    public function __construct(
        protected RequestFactoryInterface $factory,
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * @param non-empty-string $method
     * @param string|UriInterface $uri
     *
     * @return Request
     */
    public function createRequest(string $method, $uri): Request
    {
        $request = $this->factory->createRequest($method, $uri);

        return new Request($request);
    }
}
