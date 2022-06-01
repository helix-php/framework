<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Container\Tests\Stub;

class ClassWithConstructorDefaults
{
    public function __construct(
        public readonly ?string $nullable,
        public readonly \StdClass $class = new \StdClass(),
        public readonly int $scalar = 42,
    ) {
    }
}
