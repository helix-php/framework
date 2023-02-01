<?php

declare(strict_types=1);

namespace Helix\Http\Cookie;

use Helix\Contracts\Http\Cookie\ServerCookieFactoryInterface;

final class ServerCookieFactory implements ServerCookieFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(string $name, string|\Stringable $value): ServerCookie
    {
        return new ServerCookie($name, $value);
    }

    /**
     * @param non-empty-string $name
     * @param string|\Stringable $value
     *
     * @return ServerCookie
     */
    public function session(string $name, string|\Stringable $value): ServerCookie
    {
        return $this->create($name, $value)->withMarkedAsSession();
    }

    /**
     * @param non-empty-string $name
     *
     * @return ServerCookie
     */
    public function delete(string $name): ServerCookie
    {
        return $this->create($name, '')->withMarkedAsDeleted();
    }
}
