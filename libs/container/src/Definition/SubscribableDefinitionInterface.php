<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Container\Definition;

use Helix\Contracts\EventDispatcher\ListenerInterface;

/**
 * @template TService of object
 *
 * @template-extends DefinitionInterface<TService>
 */
interface SubscribableDefinitionInterface extends
    DefinitionInterface,
    ListenerInterface
{
}
