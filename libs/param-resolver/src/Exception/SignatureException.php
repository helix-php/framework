<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\ParamResolver\Exception;

use Helix\Contracts\ParamResolver\Exception\SignatureExceptionInterface;

class SignatureException extends ParamResolverException implements
    SignatureExceptionInterface
{
    /**
     * @param \Throwable $e
     * @return static
     */
    public static function fromException(\Throwable $e): self
    {
        return new self($e->getMessage(), (int)$e->getCode(), $e);
    }
}
