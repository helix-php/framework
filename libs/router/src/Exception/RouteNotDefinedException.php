<?php

declare(strict_types=1);

namespace Helix\Router\Exception;

use Helix\Contracts\Router\Exception\NotFoundExceptionInterface;

class RouteNotDefinedException extends RouteMatchingException implements NotFoundExceptionInterface
{
    /**
     * @param string $name
     * @param int $code
     * @param \Throwable|null $prev
     * @return static
     */
    public static function notDefined(string $name, int $code = 0, \Throwable $prev = null): static
    {
        return new self(\sprintf('Route named [%s] has not been defined', $name), $code, $prev);
    }
}
