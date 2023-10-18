<?php

declare(strict_types=1);

namespace Helix\Router\Exception;

use Helix\Contracts\Router\Exception\NotFoundExceptionInterface;

class RouteNotFoundException extends RouteMatchingException implements NotFoundExceptionInterface {}
