<?php

declare(strict_types=1);

namespace Helix\Router\Exception;

use Helix\Contracts\Router\Exception\NotAllowedExceptionInterface;

class RouteNotAllowedException extends RouteMatchingException implements NotAllowedExceptionInterface {}
