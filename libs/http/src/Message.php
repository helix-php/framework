<?php

declare(strict_types=1);

namespace Helix\Http;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

abstract class Message implements MessageInterface
{
    /**
     * @param MessageInterface $message
     */
    public function __construct(
        protected MessageInterface $message,
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * @return numeric-string
     */
    public function getProtocolVersion(): string
    {
        return $this->message->getProtocolVersion();
    }

    /**
     * {@inheritDoc}
     *
     * @param numeric-string $version
     *
     * @return $this
     */
    public function withProtocolVersion($version): self
    {
        assert(\is_string($version) && $version);

        $self = clone $this;
        $self->message = $self->message->withProtocolVersion($version);

        return $self;
    }

    /**
     * {@inheritDoc}
     *
     * @return array<non-empty-string, list<string>>
     */
    public function getHeaders(): array
    {
        return $this->message->getHeaders();
    }

    /**
     * {@inheritDoc}
     *
     * @param non-empty-string $name
     *
     * @return bool
     */
    public function hasHeader($name): bool
    {
        assert(\is_string($name) && $name);

        return $this->message->hasHeader($name);
    }

    /**
     * {@inheritDoc}
     *
     * @param non-empty-string $name
     *
     * @return list<string>
     */
    public function getHeader($name): array
    {
        assert(\is_string($name) && $name);

        return $this->message->getHeader($name);
    }

    /**
     * {@inheritDoc}
     *
     * @param non-empty-string $name
     *
     * @return string
     */
    public function getHeaderLine($name): string
    {
        assert(\is_string($name) && $name);

        return $this->message->getHeaderLine($name);
    }

    /**
     * {@inheritDoc}
     *
     * @param non-empty-string $name
     * @param string|list<string> $value
     *
     * @return $this
     */
    public function withHeader($name, $value): self
    {
        assert(\is_string($name) && $name);
        assert(\is_string($value) || \is_array($value));

        $self = clone $this;
        $self->message = $self->message->withHeader($name, $value);

        return $self;
    }

    /**
     * {@inheritDoc}
     *
     * @param non-empty-string $name
     * @param string|list<string> $value
     *
     * @return $this
     */
    public function withAddedHeader($name, $value): self
    {
        assert(\is_string($name) && $name);
        assert(\is_string($value) || \is_array($value));

        $self = clone $this;
        $self->message = $self->message->withAddedHeader($name, $value);

        return $self;
    }

    /**
     * {@inheritDoc}
     *
     * @param non-empty-string $name
     *
     * @return $this
     */
    public function withoutHeader($name): self
    {
        assert(\is_string($name) && $name);

        $self = clone $this;
        $self->message = $self->message->withoutHeader($name);

        return $self;
    }

    /**
     * {@inheritDoc}
     *
     * @return StreamInterface
     */
    public function getBody(): StreamInterface
    {
        return $this->message->getBody();
    }

    /**
     * {@inheritDoc}
     *
     * @param StreamInterface $body
     *
     * @return $this
     */
    public function withBody(StreamInterface $body): self
    {
        $self = clone $this;
        $self->message = $self->message->withBody($body);

        return $self;
    }
}
