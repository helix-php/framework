<?php

declare(strict_types=1);

namespace Helix\Contracts\Http\Header\Attribute;

interface FlagInterface
{
    /**
     * Returns name of the attribute.
     *
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * @psalm-immutable
     *
     * @param non-empty-string $name
     *
     * @return self
     */
    public function withName(string $name): self;
}
