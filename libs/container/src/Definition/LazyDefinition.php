<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Container\Definition;

use Helix\Container\Event\Resolved;
use Helix\Container\Event\Resolving;
use Helix\Contracts\EventDispatcher\ListenerInterface;
use Helix\Contracts\EventDispatcher\DispatcherInterface;
use Helix\EventDispatcher\Dispatcher;
use Helix\EventDispatcher\Exception\ListenerException;
use Helix\EventDispatcher\Listener;

/**
 * @template TDefinition of object
 * @template-extends Definition<TDefinition>
 */
abstract class LazyDefinition extends Definition
{
    /**
     * @var DispatcherInterface
     */
    private readonly DispatcherInterface $dispatcher;

    /**
     * @var ListenerInterface
     */
    private readonly ListenerInterface $listener;

    /**
     * @param \Closure():TDefinition $initializer
     * @param DispatcherInterface|null $parent
     */
    public function __construct(
        private readonly \Closure $initializer,
        DispatcherInterface $parent = null
    ) {
        $this->listener = new Listener();
        $this->dispatcher = new Dispatcher($this->listener, $parent);
    }

    /**
     * @return TDefinition
     */
    protected function initialize(): object
    {
        $this->dispatcher->dispatch(new Resolving($this));

        try {
            return $result = ($this->initializer)();
        } finally {
            if (isset($result)) {
                $this->dispatcher->dispatch(new Resolved($this, $result));
            }
        }
    }

    /**
     * @param callable(Resolving):void $handler
     * @return void
     * @throws ListenerException
     * @throws \Throwable
     */
    public function onResolving(callable $handler): void
    {
        $this->listener->listen(Resolving::class, $handler);
    }

    /**
     * @param callable(Resolving):void $handler
     * @return void
     * @throws ListenerException
     * @throws \Throwable
     */
    public function onResolved(callable $handler): void
    {
        $this->listener->listen(Resolved::class, $handler);
    }
}
