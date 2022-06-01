<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\ParamResolver\Parameter;

interface NotResolvableInterface
{
    /**
     * @return \ReflectionParameter
     */
    public function getParameter(): \ReflectionParameter;
}
