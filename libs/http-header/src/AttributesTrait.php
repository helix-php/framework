<?php

declare(strict_types=1);

namespace Helix\Http\Header;

use Helix\Contracts\Http\Header\Attribute\AttributeInterface;
use Helix\Contracts\Http\Header\Attribute\FlagInterface;
use Helix\Contracts\Http\Header\HeaderInterface;

/**
 * @mixin HeaderInterface
 * @psalm-require-implements HeaderInterface
 */
trait AttributesTrait
{
    /**
     * @var array<non-empty-lowercase-string, FlagInterface>
     */
    protected array $attributes = [];

    /**
     * @return array<array-key, FlagInterface>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * {@inheritDoc}
     */
    public function findAttribute(string $name): ?FlagInterface
    {
        return $this->attributes[\strtolower($name)] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function hasAttribute(string $name): bool
    {
        return isset($this->attributes[\strtolower($name)]);
    }

    /**
     * @param list<FlagInterface> $attributes
     *
     * @return void
     */
    public function setAttributes(iterable $attributes): void
    {
        $this->attributes = [];
        $this->addAttributes($attributes);
    }

    /**
     * @param list<FlagInterface> $attributes
     *
     * @return void
     */
    public function addAttributes(iterable $attributes): void
    {
        foreach ($attributes as $attr) {
            if (!$attr instanceof FlagInterface) {
                $message = 'Header attr must be an instance of %s or %s, but %s given';
                $message = \sprintf($message, AttributeInterface::class, FlagInterface::class, \get_debug_type($attr));

                throw new \InvalidArgumentException($message);
            }

            $this->attributes[\strtolower($attr->getName())] = $attr;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function withAttributes(iterable $attributes): self
    {
        $self = clone $this;
        $self->setAttributes($attributes);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function withAddedAttributes(iterable $attributes): self
    {
        $self = clone $this;
        $self->addAttributes($attributes);

        return $self;
    }
}
