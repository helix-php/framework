<?php

declare(strict_types=1);

namespace Helix\Http\Cookie;

use Helix\Contracts\Http\Cookie\CookieInterface;
use Helix\Contracts\Http\Header\Attribute\FlagInterface;
use Helix\Http\Header\Attribute\Attribute;
use Helix\Http\Header\Collection;
use Helix\Http\Header\AttributesTrait;

class Cookie implements CookieInterface
{
    use AttributesTrait;

    /**
     * List of available cookie name's chars.
     *
     * @var non-empty-string
     */
    private const NAME_AVAILABLE_CHARS = '!#$%&\'*+-.01234567'
                                       . '89ABCDEFGHIJKLMNOPQ'
                                       . 'RSTUVWXYZ^_`abcdefg'
                                       . 'hijklmnopqrstuvwxyz|~';

    /**
     * List of available cookie value's chars.
     *
     * @var non-empty-string
     */
    private const VALUE_AVAILABLE_CHARS = '!#$%&()*+-./012345678'
                                        . '9:<=>?@ABCDEFGHIJKLMN'
                                        . 'OPQRSTUVWXYZ[\]^_`abc'
                                        . 'defghijklmnopqrstuvwx'
                                        . 'yz{|}~';

    /**
     * A cookie name.
     *
     * Name can contain any US-ASCII characters (defined
     * in {@see ServerCookie::NAME_AVAILABLE_CHARS} except for: The control character,
     * space, or a tab. It also must not contain a separator characters like the
     * following: `( ) < > @ , ; : \ " / [ ] ? = { }`.
     *
     * A cookie definition begins with a name-value pair.
     *
     * @var non-empty-string
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private string $name;

    /**
     * A cookie value.
     *
     * Value can optionally be wrapped in double quotes and include any
     * US-ASCII character excluding a control character, whitespace,
     * double quotes, comma, semicolon, and backslash.
     *
     * @var string|\Stringable
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private string|\Stringable $value;

    /**
     * @param non-empty-string $name
     * @param string|\Stringable $value
     * @param iterable<FlagInterface> $attributes Additional cookie attributes
     */
    public function __construct(
        string $name,
        string|\Stringable $value = '',
        iterable $attributes = [],
    ) {
        $this->setName($name);
        $this->setValue($value);
        $this->setAttributes($attributes);
    }

    /**
     * See {@see ServerCookie::$name} for more information.
     *
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Updates the name in the {@see ServerCookie} object.
     *
     * > Note that this method changes the value of a field on an
     * > existing object. For an immutable implementation, use
     * > the {@see ServerCookie::withName()} method.
     *
     * @param non-empty-string $name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        /** @psalm-suppress TypeDoesNotContainType: Type validation */
        if ($name === '') {
            throw new \InvalidArgumentException('Cookie name cannot be empty');
        }

        if (($offset = \strspn($name, self::NAME_AVAILABLE_CHARS)) !== \strlen($name)) {
            $message = 'The cookie name "%s" contains invalid character (%s) at position %d';
            $message = \sprintf($message, $name, $name[$offset], $offset + 1);

            throw new \InvalidArgumentException($message);
        }

        $this->name = $name;
    }

    /**
     * Immutable equivalent of the {@see ServerCookie::setName()} method.
     *
     * @psalm-immutable
     *
     * @param non-empty-string $name
     *
     * @return $this
     */
    public function withName(string $name): self
    {
        $self = clone $this;
        $self->setName($name);

        return $self;
    }

    /**
     * See {@see ServerCookie::$value} for more information.
     *
     * @return string|\Stringable
     */
    public function getValue(): string|\Stringable
    {
        return $this->value;
    }

    /**
     * Updates the value in the {@see ServerCookie} object.
     *
     * > Note that this method changes the value of a field on an
     * > existing object. For an immutable implementation, use
     * > the {@see ServerCookie::withValue()} method.
     *
     * @param string|\Stringable $value
     *
     * @return void
     */
    public function setValue(string|\Stringable $value): void
    {
        // Stringable objects cannot be validated
        if ($value instanceof \Stringable) {
            $this->value = $value;

            return;
        }

        if (($offset = \strspn($value, self::NAME_AVAILABLE_CHARS)) !== \strlen($value)) {
            $message = 'The cookie value "%s" contains invalid character (%s) at position %d';
            $message = \sprintf($message, $value, $value[$offset], $offset + 1);

            throw new \InvalidArgumentException($message);
        }

        $this->value = $value;
    }

    /**
     * Immutable equivalent of the {@see ServerCookie::setValue()} method.
     *
     * @psalm-immutable
     *
     * @param non-empty-string|\Stringable $value
     *
     * @return $this
     */
    public function withValue(string|\Stringable $value): self
    {
        $self = clone $this;
        $self->setValue($value);

        return $self;
    }

    /**
     * Returns string representation of the Cookie header value.
     *
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return Collection::build([
            new Attribute($this->getName(), $this->getValue()),
            ...$this->attributes,
        ]);
    }
}
