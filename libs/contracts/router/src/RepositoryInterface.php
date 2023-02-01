<?php

declare(strict_types=1);

namespace Helix\Contracts\Router;

/**
 * @template-extends \IteratorAggregate<mixed, RouteInterface>
 */
interface RepositoryInterface extends \IteratorAggregate, \Countable
{
    /**
     * @param non-empty-string $name
     * @return RouteInterface|null
     */
    public function find(string $name): ?RouteInterface;
}
