<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\ParamResolver\Factory;

use Helix\Contracts\ParamResolver\Exception\SignatureExceptionInterface;
use Helix\ParamResolver\Exception\SignatureException;

class StatelessReader implements ReaderInterface
{
    /**
     * {@inheritDoc}
     */
    public function fromCallable(callable $callable): iterable
    {
        try {
            $reflection = new \ReflectionFunction($callable(...));
        } catch (\Throwable $e) {
            throw SignatureException::fromException($e);
        }

        return $reflection->getParameters();
    }

    /**
     * {@inheritDoc}
     */
    public function fromMethod(object|string $class, string $method): iterable
    {
        try {
            $reflection = new \ReflectionMethod($class, $method);
        } catch (\Throwable $e) {
            throw SignatureException::fromException($e);
        }

        return $reflection->getParameters();
    }

    /**
     * {@inheritDoc}
     */
    public function fromFunction(string $function): iterable
    {
        try {
            $reflection = new \ReflectionFunction($function);
        } catch (\Throwable $e) {
            throw SignatureException::fromException($e);
        }

        return $reflection->getParameters();
    }
}
