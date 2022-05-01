<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\ParamResolver\Metadata;

use Helix\ParamResolver\Metadata\Type\TypeInterface;

/**
 * @template T of TypeInterface
 */
interface TypeProviderInterface
{
    /**
     * @return T
     */
    public function getType(): TypeInterface;
}
