<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Http\Middleware\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @internal Helix\Http\Middleware\Pipeline\Next is an internal library class,
 *           please do not use it in your code.
 * @psalm-internal Helix\Http\Middleware
 */
final class Next implements RequestHandlerInterface
{
    /**
     * @param MiddlewareInterface $ctx
     * @param RequestHandlerInterface $next
     */
    public function __construct(
        private readonly MiddlewareInterface $ctx,
        private readonly RequestHandlerInterface $next,
    ) {
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Throwable
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->ctx->process($request, $this->next);
    }
}
