<?php

declare(strict_types=1);

namespace Helix\Contracts\Http\Cookie;

interface SameSiteInterface
{
    /**
     * Retrieves the SameSite Cookie's value.
     *
     * @return non-empty-string
     */
    public function getValue(): string;
}
