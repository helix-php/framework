<?php

declare(strict_types=1);

namespace Helix\Contracts\ParamResolver\Exception;

/**
 * An exception that occurs when the callable (method/function/etc) element
 * cannot be parsed.
 *
 * For example, in cases where the passed function does not exist.
 */
interface SignatureExceptionInterface extends ParamResolverExceptionInterface
{
}
