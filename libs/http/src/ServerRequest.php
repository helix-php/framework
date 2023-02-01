<?php

declare(strict_types=1);

namespace Helix\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;

class ServerRequest extends Request implements ServerRequestInterface
{
    /**
     * @param ServerRequestInterface $request
     */
    public function __construct(
        ServerRequestInterface $request,
    ) {
        parent::__construct($request);
    }

    /**
     * {@inheritDoc}
     *
     * @return array<non-empty-string, mixed>
     */
    public function getServerParams(): array
    {
        return $this->message->getServerParams();
    }

    /**
     * {@inheritDoc}
     *
     * @return array<non-empty-string, mixed>
     */
    public function getCookieParams(): array
    {
        return $this->message->getCookieParams();
    }

    /**
     * {@inheritDoc}
     *
     * @param array<non-empty-string, mixed> $cookies
     *
     * @return $this
     */
    public function withCookieParams(array $cookies): self
    {
        $self = clone $this;
        $self->message = $self->message->withCookieParams($cookies);

        return $self;
    }

    /**
     * {@inheritDoc}
     *
     * @return array<non-empty-string, string|list<string>>
     */
    public function getQueryParams(): array
    {
        return $this->message->getQueryParams();
    }

    /**
     * {@inheritDoc}
     *
     * @param array<non-empty-string, string|list<string>> $query
     *
     * @return $this
     */
    public function withQueryParams(array $query): self
    {
        $self = clone $this;
        $self->message = $self->message->withQueryParams($query);

        return $self;
    }

    /**
     * {@inheritDoc}
     *
     * @return list<UploadedFileInterface>
     */
    public function getUploadedFiles(): array
    {
        return $this->message->getUploadedFiles();
    }

    /**
     * {@inheritDoc}
     *
     * @param list<UploadedFileInterface> $uploadedFiles
     *
     * @return $this
     */
    public function withUploadedFiles(array $uploadedFiles): self
    {
        $self = clone $this;
        $self->message = $self->message->withUploadedFiles($uploadedFiles);

        return $self;
    }

    /**
     * {@inheritDoc}
     *
     * @return array|object|null
     */
    public function getParsedBody(): array|object|null
    {
        return $this->message->getParsedBody();
    }

    /**
     * {@inheritDoc}
     *
     * @param array|object|null $data
     *
     * @return $this
     */
    public function withParsedBody($data): self
    {
        $self = clone $this;
        $self->message = $self->message->withParsedBody($data);

        return $self;
    }

    /**
     * {@inheritDoc}
     *
     * @return list<mixed>
     */
    public function getAttributes(): array
    {
        return $this->message->getAttributes();
    }

    /**
     * {@inheritDoc}
     *
     * @param non-empty-string $name
     * @param mixed $default
     *
     * @return mixed
     */
    public function getAttribute($name, $default = null): mixed
    {
        return $this->message->getAttribute($name, $default);
    }

    /**
     * {@inheritDoc}
     *
     * @param non-empty-string $name
     * @param mixed $value
     *
     * @return $this
     */
    public function withAttribute($name, $value): self
    {
        $self = clone $this;
        $self->message = $self->message->withAttribute($name, $value);

        return $self;
    }

    /**
     * {@inheritDoc}
     *
     * @param non-empty-string $name
     *
     * @return $this
     */
    public function withoutAttribute($name): self
    {
        $self = clone $this;
        $self->message = $self->message->withoutAttribute($name);

        return $self;
    }
}
