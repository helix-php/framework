<?php

declare(strict_types=1);

namespace Helix\Contracts\Http\Header\Attribute;

interface AttributeInterface extends FlagInterface
{
    /**
     * Returns value of the attribute.
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * @psalm-immutable
     *
     * @param string|\Stringable $value
     *
     * @return self
     */
    public function withValue(string|\Stringable $value): self;
}
