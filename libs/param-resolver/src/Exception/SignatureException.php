<?php

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
