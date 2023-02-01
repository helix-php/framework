<?php

declare(strict_types=1);

namespace Helix\Contracts\Http\StatusCode;

interface StatusCodeInterface
{
    /**
     * @return positive-int|0
     */
    public function getCode(): int;

    /**
     * @return string
     */
    public function getReasonPhrase(): string;

    /**
     * @return CategoryInterface
     */
    public function getCategory(): CategoryInterface;
}
