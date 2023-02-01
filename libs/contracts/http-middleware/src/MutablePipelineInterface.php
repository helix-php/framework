<?php

declare(strict_types=1);

namespace Helix\Contracts\Http\Middleware;

use Psr\Http\Server\MiddlewareInterface;

interface MutablePipelineInterface extends PipelineInterface
{
    /**
     * @param MiddlewareInterface $middleware
     * @return $this
     */
    public function append(MiddlewareInterface $middleware): self;

    /**
     * @param MiddlewareInterface $middleware
     * @return $this
     */
    public function prepend(MiddlewareInterface $middleware): self;
}
