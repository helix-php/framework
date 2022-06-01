<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\ParamResolver\Exception;

use Helix\Contracts\ParamResolver\Exception\NotResolvableExceptionInterface;
use Helix\ParamResolver\Parameter\Printer;

class ParamNotResolvableException extends ParamResolverException implements
    NotResolvableExceptionInterface
{
    /**
     * @param \ReflectionParameter $parameter
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        private readonly \ReflectionParameter $parameter,
        string $message,
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return \ReflectionParameter
     */
    public function getReflectionParameter(): \ReflectionParameter
    {
        return $this->parameter;
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return static
     */
    public static function fromReflectionParameter(\ReflectionParameter $parameter): self
    {
        $message = \vsprintf('Could not resolve parameter #%d of the %s(%s)', [
            $parameter->getPosition(),
            Printer::printParameterContext($parameter),
            Printer::printParameter($parameter),
        ]);

        return new self($parameter, $message);
    }
}
