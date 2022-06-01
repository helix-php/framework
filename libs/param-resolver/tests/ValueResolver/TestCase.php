<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\ParamResolver\Tests\ValueResolver;

use Helix\Contracts\ParamResolver\MiddlewareInterface;
use Helix\ParamResolver\Tests\TestCase as BaseTestCase;
use Helix\ParamResolver\Tests\Wrapper\TestingValueMiddleware;

/**
 * @group param-resolver
 */
abstract class TestCase extends BaseTestCase
{
    protected function testingValueResolver(MiddlewareInterface $resolver): TestingValueMiddleware
    {
        return new TestingValueMiddleware($resolver, $this);
    }
}
