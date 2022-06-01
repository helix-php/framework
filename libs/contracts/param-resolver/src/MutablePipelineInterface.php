<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Contracts\ParamResolver;

interface MutablePipelineInterface extends PipelineInterface
{
    /**
     * @param iterable<MiddlewareInterface>|MiddlewareInterface $resolver
     * @param null|\Closure(MiddlewareInterface):bool $after
     * @return void
     */
    public function append(MiddlewareInterface|iterable $resolver, \Closure $after = null): void;

    /**
     * @param iterable<MiddlewareInterface>|MiddlewareInterface $resolver
     * @param null|\Closure(MiddlewareInterface):bool $before
     * @return void
     */
    public function prepend(MiddlewareInterface|iterable $resolver, \Closure $before = null): void;
}
