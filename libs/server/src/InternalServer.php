<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Server;

use Helix\Http\Psr17FactoryInterface;

/**
 * @template T of InternalServerCreateInfo
 * @template-extends Server<T>
 */
abstract class InternalServer extends Server
{
    /**
     * @param Psr17FactoryInterface $factory
     * @param T $info
     */
    public function __construct(Psr17FactoryInterface $factory, InternalServerCreateInfo $info)
    {
        parent::__construct($factory, $info);
    }
}
