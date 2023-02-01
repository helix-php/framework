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
     * @param string $name
     * @param string $pattern
     * @return $this
     */
    public function where(string $name, string $pattern): self;

    /**
     * @param mixed ...$middleware
     * @return $this
     */
    public function through(mixed ...$middleware): self;

    /**
     * @param string $prefix
     * @param bool $concat
     * @return $this
     */
    public function prefix(string $prefix, bool $concat = false): self;

    /**
     * @param string $suffix
     * @param bool $concat
     * @return $this
     */
    public function suffix(string $suffix, bool $concat = true): self;

    /**
     * @param mixed $action
     * @return $this
     */
    public function then(mixed $action): self;
}
