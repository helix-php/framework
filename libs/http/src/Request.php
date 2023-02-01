<?php

declare(strict_types=1);

namespace Helix\Http;

use Helix\Contracts\Http\Method\MethodInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{
    /**
     * @param RequestInterface $request
     */
    public function __construct(
        RequestInterface $request,
    ) {
        parent::__construct($request);
    }

    /**
     * {@inheritDoc}
     *
     * @return non-empty-string
     */
    public function getRequestTarget(): string
    {
        return $this->message->getRequestTarget();
    }

    /**
     * {@inheritDoc}
     *
     * @param non-empty-string $requestTarget
     *
     * @return $this
     */
    public function withRequestTarget($requestTarget): self
    {
        $self = clone $this;
        $self->message = $self->message->withRequestTarget($requestTarget);

        return $self;
    }

    /**
     * {@inheritDoc}
     *
     * @return non-empty-string
     */
    public function getMethod(): string
    {
        return $this->message->getMethod();
    }

    /**
     * {@inheritDoc}
     *
     * @param non-empty-string|MethodInterface $method
     *
     * @return $this
     */
    public function withMethod($method): self
    {
        if ($method instanceof MethodInterface) {
            $method = $method->getName();
        }

        $self = clone $this;
        $self->message = $self->message->withMethod($method);

        return $self;
    }

    /**
     * {@inheritDoc}
     *
     * @return UriInterface
     */
    public function getUri(): UriInterface
    {
        return $this->message->getUri();
    }

    /**
     * {@inheritDoc}
     *
     * @param UriInterface $uri
     * @param bool $preserveHost
     *
     * @return $this
     */
    public function withUri(UriInterface $uri, $preserveHost = false): self
    {
        $self = clone $this;
        $self->message = $self->message->withUri($uri, $preserveHost);

        return $self;
    }
}
