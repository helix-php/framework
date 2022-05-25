<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Boot\Attribute;

use Psr\Container\ContainerInterface;

abstract class Execution implements MethodMetadataInterface
{
    /**
     * @param array<class-string>|class-string $afterResolved
     */
    public function __construct(
        public readonly array|string $afterResolved = []
    ) {
    }

    /**
     * @param ContainerInterface $app
     * @return bool
     */
    public function shouldLoad(ContainerInterface $app): bool
    {
        foreach ((array)$this->afterResolved as $service) {
            if (!$app->has($service)) {
                return false;
            }
        }

        return true;
    }
}
