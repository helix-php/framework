<?php

declare(strict_types=1);

namespace Helix\Http\Cookie;

use Helix\Contracts\Http\Cookie\SameSiteInterface;

/**
 * Custom SameSite implementation.
 */
final class UserSameSite implements SameSiteInterface
{
    /**
     * @param non-empty-string $value
     */
    public function __construct(
        private readonly string $value,
    ) {}

    /**
     * {@inheritDoc}
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
