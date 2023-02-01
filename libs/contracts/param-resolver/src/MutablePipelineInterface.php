<?php

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
