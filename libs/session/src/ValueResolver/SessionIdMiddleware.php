<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Session\ValueResolver;

use Helix\Contracts\ParamResolver\MiddlewareInterface;
use Helix\ParamInfo\Type;
use Helix\Session\ManagerInterface;
use Helix\Session\SessionIdInterface;
use Psr\Http\Message\ServerRequestInterface;

class SessionIdMiddleware implements MiddlewareInterface
{
    /**
     * @param ManagerInterface $manager
     * @param ServerRequestInterface $request
     */
    public function __construct(
        private readonly ManagerInterface $manager,
        private readonly ServerRequestInterface $request,
    ) {
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return bool
     */
    public function supports(\ReflectionParameter $parameter): bool
    {
        return Type::fromParameter($parameter)
            ->allowsSubclassOf(SessionIdInterface::class);
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return SessionIdInterface
     */
    public function resolve(\ReflectionParameter $parameter): SessionIdInterface
    {
        return $this->manager->get($this->request);
    }
}
