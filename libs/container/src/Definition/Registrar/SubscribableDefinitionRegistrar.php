<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Container\Definition\Registrar;

use Helix\Container\Definition\Repository\RegistrarInterface;
use Helix\Container\Event\Resolved;
use Helix\Container\Event\Resolving;
use Helix\Contracts\EventDispatcher\EventSubscriptionInterface;
use Helix\Contracts\EventDispatcher\ListenerInterface;
use Helix\EventDispatcher\ListenerAwareTrait;
use Helix\EventDispatcher\Subscription;

/**
 * @internal Helix\Container is an internal library class,
 *           please do not use it in your code.
 * @psalm-internal Helix\Container
 */
class SubscribableDefinitionRegistrar extends DefinitionRegistrar implements
    SubscribableDefinitionRegistrarInterface
{
    use ListenerAwareTrait;

    /**
     * @param non-empty-string $id
     * @param RegistrarInterface $registrar
     * @param ListenerInterface $events
     */
    public function __construct(
        string $id,
        RegistrarInterface $registrar,
        ListenerInterface $events,
    ) {
        $this->listener = $events;

        parent::__construct($id, $registrar);
    }

    /**
     * @param callable(Resolved,EventSubscriptionInterface<Resolved>|null):void $then
     * @return Subscription<Resolved>
     */
    public function resolved(callable $then): Subscription
    {
        return $this->listener->listen(Resolved::class, $then);
    }

    /**
     * @param callable(Resolving,EventSubscriptionInterface<Resolving>|null):void $then
     * @return Subscription<Resolving>
     */
    public function resolving(callable $then): Subscription
    {
        return $this->listener->listen(Resolving::class, $then);
    }
}
