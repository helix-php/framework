<?php

declare(strict_types=1);

namespace Helix\Contracts\Http\Cookie;

interface CookieFactoryInterface
{
    /**
     * @param non-empty-string $name
     * @param string|\Stringable $value
     *
     * @return CookieInterface
     */
    public function create(string $name, string|\Stringable $value): CookieInterface;
}
