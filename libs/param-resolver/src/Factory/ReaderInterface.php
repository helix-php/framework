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

interface ReaderInterface
{
    /**
     * @param callable $callable
     * @return iterable<\ReflectionParameter>
     * @throws SignatureExceptionInterface
     */
    public function fromCallable(callable $callable): iterable;

    /**
     * @param class-string|object $class
     * @param non-empty-string $method
     * @return iterable<\ReflectionParameter>
     * @throws SignatureExceptionInterface
     */
    public function fromMethod(string|object $class, string $method): iterable;

    /**
     * @param callable-string $function
     * @return iterable<\ReflectionParameter>
     * @throws SignatureExceptionInterface
     */
    public function fromFunction(string $function): iterable;
}
