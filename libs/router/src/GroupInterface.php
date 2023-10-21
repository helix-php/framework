<?php

declare(strict_types=1);

namespace Helix\Router;

use Helix\Contracts\Router\RouteInterface;

/**
 * @template-extends \Traversable<array-key, RouteInterface>
 */
interface GroupInterface extends \Traversable, \Countable
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $pattern
     */
    public function where(string $name, string $pattern): self;

    /**
     * @param mixed ...$middleware
     */
    public function through(mixed ...$middleware): self;

    /**
     * @param non-empty-string $prefix
     */
    public function prefix(string $prefix, bool $concat = false): self;

    /**
     * @param non-empty-string $suffix
     */
    public function suffix(string $suffix, bool $concat = true): self;

    /**
     * @param mixed $action
     */
    public function then(mixed $action): self;
}
