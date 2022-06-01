<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\ParamResolver\Pipeline;

use Helix\Contracts\ParamResolver\MiddlewareInterface;
use Helix\Contracts\ParamResolver\ResolverInterface;

/**
 * @internal Helix\ParamResolver\Pipeline\Next is an internal library class,
 *           please do not use it in your code.
 * @psalm-internal Helix\ParamResolver
 */
final class Next implements ResolverInterface
{
    /**
     * @param MiddlewareInterface $ctx
     * @param ResolverInterface $next
     */
    public function __construct(
        private readonly MiddlewareInterface $ctx,
        private readonly ResolverInterface $next,
    ) {
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return mixed
     */
    public function handle(\ReflectionParameter $parameter): mixed
    {
        return $this->ctx->process($parameter, $this->next);
    }
}
