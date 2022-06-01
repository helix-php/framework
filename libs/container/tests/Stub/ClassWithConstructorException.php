<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Container\Tests\Stub;

class ClassWithConstructorException
{
    public function __construct()
    {
        throw new \BadMethodCallException('Whoops, something went wrong');
    }
}
