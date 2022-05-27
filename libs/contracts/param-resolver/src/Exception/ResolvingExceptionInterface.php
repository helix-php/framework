<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Contracts\ParamResolver\Exception;

/**
 * An error that occurs during the resolution of some parameter.
 *
 * For example, such an error may occur if the parameter cannot
 * be correctly processed:
 * ```php
 *  $resolver->fromFunction(function (UnknownClass $e): void { ... });
 *
 *  ResolvingException: Parameter #0 [UnknownClass $e] can not be resolved
 *                      (UnknownClass has not been found).
 * ```
 */
interface ResolvingExceptionInterface extends ParamResolverExceptionInterface
{
}
