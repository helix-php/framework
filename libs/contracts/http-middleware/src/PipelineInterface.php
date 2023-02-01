<?php

declare(strict_types=1);

namespace Helix\Contracts\Http\Middleware;

use Psr\Http\Server\MiddlewareInterface;

interface PipelineInterface extends MiddlewareInterface
{
    /**
     * @psalm-pure
     * @param MiddlewareInterface ...$middleware
     * @return self
     */
    public function through(MiddlewareInterface ...$middleware): self;
}
