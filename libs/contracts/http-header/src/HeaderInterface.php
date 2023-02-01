<?php

declare(strict_types=1);

namespace Helix\Contracts\Http\Header;

use Helix\Contracts\Http\Header\Attribute\AttributeInterface;
use Helix\Contracts\Http\Header\Attribute\FlagInterface;

interface HeaderInterface
{
    /**
     * Returns a list of header value attributes.
     *
     * Can contain either values in the {@see AttributeInterface} (name and
     * value) or {@see FlagInterface} (only name) formats.
     *
     * For example:
     * ```
     * > Set-Cookie: <attributes>
     *  [
     *      AttributeInterface(name: 'Name', 'Value'),
     *      AttributeInterface(name: 'Max-Age', value: 42),
     *      FlagInterface(name: 'Secure'),
     *      FlagInterface(name: 'HttpOnly'),
     *  ]
     *
     * // Can be presented in the format
     * > Set-Cookie: Name=Value; Max-Age=42; Secure; HttpOnly
     * ```
     *
     * @return list<FlagInterface>
     */
    public function getAttributes(): iterable;

    /**
     * @param non-empty-string $name
     *
     * @return FlagInterface|null
     */
    public function findAttribute(string $name): ?FlagInterface;

    /**
     * @param non-empty-string $name
     *
     * @return bool
     */
    public function hasAttribute(string $name): bool;

    /**
     * Updates the attributes of the {@see HeaderInterface} object.
     *
     * @psalm-immutable
     *
     * @param list<FlagInterface> $attributes
     *
     * @return self
     */
    public function withAttributes(iterable $attributes): self;

    /**
     * Adds an attributes of the {@see HeaderInterface} object.
     *
     * @psalm-immutable
     *
     * @param list<FlagInterface> $attributes
     *
     * @return self
     */
    public function withAddedAttributes(iterable $attributes): self;
}
