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
 * An exception thrown during processing of a passed argument
 * of a function and/or method.
 *
 * For example, an error may occur when the specified function is not found:
 * ```php
 *  $resolver->fromFunction('unknown_function');
 *  // > SignatureException: Function "unknown_function" not found.
 * ```
 */
interface SignatureExceptionInterface extends ParamResolverExceptionInterface
{
}
