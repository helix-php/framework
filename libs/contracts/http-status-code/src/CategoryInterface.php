<?php

declare(strict_types=1);

namespace Helix\Contracts\Http\StatusCode;

interface CategoryInterface
{
    /**
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * @param bool $server
     * @return bool
     */
    public function isError(bool $server = false): bool;
}
