<?php

declare(strict_types=1);

namespace Helix\Contracts\Http\Method;

interface MethodInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return bool
     */
    public function isIdempotent(): bool;

    /**
     * @return bool
     */
    public function isSafe(): bool;
}
