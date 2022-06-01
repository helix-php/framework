<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\ParamResolver\Exception;

use Helix\Contracts\ParamResolver\Exception\ParamResolverExceptionInterface;

class ParamResolverException extends \Exception implements
    ParamResolverExceptionInterface
{
}
