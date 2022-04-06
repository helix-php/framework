<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Router\Exception;

use Helix\Contracts\Router\Exception\NotAllowedExceptionInterface;

class RouteNotAllowedException extends RouteMatchingException implements NotAllowedExceptionInterface
{
}
