<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Container\Exception;

class ServiceNotInstantiatableException extends ServiceNotResolvableException
{
    /**
     * @param non-empty-string $service
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        private readonly string $service,
        string $message,
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param \Throwable $e
     * @param non-empty-string $service
     * @return static
     */
    public static function fromException(\Throwable $e, string $service): self
    {
        $message = 'An error occurred while service [%s] instantiation: %s';
        $message = \sprintf($message, $service, $e->getMessage());

        return new self($service, $message, (int)$e->getCode(), $e);
    }

    /**
     * @param non-empty-string $service
     * @return static
     */
    public static function fromInvalidClass(string $service): self
    {
        $message = 'Can not create an object of a non-existent class [%s]';

        return new self($service, \sprintf($message, $service));
    }

    /**
     * @return non-empty-string
     */
    public function getId(): string
    {
        return $this->service;
    }
}
