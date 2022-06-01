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
use Helix\Contracts\EventDispatcher\DispatcherInterface;
use Helix\EventDispatcher\Dispatcher;
use Helix\EventDispatcher\Listener;
use Helix\EventDispatcher\ListenerAwareTrait;

/**
 * @template TService of object
 *
 * @template-extends Definition<TService>
 * @template-implements SubscribableDefinitionInterface<TService>
 */
abstract class LazyDefinition extends Definition implements SubscribableDefinitionInterface
{
    use ListenerAwareTrait;

    /**
     * @var DispatcherInterface
     */
    private readonly DispatcherInterface $dispatcher;

    /**
     * @param \Closure():TService $initializer
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
     * @return TService
     */
    protected function initialize(): object
    {
        $this->dispatcher->dispatch(new Resolving($this));

        $result = ($this->initializer)();

        try {
            return $result;
        } finally {
            $this->dispatcher->dispatch(new Resolved($this, $result));
        }
    }
}
