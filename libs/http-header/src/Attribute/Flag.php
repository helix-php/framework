<?php

declare(strict_types=1);

namespace Helix\Http\Header\Attribute;

use Helix\Contracts\Http\Header\Attribute\FlagInterface;

class Flag implements FlagInterface
{
    /**
     * @var non-empty-string
     */
    protected string $name;

    /**
     * @param non-empty-string $name
     */
    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * @param mixed $name
     * @return self|null
     */
    public static function createOrNull(mixed $name): ?self
    {
        $name = self::nameToStringOrNull($name);

        return $name !== null ? new self($name) : null;
    }

    /**
     * @param mixed $name
     * @param bool $expr
     * @return self|null
     */
    public static function createIf(mixed $name, bool $expr): ?self
    {
        return $expr ? self::createOrNull($name) : null;
    }

    /**
     * @param mixed $name
     * @return non-empty-string|null
     */
    protected static function nameToStringOrNull(mixed $name): ?string
    {
        if (!\is_scalar($name) && !$name instanceof \Stringable) {
            return null;
        }

        $string = (string)$name;

        return $string !== '' ? $string : null;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param non-empty-string $name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        /** @psalm-suppress TypeDoesNotContainType */
        if ($name === '') {
            throw new \InvalidArgumentException('Name of the attribute cannot be empty');
        }

        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function withName(string $name): self
    {
        $self = clone $this;
        $self->setName($name);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return $this->name;
    }
}
