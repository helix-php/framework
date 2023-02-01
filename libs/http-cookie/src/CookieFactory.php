<?php

declare(strict_types=1);

namespace Helix\Http\Cookie;

use Helix\Contracts\Http\Cookie\CookieFactoryInterface;

final class CookieFactory implements CookieFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(string $name, string|\Stringable $value): Cookie
    {
        return new Cookie($name, $value);
    }
}
