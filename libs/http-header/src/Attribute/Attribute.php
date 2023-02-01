<?php

declare(strict_types=1);

namespace Helix\Http\Header\Attribute;

use Helix\Contracts\Http\Header\Attribute\AttributeInterface;

class Attribute extends Flag implements AttributeInterface
{
    /**
     * Key-value attribute's delimiter.
     *
     * @var non-empty-string
     */
    final public const DELIMITER = '=';

    /**
     * @var string|\Stringable
     */
    protected string|\Stringable $value;

    /**
     * @var non-empty-string
     */
    protected string $delimiter = self::DELIMITER;

    /**
     * @param non-empty-string $name
     * @param string|\Stringable $value
     * @param non-empty-string $delimiter
     */
    public function __construct(
        string $name,
        string|\Stringable $value,
        string $delimiter = self::DELIMITER,
    ) {
        parent::__construct($name);

        $this->setValue($value);
        $this->setDelimiter($delimiter);
    }

    /**
     * @param mixed $name
     * @param mixed|null $value
     * @return static|null
     */
    public static function createOrNull(mixed $name, mixed $value = null): ?self
    {
        $name = self::nameToStringOrNull($name);

        return match (true) {
            $value instanceof \Stringable => new self($name, $value),
            \is_scalar($value) => new self($name, (string)$value),
            default => null,
        };
    }

    /**
     * @return non-empty-string
     */
    public function getDelimiter(): string
    {
        return $this->delimiter;
    }

    /**
     * @param non-empty-string $delimiter
     *
     * @return void
     */
    public function setDelimiter(string $delimiter): void
    {
        /** @psalm-suppress TypeDoesNotContainType */
        if ($delimiter === '') {
            throw new \InvalidArgumentException('Attribute name-value delimiter cannot be empty');
        }

        $this->delimiter = $delimiter;
    }

    /**
     * @psalm-immutable
     *
     * @param non-empty-string $delimiter
     *
     * @return self
     */
    public function withDelimiter(string $delimiter): self
    {
        $self = clone $this;
        $self->setDelimiter($delimiter);

        return $self;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return (string)$this->value;
    }

    /**
     * @param string|\Stringable $value
     *
     * @return void
     */
    public function setValue(string|\Stringable $value): void
    {
        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function withValue(string|\Stringable $value): self
    {
        $self = clone $this;
        $self->value = $value;

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return $this->name . $this->delimiter . $this->value;
    }
}
