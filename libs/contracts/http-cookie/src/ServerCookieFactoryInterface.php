<?php

declare(strict_types=1);

namespace Helix\Contracts\Http\Cookie;

interface ServerCookieFactoryInterface
{
    /**
     * @param non-empty-string $name
     * @param string|\Stringable $value
     *
     * @return ServerCookieInterface
     */
    public function create(string $name, string|\Stringable $value): ServerCookieInterface;
}
